<?php

namespace app\admin\controller;

class Pub extends \Cencms\ApiBase
{
	//
	public function __construct()
	{
		parent::__construct();
	}

	public function tables()
	{
		return $this->fetch('public/tables');
	}
}