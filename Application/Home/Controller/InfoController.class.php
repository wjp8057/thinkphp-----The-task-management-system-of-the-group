<?php
namespace Home\Controller;
use Think\Controller;
class InfoController extends BaseController {
    public function index(){
        $userid = session(C('USER_AUTH_KEY'));
        $res = $list = array();
        $p = empty($_REQUEST['p']) ? 1 : $_REQUEST['p'];
        $size = empty($_REQUEST['size']) ? 10 : $_REQUEST['size'];
        $where = " 1 = 1 ";
        $rangetime = trim(I('rangetime'));
        $rangetime && $time = get_time($rangetime);
        $time && $where.=" and addtime between {$time['begin']} and {$time['end']} ";
        if($userid!=1)
    		$where = $where.=" and tos = $userid ";
        $list = M('info')->where($where)->order('addtime desc')->page("$p,$size")->select();
        $count = M('info')->where($where)->count();
        $pager = new \Think\Page($count,$size);
        $page = $pager->show();
        $userlist = M('user')->where("id>1")->select();
        foreach ($userlist as $key => $value) {
        	$names[$value['id']] = $value['name'];
        }
        $this->assign('userlist',$names);
        $this->assign('time',$rangetime);
        $this->assign('list',$list);
        $this->assign('page',$page);
        $this->display();
    }
    public function updateread(){
    	$id = I('post.id');
    	D('info')->where(array('id'=>$id))->save(array('is_read'=>1));
    	exit(json_encode(1));
    }
}