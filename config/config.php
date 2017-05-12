<?php
// 配置文件
return [

	'debug' => true,

	// 数据库配置
	'db' => [
		'host'	 => 'localhost',
		'port'   => '3306',
		'name'   => 'ecs',
		'user'   => 'root',
		'pass'   => 'eb',
		'prefix' => ''
	],

	'charset'  => 'utf-8',

	'timezone' => 'Asia/Chongqing',

	// 缓存配置
	'cache' => [
		'on'   => true,
		'path' => 'temp/static_caches'
	],

	// 路由配置
	'route' => [
		'install' => 'Install/Install',
	]
];