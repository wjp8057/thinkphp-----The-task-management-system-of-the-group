<?php
namespace Home\Controller;
use Think\Controller;
class ManagerController extends BaseController {
    public function user(){
    	$list = D('user')->query("SELECT * FROM __PREFIX__user AS a LEFT JOIN __PREFIX__admin_role AS b ON a.role_id=b.role_id WHERE a.id>1");
    	$this->assign('list',$list);
    	$this->display();
    }
    public function userhandle(){
    	$id = I('get.user_id');
    	$id ? $act='edit':$act="add";
    	if($id)
    	$info = M('user')->where('id='.$id)->find();
        $info['password'] = '';
    	$role = M('admin_role')->where('role_id>1')->select();
    	$this->assign('role',$role);
    	$this->assign('info',$info);
    	$this->assign('act',$act);
    	if(IS_POST){
    	$data = I('post.');
        if(empty($data['password'])){
            unset($data['password']);
        }else{
            $data['password'] = md5($data['password']);
        }
    	if($data['act']=='del'){
    		$rel = D('user')->where("id=".$data['del_id'])->delete();
    		if($rel)exit(json_encode(array('status'=>1,'msg'=>'删除成功！')));
    	}
    	if($data['act']=='add'){
    		$data['create_time'] = time();
    		$have = M('user')->where(array('emp_no'=>$data['emp_no']))->find();
    		if($have) $this->error('已经存在该用户！',U('Manager/user'));
    		$rel = D('user')->add($data);
    	}
    	if($data['act']=='edit'){
    		$rel = D('user')->where("id=".$data['uid'])->save($data);
    	}
    	if($rel)$this->success('操作成功！',U('Manager/user'));
        else $this->error('操作失败！',U('Manager/user'));
        exit();
    	}
    	$this->display();
    }
    public function role(){
    	$list = D('admin_role')->where("role_id>1")->order('role_id')->select();
    	$this->assign('list',$list);
    	$this->display();
    }
    public function rolehandle(){
        $role_id = I('get.role_id');
        $tree = $detail = array();
        if($role_id){
            $detail = M('admin_role')->where("role_id=$role_id")->find();
            $detail['act_list'] = explode(',', $detail['act_list']);
            $this->assign('detail',$detail);
        }
        $right = M('system_menu')->order('id')->select();
        foreach ($right as $val){
            if(!empty($detail)){
                $val['enable'] = in_array($val['id'], $detail['act_list']);
            }
            $modules[$val['group']][] = $val;
        }
        $group = array('work'=>'工作','mail'=>'邮件','info'=>'消息','form'=>'报表','manager'=>'管理');
        $this->assign('group',$group);
        $this->assign('modules',$modules);
        $this->display();
    }
    public function rolesave(){
        $data = I('post.');
        $res = $data['data'];
        $res['act_list'] = is_array($data['right']) ? implode(',', $data['right']) : '';
        if(empty($data['role_id'])){
            $r = D('admin_role')->add($res);
        }else{
            $r = D('admin_role')->where('role_id='.$data['role_id'])->save($res);
        }
        if($r){
            $this->success("操作成功!",U('Home/Manager/role',array('role_id'=>$data['role_id'])));
        }else{
            $this->success("操作失败!",U('Home/Manager/role'));
        }
    }
    public function roledel(){
        $role_id = I('post.role_id');
        $admin = D('admin')->where('role_id='.$role_id)->find();
        if($admin){
            exit(json_encode(array('status'=>0,'msg'=>"请先清空所属该角色的管理员")));
        }else{
            $d = M('admin_role')->where("role_id=$role_id")->delete();
            if($d){
                exit(json_encode(array('status'=>1,'msg'=>"删除成功")));
            }else{
                exit(json_encode(array('status'=>1,'msg'=>"删除失败")));
            }
        }
    }
}