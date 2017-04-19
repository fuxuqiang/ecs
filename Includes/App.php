<?php

namespace Includes;

class App
{
	public static function start()
	{
		// 解析URL
		$dispatch = self::dispatch();
		// 包含当前模块函数库文件
		@include ROOT_PATH.'App/'.$dispatch['module'].'/functions.php';
		// 实例化控制器
		$class = '\\App\\'.$dispatch['module'].'\\'.$dispatch['class'];
		$controller = new $class;
		// 绑定参数并当前操作方法
		$method = new \ReflectionMethod($controller, $dispatch['action']);
		if ($method->getNumberOfParameters()) {
			$params = $method->getParameters();
			$args = [];
			foreach ($params as $param) {
				$name = $param->getName();
				isset($_REQUEST[$name]) && $args[] = trim($_REQUEST[$name]);
			}
			$method->invokeArgs($controller, $args);
		} else {
			$method->invoke($controller);
		}
	}

	public static function dispatch()
	{
		static $dispatch;
		if ($dispatch === null) {
			if (isset($_SERVER['PATH_INFO']) && $_SERVER['PATH_INFO']!='/') {
			    $paths = explode('/', trim($_SERVER['PATH_INFO'], '/'));
			}
			$module = ucfirst(isset($paths[0])? $paths[0] : 'index');
			// 是否存在与模块同名的类
			if (is_file(ROOT_PATH.'App/'.$module.'/'.$module.'.php')) {
				$class = $module;
				$action = isset($paths[1])? $paths[1] : 'index';
			} else {
				$class = ucfirst(isset($paths[1])? $paths[1] : 'index');
				$action = isset($paths[2])? $paths[2] : 'index';
			}
			$dispatch = ['module'=>$module, 'class'=>$class, 'action'=>$action];
		}
		return $dispatch;
	}
}