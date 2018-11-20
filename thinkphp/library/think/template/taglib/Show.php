<?php
// +----------------------------------------------------------------------
// | LLS_WOODS [ Constantly improve yourself ]
// +----------------------------------------------------------------------
// | Copyright (c) 念菲网络 (http://www.cencms.com)
// +----------------------------------------------------------------------
// | Creation time 2018-11-20 09:46:08
// +----------------------------------------------------------------------
// | Author: lls_woods <1300904522@qq.com>
// +----------------------------------------------------------------------

namespace think\template\taglib;

use think\template\TagLib;

/**
 * Show 数据展示 标签库解析类
 * @category   Think
 * @package  Think
 * @subpackage  Driver.Taglib
 * @Author: lls_woods <1300904522@qq.com>
 */
class Show extends Taglib
{
	// 标签定义
    protected $tags = [
        // 标签定义： attr 属性列表 close 是否闭合（0 或者1 默认1） alias 标签别名 level 嵌套层次
        'list'        => ['attr' => 'name,type,order,start,num', 'alias' => 'List,LIST'],
	];
	
	protected function error($str)
	{
		return "<!--{$str}-->";
	}

	/*
	 * list 标签解析 获取对应数据列表并输出
	 * 格式:
	 * {list name='now' type='top' order='create_time' start='10' num='20'}
	 * 	{now.title}
	 * {/list}
     * @access public
     * @param  array $tag 标签属性
     * @param  string $content 标签内容
     * @return string|void
	*/
	public function tagList($tag,$content)
	{
		
		$name = strtolower($tag['name']);
		unset($tag['name']);
		
		//关联类
		$class = '\app\common\controller\getList';
		
		//类可用方法
		$methods = get_class_methods($class);
		//var_dump($methods); die();
		
		//name属性不存在 或者 试图调用魔术方法 或者 没有方法
		if( empty($name) || strpos('$'.$name,'__')===1 || !in_array($name,$methods) )
		{
			return self::error('方法名不可用');
		}
		
		//随机字符 防止数据污染
		$rand = rand();
		
		//输出字符
		$parseStr = '';
		
		//获取数据
		$parseStr .= '<?php $list_' . $rand . ' = ' . $class . '::' . $name . '(' . var_export($tag,1) . '); ?>';
		
		$parseStr .= '{volist name="$list_' . $rand . '" id="li"}';
		
		$parseStr .= $content;
		
		$parseStr .= '{/volist}';
		
		return $parseStr;
		
	}

}
