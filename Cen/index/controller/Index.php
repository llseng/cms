<?php
namespace app\index\controller;

use app\common\logic as logic;

class Index extends \think\Controller
{
	
    public function index()
    {
		$methods = get_class_methods('\app\common\controller\getList');
		var_dump($methods);
		var_dump(MODULE,CONTROLLER,ACTION);
		var_dump(\Request::module());
		
		return $this->fetch();
		
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
}
