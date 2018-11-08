<?php
namespace app\index\controller;

use app\common\logic as logic;

class Index
{
    public function index()
    {
		var_dump(logic\Sms::send('123456','18797818194'));
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
}
