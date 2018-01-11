<?php
if(file_exists("./Install/") && !file_exists("./Install/install.lock")){
	if($_SERVER['PHP_SELF'] != '/index.php'){
		header("Content-type: text/html; charset=utf-8");         
		exit("<center>请在域名根目录下安装,如:<br/> www.xxx.com/index.php 正确 <br/>  www.xxx.com/www/index.php 错误,域名后面不能圈套目录, 但项目没有根目录存放限制,可以放在任意目录,apache虚拟主机配置一下即可</center>");
	}  
	header('Location:/Install/index.php');
	exit(); 
}
define('APP_PATH','./Application/');
define ('RUNTIME_PATH', './Runtime/' );
define('APP_STATUS', 'config');
define('APP_DEBUG',true);
define('__PREFIX__','oa_');
require './ThinkPHP/ThinkPHP.php';