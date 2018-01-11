<?php
namespace Home\Controller;
use Think\Controller;
class FormController extends BaseController {
    public function index(){
    	$users = D('user')->where('role_id>2')->select();
    	foreach ($users as $key => $value) {
    		$names[] = $value['name'];
    	}
    	$data = D('task')->field('user_name,count(id) as num')->group('user_name,status')->select();
    	$num = count($data)/3;
    	foreach ($data as $key => $value) {
			$rel[0]['name'] = '未解决';
			$rel[0]['type'] = 'bar';
			for ($i=0; $i < $num; $i++) { 
				$rel[0]['data'][$i] = $data[0+$i*3]['num'];
			}
			$rel[1]['name'] = '正在解决';
			$rel[1]['type'] = 'bar';
			for ($j=0; $j < $num; $j++) { 
				$rel[1]['data'][$j] = $data[1+$j*3]['num'];
			}
			$rel[2]['name'] = '已解决';
			$rel[2]['type'] = 'bar';
			for ($k=0; $k < $num; $k++) { 
				$rel[2]['data'][$k] = $data[2+$k*3]['num'];
			}
    	}
    	$this->assign('names',json_encode($names));
    	$this->assign('data',json_encode($rel));
        $this->display();
    }
}
