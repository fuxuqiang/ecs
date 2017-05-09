<?php

namespace Includes;

class App
{
	public static $dispatch = [];

	public static function start()
	{
		// 解析URL
		if (isset($_SERVER['PATH_INFO']) && $_SERVER['PATH_INFO']!='/') {
			$pathInfo = trim($_SERVER['PATH_INFO'], '/');
			// 获取路由配置
			if (config('route')) {
				foreach (config('route') as $key => $value) {
					if (strpos($pathInfo, $key) === 0) {
						$pathInfo = str_replace($key, $value, $pathInfo);
					}
				}
			}
		    $paths = explode('/', $pathInfo);
		}
		$dispatch = &self::$dispatch;
		$dispatch['module'] = ucfirst(get($paths[0], 'index'));
		$dispatch['controller'] = ucfirst(get($paths[1], 'index'));
		$dispatch['action'] = get($paths[2], 'index');
		// 包含当前模块函数库文件
		@include ROOT_PATH.'App/'.$dispatch['module'].'/functions.php';
		// 实例化控制器
		$class = '\\App\\'.$dispatch['module'].'\\'.$dispatch['controller'];
		$controller = new $class;
		// 绑定参数并执行当前操作方法
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
}