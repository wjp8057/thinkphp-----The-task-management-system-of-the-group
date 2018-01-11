<?php
namespace Home\Controller;
use Think\Controller;
class BaseController extends Controller {
	function __construct(){
		parent::__construct();
		// $navigate = navigateinfo();
	}
    function _initialize(){
    	$authid = session(C('USER_AUTH_KEY'));
    	if(in_array(ACTION_NAME,array('login','logout','vertify')) || in_array(CONTROLLER_NAME,array('Ueditor','Uploadify'))){
        
        }else{
        	if($authid > 0 ){
        		$this->check_priv();
        	}else{
        		$this->error('请先登陆',U('Public/login'),1);
        	}
        }
        $nav_list = nav_list();
        $this->assign('nav_list',$nav_list);
    }
    private function check_priv(){
    	$controller = CONTROLLER_NAME;
    	$action = ACTION_NAME;
    	$uncheck = array('login','logout','vertifyHandle','vertify');
        $act_list = session('act_list');
    	if($controller == 'Index' && $action == 'index'){
    		return true;
    	}elseif($act_list == 'all'){
            return true;
        }elseif(strpos('ajax',$act) || in_array($act,$uncheck)){
    		return true;
    	}else{

    		$right = M('system_menu')->where("id in ($act_list)")->getField('right',true);

    		foreach ($right as $val){
    			$role_right .= $val.',';
    		}
    		$role_right = explode(',', $role_right);
/*    	    if(!in_array($controller.'Controller@'.$action, $role_right)){
    			$this->error('您没有操作权限,请联系超级管理员分配权限',U('Index/index'));
    		}*/
    	}
    }
}