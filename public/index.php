<?php

// 要求PHP版本5.4以上
if(version_compare(PHP_VERSION, '5.4') < 0)
	die('require PHP >= 5.4 !');

// 取得当前ecs所在的根目录
define('ROOT_PATH', strtr(dirname(__DIR__), '\\', '/').'/');

// 载入类及函数库文件
function __autoload($class) 
{
	if (strstr($class, '\\')) {
		$class = strtr($class, '\\', '/');
	}
	require ROOT_PATH.$class.'.php';
}
require ROOT_PATH.'includes/functions.php';

// 启动应用
\includes\App::start();