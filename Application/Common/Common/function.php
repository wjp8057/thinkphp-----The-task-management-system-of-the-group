<?php
function get_menu_list($list){
	$menu_list = getAllMenu();
	if($list != 'all'){
		$right = M('system_menu')->where("id in ($list)")->cache(true)->getField('right',true);
		foreach ($right as $val){
			$role_right .= $val.',';
		}
		$role_right = explode(',', $role_right);		
		foreach($menu_list as $k=>$mrr){
			foreach ($mrr['sub_menu'] as $j=>$v){
				if(!in_array($v['control'].'Controller@'.$v['act'], $role_right)){
					unset($menu_list[$k]['sub_menu'][$j]);
				}
			}
		}
	}
	return $menu_list;
}

function getAllMenu(){
	return	array(
			'work' => array('name'=>'工作','icon'=>'fa-clipboard','sub_menu'=>array(
					array('name'=>'工作日志','act'=>'worklog','control'=>'Work'),
					array('name'=>'工作列表','act'=>'worklist','control'=>'Work'),
					array('name'=>'任务列表','act'=>'task','control'=>'Work'),
					array('name'=>'任务状态','act'=>'status','control'=>'Work')
			)),
			'mail' => array('name'=>'邮件','icon'=>'fa-envelope','sub_menu'=>array(
					array('name'=>'已发送','act'=>'maillist','control'=>'Mail'),
					array('name'=>'写信','act'=>'sendemail','control'=>'Mail'),
					array('name'=>'草稿箱','act'=>'draftmail','control'=>'Mail'),
					array('name'=>'邮件设置','act'=>'config','control'=>'Mail'),
			)),
			'info' => array('name' => '信息', 'icon'=>'fa-comments', 'sub_menu' => array(
					array('name' => '我的信息', 'act'=>'index', 'control'=>'Info'),
			)),

			'form' => array('name' => '报表', 'icon'=>'fa-bars', 'sub_menu' => array(
					array('name' => '工作统计', 'act'=>'index', 'control'=>'Form'),
					
			)),
			'manager' => array('name' => '管理', 'icon'=>'fa-gears', 'sub_menu' => array(
					array('name' => '用户管理', 'act'=>'user', 'control'=>'Manager'),
					array('name' => '角色管理', 'act'=>'role', 'control'=>'Manager'),
					
			))

	);
}

function nav_list(){        
    $navigate = include APP_PATH.'Common/Conf/navigate.php';    
    $location = strtolower('Home/'.CONTROLLER_NAME);
    $arr = array(
        '首页'=>'javascript:void();',
        $navigate[$location]['name']=>'javascript:void();',
        $navigate[$location]['action'][ACTION_NAME]=>'javascript:void();',
    );
    return $arr;
}

function get_time($data){
	$time = array();
	$time_arr = explode('~',$data);
	$time['begin'] = strtotime($time_arr[0]);
	$time['end'] = strtotime($time_arr[1])+86399;
	return $time;
}
function getsubstr($string, $start, $length){
	if(mb_strlen($string,'utf-8')>$length){
          $str = mb_substr($string, $start, $length,'utf-8');
          return $str.'...';
    }else{
          return $string;
    }
}
function sendmail($to,$title,$content,$config) {
    require_once THINK_PATH.'Library/Vendor/phpmailer/PHPMailerAutoload.php';
    $mail = new PHPMailer;
    $mail->isSMTP();                                      
    $mail->SMTPAuth = true;                               
    $mail->Host = $config['smtp_server'];
    $mail->Username = $config['smtp_user'];                 
    $mail->Password = $config['smtp_pwd'];     
    $mail->Port = intval($config['smtp_port']);
    if($mail->Port === 465) $mail->SMTPSecure = 'ssl';
    $mail->CharSet = 'UTF-8';                        
    $mail->setFrom($config['smtp_user']);
    if(is_array($to)){
        foreach ($to as $v){
            $mail->addAddress($v);
        }
    }else{
        $mail->addAddress($to);
    }
    $mail->isHTML(true);
    $mail->Subject = $title;
    $mail->msgHTML($content);
    if(!$mail->send())return false;
    else return true;
}
function getcutstr($str,$num,$type='|'){
	if(is_string($str)){
		$arr = explode($type,$str);
		return $arr[$num];
	}
}

?>