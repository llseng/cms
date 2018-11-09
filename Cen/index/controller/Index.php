<?php
namespace app\index\controller;

use app\common\logic as logic;

class Index
{
    public function index()
    {
		$json = \Addons\Taobao\TelAddr::get(18797818194);
		var_dump($json);
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
}
