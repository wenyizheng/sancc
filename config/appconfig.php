<?php

/*
应用配置文件
*/

return[
	//模块名称
	'app'=>'test',

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

	
];