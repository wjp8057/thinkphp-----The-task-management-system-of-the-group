<?php
namespace Home\Controller;
use Think\Controller;
use Think\Verify;
class PublicController extends BaseController {
    public function login(){
    	$isShowCode = get_config('login_verify_code');
    	$this->assign('isShowCode',$isShowCode);
    	$authid = session(C('USER_AUTH_KEY'));
    	if (isset($authid)) {
			$this->error("您已登录！",U('Home/Index/index'));
		}
    	if(IS_POST){
            $verify = new Verify();
            if ($isShowCode&&!$verify->check(I('post.vertify'), "admin_login")) {
            	exit(json_encode(array('status'=>0,'msg'=>'验证码错误！')));
            }
            $condition['emp_no'] = I('post.username');
            $condition['password'] = I('post.password');
            if(!empty($condition['emp_no']) && !empty($condition['password'])){
                $condition['password'] = md5($condition['password']);
                $condition["is_del"] = array('eq', 0);
               	$admin_info = M('user')->join(__PREFIX__."admin_role ON ".__PREFIX__."user.role_id=".__PREFIX__."admin_role.role_id")->where($condition)->find();
                if(is_array($admin_info)){
                    session(C('USER_AUTH_KEY'), $admin_info['id']);
                    session('act_list', $admin_info['act_list']);
					session('emp_no', $admin_info['emp_no']);
					session('user_name', $admin_info['name']);
					session('user_pic', $admin_info['pic']);
					session('dept_id', $admin_info['dept_id']);
                    $User = M('User');
					$data = array();
					$data['id'] = $admin_info['id'];
					$data['last_login_time'] = time();
					$data['login_count'] = array('exp', 'login_count+1');
					$data['last_login_ip'] = get_client_ip();
					$User -> save($data);
                    $url = session('from_url') ? session('from_url') : U('Home/Index/index');
                    exit(json_encode(array('status'=>1,'url'=>$url)));
                }else{
                    exit(json_encode(array('status'=>0,'msg'=>'账号密码不正确！')));
                }
            }else{
                exit(json_encode(array('status'=>0,'msg'=>'请填写账号密码！')));
            }
        }

        $this->display();
    }
    public function vertify(){
        $config = array(
            'fontSize' => 30,
            'length' => 4,
            'useCurve' => true,
            'useNoise' => false,
        	'reset' => false
        );    
        $Verify = new Verify($config);
        $Verify->entry("admin_login");
    }
    public function logout(){
        session_unset();
        session_destroy();
        $this->success("退出成功！",U('Public/login'));
    }
    public function ajaxupdatepwd(){
        $data = I('post.');
        if($data['val']=='')exit(json_encode(array('status'=>0,'msg'=>'密码不能为空！')));
        if(strlen($data['val'])<5)exit(json_encode(array('status'=>0,'msg'=>'密码长度不小于5！')));
        $rel = M('user')->where("id={$data['id']}")->save(array('password'=>md5($data['val'])));
        if($rel)exit(json_encode(array('status'=>1,'msg'=>'修改密码成功！')));
        else exit(json_encode(array('status'=>0,'msg'=>'修改密码失败！')));
    }
}