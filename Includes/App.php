<?php

namespace Includes;

class App
{
	/**
	 * @var array $dispatch URL映射结果
	 */
	public static $dispatch = [];

	/**
	 * 启动应用
	 */
	public static function start()
	{
		// 载入函数库文件
		require ROOT_PATH.'Includes/functions.php';
		// 是否开启调试模式
		if (!config('debug')) {
			error_reporting(0);
		}
		// 解析URL
		if (isset($_SERVER['PATH_INFO']) && $_SERVER['PATH_INFO']!='/') {
			$pathInfo = trim($_SERVER['PATH_INFO'], '/');
			$dispatch = &self::$dispatch;
			// 获取路由配置
			if (config('route')) {
				foreach (config('route') as $key => $value) {
					if (strpos($pathInfo, $key) === 0) {
						if (is_array($value)) {
							// REST路由
							$value = $value[0];
							switch ($_SERVER['REQUEST_METHOD']) {
								case 'GET':
									$dispatch['action'] = 'show';
									break;
								case 'POST':
									if (isset($_POST['_method'])) {
										switch ($_POST['_method']) {
											case 'PUT':
												$dispatch['action'] = 'update';
												break;
											case 'DELETE':
												$dispatch['action'] = 'delete';
												break;
										}
									} else {
										$dispatch['action'] = 'add';
									}
									break;
							}
						}
						// 根据路由替换PATH_INFO
						$pathInfo = preg_replace('/'.$key.'/', $value, $pathInfo, 1);						
					}
				}
			}
			// 拆分PATH_INFO
		    $paths = explode('/', $pathInfo);
		}
		// 生成映射结果
		$dispatch['module'] = ucfirst(get($paths[0], 'index'));
		$dispatch['controller'] = ucfirst(get($paths[1], 'index'));
		$dispatch['action'] = get($dispatch['action'], get($paths[2], 'index'));
		// 包含当前模块函数库文件
		@include ROOT_PATH.'App/'.$dispatch['module'].'/functions.php';
		// 实例化控制器
		$class = '\\App\\'.$dispatch['module'].'\\'.$dispatch['controller'];
		$controller = new $class;
		// 执行当前操作方法
		$method = new \ReflectionMethod($controller, $dispatch['action']);
		if ($method->getNumberOfParameters()) {
			// 带参数执行
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