<?php

namespace app\install;

class Install
{
	// 首页
	public function index()
	{
		if (is_file(ROOT_PATH.'data/install.lock')) {
			view('error', ['lang' => lang_var('error')], true);
		} else {
			view('welcome', ['lang' => lang_var('welcome', true)], true);
		}
	}

	// 安装环境检测页
	public function checking()
	{
		view('checking', ['lang' => lang_var('check', true)], true);
	}

	// 输出检测结果
	public function checked()
	{
		$lang = lang_var('check', true);
		$disabled = '';
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
				$disabled = 'disabled="true"';
			// 不存在
			} else {
				$dirCheck[$key]['rst'] = $lang['not_exists'];
				$disabled = 'disabled="true"';
			}
		}
		$var['dirCheck'] = $dirCheck;
		$var['lang'] = $lang;
		$var['disabled'] = $disabled;
		view('checked', $var, true);
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
			$dirCheck = $db->query('SHOW DATABASES');
			while ($row = $dirCheck->fetch_row()) {
				$databases[] = $row[0];
			}
			exit(json_encode(['status'=>true, 'content'=>$databases]));
		}
	}

	// 创建配置文件
	public function createConfFile($host, $port, $user, $pass, $name, $prefix, $timezone)
	{
		$content = file_get_contents(module_path('data/config.tpl'));
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
		$queryItems = explode(";\n", $sql);
		// 连接数据库
		$db = new \mysqli($settings['host'], $settings['user'], $settings['pass'], $settings['name'], $settings['port']);
		$db->set_charset('utf8');
		// 执行SQL
		foreach ($queryItems as $value) {
			if (!$db->query($value)) {
				exit($db->error);
			}
		}
		// 响应
		exit('ok');
	}

	// 
	public function createAdminPassport($name, $pass, $email)
	{
		if ($pass === '') {
			exit(lang_var('common')['password_empty_error']);
		}
		$db = db();
		// $db->query('INSERT __admin_user__ (`user_name`,`email`,`password`,`add_time`,`action_list`,`nav_list`) VALUES (?,?,?,?,?,?)', [$name, $email]);
	}
}