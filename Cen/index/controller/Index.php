<?php
namespace app\index\controller;

use \Cencms\Test;

class Index
{
    public function index()
    {
		$Test = new Test();
		$Test->print();
        return ;
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
}
