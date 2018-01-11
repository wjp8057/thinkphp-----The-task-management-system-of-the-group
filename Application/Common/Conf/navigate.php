<?php
// 面包屑导航配置
    return array(        
           	'home/work'=>array(
                'name' =>'工作',
                'action'=>array(
                     'worklog'=>'工作日志',
                     'worklist'=>'工作列表',           
                     'task'=>'任务列表',
                     'status'=>'任务状态',
                     'handletask'=>'编辑任务',
            	    )
               ), 
            'home/mail'=>array(
                'name' =>'邮件',
                'action'=>array(
                     'maillist'=>'邮件列表',
                     'sendemail'=>'写信',           
                     'draftmail'=>'草稿箱',
                     'config'=>'邮件设置',
                    )
               ), 
            'home/info'=>array(
                'name' =>'信息',
                'action'=>array(
                     'index'=>'我的信息',
                    )
               ), 
            'home/form'=>array(
                'name' =>'报表',
                'action'=>array(
                     'index'=>'工作统计',
                    )
               ),
            'home/manager'=>array(
                'name' =>'管理',
                'action'=>array(
                     'user'=>'用户管理',
                     'role'=>'角色管理',
                     'userhandle'=>'编辑用户',
                     'rolehandle'=>'编辑角色',
                    )
               ),               		   
    );
?>
