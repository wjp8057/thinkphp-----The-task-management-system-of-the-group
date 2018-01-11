<?php
namespace Home\Controller;
use Think\Controller;
class WorkController extends BaseController {
    public function worklist(){
    	$userid = session(C('USER_AUTH_KEY'));
    	$res = $list = array();
        $p = empty($_REQUEST['p']) ? 1 : $_REQUEST['p'];
        $size = empty($_REQUEST['size']) ? 10 : $_REQUEST['size'];
        $where = " 1 = 1 ";
        $username = trim(I('username'));
        $username && $where.=" and user_name = '$username' ";
    	if($userid!=1)
    		$where = $where.=" and user_id = $userid ";
    	$rangetime = trim(I('rangetime'));
    	$rangetime && $time = get_time($rangetime);
		$time && $where.=" and addtime between {$time['begin']} and {$time['end']} ";
    	$list = M('worklog')->where($where)->order('addtime desc')->page("$p,$size")->select();
    	$count = M('worklog')->where($where)->count();
    	$pager = new \Think\Page($count,$size);
    	$page = $pager->show();

    	$userlist = M('user')->where("id>1")->field('name')->select();
    	$this->assign('userlist',$userlist);
    	$this->assign('username',$username);
    	$this->assign('time',$rangetime);
    	$this->assign('list',$list);
        $this->assign('page',$page);
    	$this->display();
    }
    public function worklog(){
    	$userid = session(C('USER_AUTH_KEY'));
    	$log = M('worklog');
    	$list = $log->where(array('user_id'=>$userid))->select();
    	$info = array();
    	foreach ($list as $key => $value) {
    		$k = date('Y-m-d',$value['addtime']);
    		$info[$k] = intval(date('d',$value['addtime'])).'<br>工作内容和计划';
    	}
    	$this->assign('info',json_encode($info));
    	$this->display();
    }
    public function ajaxwork(){
    	if(IS_POST){
    		$time = I('time');
    		$userid = session(C('USER_AUTH_KEY'));
    		$begintime = strtotime($time);
    		$endtime = $begintime+86400;
    		$where['addtime'] = array('between',$begintime.','.$endtime);
    		$where['user_id'] = $userid;
    		$info = M('worklog')->where($where)->find();
    		if($info){
                
                exit(json_encode(array('title'=>'修改','field'=>$info['id'],'content'=>$info['content'],'plan'=>$info['plan'],'times'=>$time)));
            }
    		else {
                
                exit(json_encode(array('title'=>'添加','field'=>$userid,'username'=>$info['user_name'],'times'=>$time)));
            }
    	}
    }
    public function ajaxhandlework(){
    	$data = I('post.');
    	$log = M('worklog');
    	$righttime = date('Y-m-d',time());
    	$info = getuserinfo();
        if($data['act']=='del'){
            $result = $log->where(array('id'=>$data['del_id']))->delete();
            if($result)exit(json_encode(array('status'=>1,'msg'=>'删除成功！')));
        }
    	if($righttime == $data['times']){
    		
    		if($data['title']=='修改'){
                sendmsg('修改'.$righttime.'工作内容和计划',1);
    			$rel = array('content'=>$data['con'],'addtime'=>time(),'plan'=>$data['plan']);
    			$result = $log->where(array('id'=>$data['field']))->save($rel);
    		}
    		if($data['title']=='添加'){
                sendmsg('添加'.$righttime.'工作内容和计划',1);
    			$rel = array('content'=>$data['con'],'addtime'=>time(),'plan'=>$data['plan'],'user_id'=>$info['id'],'user_name'=>$info['name']);
    			$result = $log->add($rel);
    		}

    		if($result)exit(json_encode(array('status'=>1,'msg'=>'操作成功！')));
    	}else{
    		exit(json_encode(array('status'=>0,'msg'=>'仅能在本日操作！')));
    	}
    }
    public function task(){
        $userid = session(C('USER_AUTH_KEY'));
        $res = $list = array();
        $p = empty($_REQUEST['p']) ? 1 : $_REQUEST['p'];
        $size = empty($_REQUEST['size']) ? 10 : $_REQUEST['size'];
        $where = " 1 = 1 ";
        $username = trim(I('username'));
        $username && $where.=" and user_name = '$username' ";
        $rangetime = trim(I('rangetime'));
        $rangetime && $time = get_time($rangetime);
        $time && $where.=" and create_time between {$time['begin']} and {$time['end']} ";
        $list = M('task')->where($where)->order('create_time desc')->page("$p,$size")->select();
        foreach ($list as $key => $value) {
            if($value['status']==0)$list[$key]['_status'] = '未解决';
            else if($value['status']==1)$list[$key]['_status'] = '<font color="red">正在解决</font>';
            else if($value['status']==2)$list[$key]['_status'] = '<font color="green">已解决</font>';
        }
        $count = M('task')->where($where)->count();
        $pager = new \Think\Page($count,$size);
        $page = $pager->show();

        $userlist = M('user')->where("id>1")->field('name')->select();
        $this->assign('userid',$userid);
        $this->assign('userlist',$userlist);
        $this->assign('username',$username);
        $this->assign('time',$rangetime);

        $this->assign('list',$list);
        $this->assign('page',$page);
        $this->display();
    }
    public function handletask(){
        if(I('id')){
            $info = M('task')->where(array('id'=>I('id')))->find();
            $this->assign('info',$info);
        }else{
            $info['expected_time'] = time();
            $this->assign('info',$info);
        }
        I('id')?$act='edit':$act='add';
        $this->assign('act',$act);
        if(IS_POST){
            $data = I('post.');
            $data['create_time'] = time();
            $data['expected_time'] = strtotime($data['expected_time']);
            if($data['act']=='add'){
                $rel = M('task')->add($data);
                $user = M('user')->where(array('name'=>$data['user_name']))->find();
                sendmsg('被分配了一个任务',2,$user['id']);
            }
            if($data['act']=='edit'){
                $rel = M('task')->where(array('id'=>$data['taskid']))->save($data);
                $user = M('user')->where(array('name'=>$data['user_name']))->find();
                sendmsg('被分配了一个任务',2,$user['id']);

            }
            if($data['act']=='del'){
                $rel = M('task')->where(array('id'=>$data['del_id']))->delete();
            }
            if($rel)$this->success('操作成功！',U('Work/task'));
            else $this->error('操作失败！',U('Work/task'));
            exit();
        }
        $userlist = M('user')->where("id>1")->field('name')->select();
        $this->assign('userlist',$userlist);
        $this->display();
    }
    public function status(){
        $user = getuserinfo();
        $condition['status'] = array('lt',2);
        if($user['id']<3)$condition = " 1=1 ";
        else $condition['user_name'] = $user['name'];
        $list = M('task')->where($condition)->limit('10')->select();
        foreach ($list as $key => $value) {
            $list[$key]['width']=intval(($value['expected_time']-$value['create_time'])/(time()-$value['create_time'])*100);
            if($value['status']==0)$list[$key]['_status'] = '未解决';
            else if($value['status']==1)$list[$key]['_status'] = '<font color="red">正在解决</font>';
            else if($value['status']==2)$list[$key]['_status'] = '<font color="green">已解决</font>';
        }
        $this->assign('list',$list);
        $this->display();
    }
    public function updatestatus(){
        $status = I('post.status');
        $id = I('post.id');
        $rel = D('task')->where('id='.$id)->save(array('status'=>$status));
        if($rel){
            sendmsg('修改任务ID为'.$id.'的任务状态',1);
            exit(json_encode(array('status'=>1,'msg'=>'任务状态修改成功！')));
        }
    }
    
}