<?php

// 要求PHP版本5.4以上
if(version_compare(PHP_VERSION, '5.4') < 0)
	die('require PHP >= 5.4 !');

// 定义开始时间及项目根目录
define('START', microtime(true));
define('ROOT_PATH', strtr(dirname(__DIR__), '\\', '/').'/');

// 自动载入类文件
spl_autoload_register(function($class) {
	if (strstr($class, '\\')) {
		$class = strtr($class, '\\', '/');
	}
	require ROOT_PATH.$class.'.php';
});

// 启动应用
\Includes\App::start();