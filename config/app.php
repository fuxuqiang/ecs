<?php
// 配置文件
return [

	'debug_mode' => 1,

	// 数据库配置
	'db' => [
		'host'	 => 'localhost',
		'port'   => '3306',
		'name'   => 'ecs',
		'user'   => 'root',
		'pass'   => 'eb',
		'prefix' => ''
	],

	'charset' => 'utf-8',

	// 缓存配置
	'cache_on'   => false,
	'cache_path' => 'temp/static_caches/'

];
