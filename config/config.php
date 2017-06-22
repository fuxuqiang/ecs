<?php
// 配置文件
return [

	'debug' => true,

	// 数据库配置
	'db' => [
		'api'	 => 'PDO',
		'host'	 => '[host]',
		'port'   => '[port]',
		'name'   => '[name]',
		'user'   => '[user]',
		'pass'   => '[pass]',
		'prefix' => '[prefix]'
	],

	'charset'  => 'utf-8',

	'timezone' => '[timezone]',

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