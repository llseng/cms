<?php
// +----------------------------------------------------------------------
// | LLS_WOODS [ Constantly improve yourself ]
// +----------------------------------------------------------------------
// | Copyright (c) 念菲网络 (http://www.cencms.com)
// +----------------------------------------------------------------------
// | Creation time 2018-11-07
// +----------------------------------------------------------------------
// | Author: lls_woods <1300904522@qq.com>
// +----------------------------------------------------------------------

namespace app\common\logic;

use \Db;
use \Cache;

class Config 
{
    //单例例子
    static public $case;
    
    //配置信息
    protected $config;

    //缓存键名
    static public $cacheName = 'CONFIG_DATA';

    //缓存时效 单位秒
    static public $cacheTime = 86400;

    //构造函数
    public function __construct()
    {
        //获取配置信息
        $this->config = $this->getConfigData();
    }

    //获取配置信息
    private function getConfigData()
    {
        //是否已有缓存数据
        $cacheData = Cache::get(self::$cacheName);
        if( $cacheData ) return $cacheData;

        //获取去配置数据
        $data = Db::name("config")->select();

        $config = [];

        foreach($data as $key => $val)
        {
            $group = trim_all( $val['group'] );

            if( $group )
            {

                $config[$group][$val['name']] = ['value' => $val['value'],'intro' => $val['intro']];
            }else{

                $config['default'][$val['name']] = ['value' => $val['value'],'intro' => $val['intro']];
            }
        }

        //存入缓存中
        Cache::Set(self::$cacheName,$config,self::$cacheTime);

        return $config;

    }



    //获取单例
    static public function init()
    {
        
        if( !self::$case )
        {
            self::$case = new self();
        }

        return  self::$case;

    }

    //获取缓存配置
    static public function get($string)
    {
        //
        $find = ["group"=>false,'name'=>false];
        //是否查找二级
        $dStartNum = strpos($string,'.');

        if( $dStartNum !== false )
        {
            //组名
            $find['group'] = substr($string,0,$dStartNum);
            //键名
            $find['name'] = substr($string,$dStartNum+1);

            if( empty($find['group']) && $find['name'] )
            {
                $find['group'] = $find['name'];
                $find['name'] = '';
            }

        }else{
            //键名
            $find['name'] = $string;
        }

        $result = self::getConfig($find['name'],$find['group']);
        
        if( $result['value'] ) return $result['value'];

        return $result;
    }

    //获取缓存配置
    static private function getConfig($name,$group = false)
    {//var_dump(self::init()->config);die;
        //获取组名
        $group || $group = "default";

        if( $name )
        {
            $result = self::init()->config[$group][$name];
        }else{
            //die($group);
            $result = self::init()->config[$group];
        }

        return $result ?: false;
        
    }

    //删除站点配置缓存
    static public function rmCache()
    {
        Cache::rm(self::$cacheName);
        
        return true;
    }

    //设置配置信息
    static public function set_config($name,$value,$group = false,$intro = false)
    {
        $group = $group ?: '';
        $intro = $intro ?: '';

        //是否有已有配置
        $config_existes = self::get_config($name,$group);
        //存在
        if( $config_existes )
        {
            //修改存在的
            //条件
            $where = [
                'name' => $name,
                'group' => $group,
            ];

            //修改数据
            $update = [
                'value' => $value,
                'intro' => $intro,
                'settime' => NOWTIME, //修改时间
            ];

            $result = Db::name("config")->where($where)->update($update);

        }else{
            //新增一条配置
            $insert = [
                'name' => $name,
                'group' => $group,
                'value' => $value,
                'intro' => $intro,
                'addtime' => NOWTIME,
                'settime' => NOWTIME,
            ];

            $result = Db::name('config')->insert($insert);
        }

        //清楚缓存文件
        self::rmCache();

        return $result ? true : false;
    }

    //修改配置
    static public function alter_config($id, array $data)
    {
        //是否有这条记录
        $is_exists = Db::name("config")->where("id={$id}")->find();
        if( !$is_exists ) return "无效ID";

        //修改数据
        $update = [
            'settime' => NOWTIME
        ];
        isset($data['name']) && $update['name'] = $data['name'];
        isset($data['value']) && $update['value'] = $data['value'];
        isset($data['group']) && $update['group'] = $data['group'];
        isset($data['intro']) && $update['intro'] = $data['intro'];
        
        $result = Db::name("config")->where("id={$id}")->update($update);

        //清楚缓存文件
        self::rmCache();

        return $result ?: false;
    }

    //获取配置
    static private function get_config($name,$group = '')
    {
        //条件
        $where = [
            'name' => $name,
            'group' => $group,
        ];

        $result = Db::name('config')->where($where)->find();

        return $result ?: false;
    }

    //删除配置
    static public function del_config($name,$group = '')
    {
        //条件
        $where = [
            'name' => $name,
            'group' => $group,
        ];

        $result = Db::name('config')->where($where)->delete();

        //清楚缓存文件
        self::rmCache();

        return $result ?: false;
    }

}

