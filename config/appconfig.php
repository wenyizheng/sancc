<?php

/*
应用配置文件
*/

return[
	//模块名称
	'app'=>'',

	//GET参数匹配模式
	//默认 默认参数获取方式 1或不填
	//2    使用 / 进行分割  2
	'get_param_pattern'=>'2',


	/*
		视图模板
	*/
	//后缀名
	'suffix'=>'.html',
	//缓存时间(秒) 
	'cache_time'=>'',
	//模板变量
	'replace_var'=>[
		'name'=>'wenyizheng',
	],

    //空操作名称
    'empty_operate'=>'error',
    //空控制器名称
    'empty_controller'=>'Error',

	/*
		数据库配置
	*/

	//数据库类型
	'dbtype'=>'mysql',

	//数据库地址
	'host'=>'127.0.0.1',

	//数据库名称                                                                                  
	'dbname'=>'',

	//数据库用户名
	'user'=>'root',

	//数据库密码
	'password'=>'',

	//端口
	'port'=>'3306',

	//字符集
	'charset'=>'UTF8',


    /*
     * 路由注册
     * */
    'routefile'=>'route',

    /*
     * Cookie设置
     * */
    'cookie'=>[
        //cookie名称前缀
        'prefix'=>'',
        //保存时间
        'expire'=>'',
        //保存路径
        'path'=>'',
        //有效域名
        'domain'=>'',
        //安全传输
        'secure'=>false,

    ],

    /*
     * Session设置
     * */
    'session'=>[
        //自动初始化
        'auto'=>true,
        //驱动设置 默认file 支持redis
        'driver'=>'redis',
        //保存路径
        'path'=>'',
        //前缀
        'prefix'=>'sancc',
        //过期时间(秒)
        'timeout'=>1800,

        //redis设置
        'redis'=>[
            //地址
            'host'=>'127.0.0.1',
            //端口
            'port'=>6379,
        ],
    ],
];