<?php

namespace App\Install;
use Includes\App;

class Install
{
	public function __construct()
	{
		if (is_file(ROOT_PATH.'config/install.lock') && App::dispatch()['action'] != 'done') {
			view('error', ['lang' => lang_var('error')], true);
		}
	}

	// 首页
	public function index()
	{
		view('welcome', ['lang' => lang_var('welcome', true)], true);
	}

	// 安装环境检测页
	public function checking()
	{
		view('checking', ['lang' => lang_var('check', true)], true);
	}

	// 输出检测结果
	public function checked()
	{
		$disabled = '';
		// 获取语言变量
		$lang = lang_var('check', true);
		// 获取GD的版本号
		$gd_ver = gd_info()['GD Version'] ?: $lang['not_support'];
		// 检查目录权限
		$dirs = ['temp/compile', 'config'];
		foreach ($dirs as $key => $dir) {
			$dirCheck[$key]['dir'] = $dir;
			$path = ROOT_PATH.$dir;
			// 可写
			if (is_writable($path)) {
				$dirCheck[$key]['rst'] = $lang['can_write'];
			// 不可写
			} elseif (is_dir($path)) {
				$dirCheck[$key]['rst'] = $lang['cannt_write'];
				$disabled = 'disabled';
			// 不存在
			} else {
				$dirCheck[$key]['rst'] = $lang['not_exists'];
				$disabled = 'disabled';
			}
		}
		// 显示视图
		view('checked', compact('gd_ver', 'dirCheck', 'lang', 'disabled'), true);
	}

	// 配置页
	public function set()
	{
		view('set', ['lang' => lang_var('set', true)]);
	}

	// 查询数据库列表
	public function getDbList($host, $port, $user, $pass)
	{
		$db = @new \mysqli($host, $user, $pass, null, $port);
		if ($db->connect_errno) {
			exit(json_encode(['status'=>false, 'content'=>lang_var('common')['query_failed']]));
		} else {
			$rst = $db->query('SHOW DATABASES');
			while ($row = $rst->fetch_row()) {
				$databases[] = $row[0];
			}
			exit(json_encode(['status'=>true, 'content'=>$databases]));
		}
	}

	// 创建配置文件
	public function createConfFile($host, $port, $user, $pass, $name, $prefix, $timezone)
	{
		$content = file_get_contents(ROOT_PATH.'config/config.php');
		$pairs = ['[host]'=>$host, '[port]'=>$port, '[user]'=>$user, '[pass]'=>$pass, '[name]'=>$name, '[prefix]'=>$prefix, '[timezone]'=>$timezone];
		$content = strtr($content, $pairs);
		if (@file_put_contents(ROOT_PATH.'config/config.php', $content)) {
			exit('ok');
		} else {
			exit(lang_var('common')['write_config_file_failed']);
		}
	}

	// 创建数据库
	public function createDB()
	{
		$lang = lang_var('common');
		$settings = config('db');
		$db = @new \mysqli($settings['host'], $settings['user'], $settings['pass'], null, $port);
		if ($db->connect_errno) {
			exit($lang['connect_failed']);
		} else {
			$db->set_charset('utf8');
		}
		if (!$db->query('CREATE DATABASE IF NOT EXISTS `'.$settings['name'].'` CHARACTER SET utf8')) {
			exit($lang['cannt_create_database']);
		}
		exit('ok');
	}

	// 安装数据
	public function installBaseData()
	{
		// 获取数据库配置
		$settings = config('db');
		// 读取SQL
		$sql = file_get_contents(module_path('data/structure.sql'));
		$sql = strtr($sql, ["\r"=>'', '`ecs_'=>"`{$settings['prefix']}"]);
		$items = explode(";\n", $sql);
		// 连接数据库
		$db = new \mysqli($settings['host'], $settings['user'], $settings['pass'], $settings['name'], $settings['port']);
		$db->set_charset('utf8');
		// 执行SQL
		foreach ($items as $item) {
			if (!$db->query($item)) {
				exit($db->error);
			}
		}
		// 响应
		exit('ok');
	}

	// 创建管理员账户
	public function createAdminPassport($name, $pass, $email)
	{
		if ($pass === '') {
			exit(lang_var('common')['password_empty_error']);
		}
		$rst = db('admin_user')->insert([
			'user_name' => $name,
			'email' => $email,
			'password' => md5($pass),
			'add_time' => time()
		]);
		if ($rst) {
			exit('ok');
		}
	}

	// 处理其它
	public function doOthers($lang)
	{
		// 写入语言配置
		$rst = db('shop_config')->insert([
			'code' => 'lang',
			'value' => $lang
		]);
		// 写入安装锁定文件
		$handle = fopen(ROOT_PATH.'config/install.lock', 'w');
		// 响应
		if ($rst && $handle) {
			exit('ok');
		}
	}

	// 安装完成
	public function done()
	{
		if (is_file(ROOT_PATH.'config/install.lock')) {
			view('done', ['lang'=>lang_var('done')]);
		} else {
			redirect('/install/index');
		}
	}
}