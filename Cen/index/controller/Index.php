<?php
namespace app\index\controller;

use app\common\logic as logic;

class Index
{
    public function index()
    {
		var_dump(MODULE,CONTROLLER,ACTION);
		var_dump(\Request::module());
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
}
