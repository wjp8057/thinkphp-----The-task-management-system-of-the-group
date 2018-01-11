<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends BaseController {
    public function index(){
    	$adminInfo = getuserinfo();
    	$list = session('act_list');
    	$menuList = get_menu_list($list);
    	$this->assign('list',$menuList);
    	$this->assign('admininfo',$adminInfo);
        $this->display();
    }
}