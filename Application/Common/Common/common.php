<?php
function get_config($code){
	$model = M("system_config");
	$where['code'] = array('eq',$code);
	$count = $model->where($where)->count();
	if($count>1)
		return $model->where($where)->getfield("val,name");
	else 
		return $model->where($where)->getfield("val");
}

function getuserinfo(){
	$userid = session(C('USER_AUTH_KEY'));
    $where['id'] = $userid;
    $info = M('user')->where($where)->find();
    return $info; 	
}
function maillog($title,$content,$from,$to,$type){
	$info = getuserinfo();
	$data = array('title'=>$title,'content'=>$content,'from'=>$from,'to'=>$to,'user_id'=>$info['id'],'user_name'=>$info['name'],'create_time'=>time(),'type'=>$type);
	D('mail')->add($data);
}
function sendmsg($content,$type,$id=''){
	$info = getuserinfo();
	$admin = D('user')->where('role_id=2')->find();
	$data = array('msg'=>$content,'type'=>$type,'addtime'=>time());
	if($type==1){
		$data['from'] = $info['id'];
		$data['tos'] = $admin['id'];
	}
	if($type==2){
		$data['from'] = $admin['id'];
		$data['tos'] = $id;
	}
	D('info')->add($data);
}
?>