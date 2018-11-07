<?php
namespace app\index\controller;

use app\common\logic as logic;

class Index
{
    public function index()
    {
		$a = logic\Mch::create([]);
        return json($a);
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
}
