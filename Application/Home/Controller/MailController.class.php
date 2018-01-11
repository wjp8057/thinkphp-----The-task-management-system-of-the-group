<?php
namespace Home\Controller;
use Think\Controller;
class MailController extends BaseController {
    protected function common(){
    	$userid = session(C('USER_AUTH_KEY'));
    	$rel = M('mail_account')->where('id = '.$userid)->find();
    	if(!$rel)$this->error('请先配置邮箱！',U('Mail/config'));
    	return $rel;
    }
    protected function gettoname($mail){
    	$info = M('user')->where(array('email'=>$mail))->field('name')->find();
    	if($info)return $info['name'];
    	else return $mail;
    }
    public function maillist(){
    	$check = $this->common();
    	$userid = session(C('USER_AUTH_KEY'));
        $p = empty($_REQUEST['p']) ? 1 : $_REQUEST['p'];
        $size = empty($_REQUEST['size']) ? 10 : $_REQUEST['size'];
        $where = " 1 = 1 ";
        $where.=" and user_id = '$userid' and type=1 ";
    	$list = M('mail')->where($where)->order('create_time desc')->page("$p,$size")->select();
    	$count = M('mail')->where($where)->count();
    	$pager = new \Think\Page($count,$size);
    	$page = $pager->show();
    	$this->assign('list',$list);
        $this->assign('page',$page);
    	$this->display();
    }
    public function config(){
    	$userid = session(C('USER_AUTH_KEY'));
    	$rel = M('mail_account')->where('id = '.$userid)->find();
    	$data = I('post.');
    	if(!isset($data['id']))$data['id']=$userid;
    	if(IS_POST){
    		if($rel)$result = M('mail_account')->where('id='.$data['id'])->save($data);
    		else $result = M('mail_account')->add($data);
    		
    		if($result){
    			M('user')->where('id='.$data['id'])->save(array('email'=>$data['smtp_user']));
    			$this->success('邮箱配置保存成功！',U('Mail/config'));
    		}
    		else $this->error('邮箱配置保存失败！',U('Mail/config'));
    		exit();
    	}
    	$this->assign('info',$rel);
    	$this->display();
    }
    public function send($config = array(),$info = array()){
    	$data = I('post.');
    	$userinfo = getuserinfo();
    	$gettoname = $this->gettoname($info['user']);
    	if($data&&!$config){
    		$rel = sendmail($data['smtp_user'],'测试邮件','邮件配置成功！',$data);
    		if($rel){
    			maillog('测试邮件','邮件配置成功！',$data['smtp_user'].'|'.$userinfo['name'],$data['smtp_user'].'|'.$userinfo['name'],1);
    			exit(json_encode(array('status'=>1,'msg'=>'测试邮件发送成功！')));
    		}
    		else exit(json_encode(array('status'=>0,'msg'=>'测试邮件发送失败！')));
    	}
    	if($config&&$info){
    		$rel = sendmail($info['user'],$info['title'],$info['content'],$config);
    		if($rel){
    			maillog($info['title'],$info['content'],$config['smtp_user'].'|'.$userinfo['name'],$info['user'].'|'.$gettoname,1);
    			$this->success('邮件发送成功！',U('Mail/sendemail'));
    		}
    		else $this->error('邮件发送失败！',U('Mail/sendemail'));
    	}else{
    		$this->error('邮件发送失败！',U('Mail/sendemail'));
    	}
    }
    public function sendemail(){
    	$config = $this->common();
    	$id = I('id');
    	if($id){
    		$info = M('mail')->where("id=$id")->find();
    		$info['_to'] = getcutstr($info['to'],0);
    		$this->assign('info',$info);
    	}
    	if(IS_POST){
    		$data = I('post.');
    		exit($this->send($config,$data));
    	}
    	$this->display();
    }
    public function savemail(){
    	$data = I('post.');
    	$info = getuserinfo();
    	$save = D('mail')->where(array('title'=>$data['title'],'content'=>$data['content'],'to'=>$data['user']))->find();
    	if($save)exit(json_encode(array('status'=>0,'msg'=>'邮件已经保存！')));
    	else{
    		maillog($data['title'],$data['content'],$info['email'].'|'.$info['name'],$data['user'].'|'.$this->gettoname($data['user']),3);
    		exit(json_encode(array('status'=>1,'msg'=>'邮件成功保存在邮件箱！')));
    	}
    }
    public function dealmail(){
    	$data = I('post.');
    	foreach ($data['ids'] as $key => $value) {
    		if($data['type']==1){
    			D('mail')->where("id=$value")->delete();
    		}
    	}
    	exit(json_encode(array('status'=>1,'msg'=>'删除成功！')));
    }
    public function draftmail(){
    	$check = $this->common();
    	$userid = session(C('USER_AUTH_KEY'));
        $p = empty($_REQUEST['p']) ? 1 : $_REQUEST['p'];
        $size = empty($_REQUEST['size']) ? 10 : $_REQUEST['size'];
        $where = " 1 = 1 ";
        $where.=" and user_id = '$userid' and type=3 ";
    	$list = M('mail')->where($where)->order('create_time desc')->page("$p,$size")->select();
    	$count = M('mail')->where($where)->count();
    	$pager = new \Think\Page($count,$size);
    	$page = $pager->show();
    	$this->assign('list',$list);
        $this->assign('page',$page);
    	$this->display();
    }

}