<?php

// 要求PHP版本5.4以上
if(version_compare(PHP_VERSION, '5.4') < 0)
	die('require PHP >= 5.4 !');

// 定义当前根目录
define('ROOT_PATH', strtr(dirname(__DIR__), '\\', '/').'/');

// 自动载入类文件
function __autoload($class) 
{
	if (strstr($class, '\\')) {
		$class = strtr($class, '\\', '/');
	}
	require ROOT_PATH.$class.'.php';
}

// 启动应用
\Includes\App::start();