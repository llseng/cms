<?php
namespace Cencms;

use \Request;

class ApiBase
{

    /**
     * 视图类实例
     * @var \think\View
     */
    protected $view;

    /**
     * Request实例
     * @var \think\Request
     */
    protected $request;

	//构造函数
	public function __construct(App $app = null)
	{
        $this->app     = $app ?: \think\Container::get('app');
		
        $this->request = $this->app['request'];
		
        $this->view    = $this->app['view'];
		
		
		//请求IP
		!defined("REQUEST_IP") && define("REQUEST_IP",Request::ip());
		
		//请求模块
		!defined("MODULE_NAME") && define("MODULE_NAME",Request::module());
		
		//请求控制器
		!defined("CONTROLLER_NAME") && define("CONTROLLER_NAME",Request::controller());
		
		//请求方法
		!defined("ACTION_NAME") && define("ACTION_NAME",Request::action());
		
		//请求方式
		!defined("METHOD") && define("METHOD",Request::method());
		
		//是否是POST提交
		!defined("IS_POST") && define("IS_POST", METHOD == 'POST');
		
		if( CONTROLLER_NAME == "Base" ) exit(json_encode(self::returnError()));
		
	}
	
	//返回数据
	protected function returnData($status,$msg = '')
	{
		//基础参数
		$data = [
			'status' => 0,
			'msg' => ''
		];
		//状态
		$data['status'] = $status ? 1 : 0 ;
		//信息
		$data['msg'] = is_string($msg) ? $msg : '';
		
		return $data;
	}
	
	//成功返回
	protected function returnSuccess($arr = [],$msg = 'Success')
	{
		//基础数据
		$data = self::returnData(1,$msg);
		//返回数据
		$returnData = array_merge($arr,$data); //附加数据放在第一个参数,防止基础数据被替换
		
		return $returnData;
	}
	
	//失败返回
	protected function returnError($msg = 'Fail',$arr = [])
	{
		//基础数据
		$data = self::returnData(0,$msg);
		//返回数据
		$returnData = array_merge($arr,$data);
		
		return $returnData;
	}
	
	
	

    /**
     * 加载模板输出
     * @access protected
     * @param  string $template 模板文件名
     * @param  array  $vars     模板输出变量
     * @param  array  $config   模板参数
     * @return mixed
     */
    protected function fetch($template = '', $vars = [], $config = [])
    {
        return $this->view->fetch($template, $vars, $config);
    }

    /**
     * 渲染内容输出
     * @access protected
     * @param  string $content 模板内容
     * @param  array  $vars    模板输出变量
     * @param  array  $config  模板参数
     * @return mixed
     */
    protected function display($content = '', $vars = [], $config = [])
    {
        return $this->view->display($content, $vars, $config);
    }

    /**
     * 模板变量赋值
     * @access protected
     * @param  mixed $name  要显示的模板变量
     * @param  mixed $value 变量的值
     * @return $this
     */
    protected function assign($name, $value = '')
    {
        $this->view->assign($name, $value);

        return $this;
    }

    /**
     * 视图过滤
     * @access protected
     * @param  Callable $filter 过滤方法或闭包
     * @return $this
     */
    protected function filter($filter)
    {
        $this->view->filter($filter);

        return $this;
    }

    /**
     * 初始化模板引擎
     * @access protected
     * @param  array|string $engine 引擎参数
     * @return $this
     */
    protected function engine($engine)
    {
        $this->view->engine($engine);

        return $this;
    }

}