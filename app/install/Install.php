<?php

namespace app\install;

class Install
{
	// 首页
	public function index()
	{
		if (is_file(ROOT_PATH.'data/install.lock')) {
			view('error', ['lang' => lang_var('error', false)], true);
		} else {
			view('welcome', ['lang' => lang_var('welcome')], true);
		}
	}

	// 安装环境检测页
	public function checking()
	{
		view('checking', ['lang' => lang_var('check')], true);
	}

	// 输出检测结果
	public function checked()
	{
		$lang = lang_var('check');
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
		view('set', ['lang' => lang_var('set')]);
	}

	// 查询数据库列表
	public function getDbList($host, $port, $user, $pass)
	{
		$db = @new \mysqli($host, $user, $pass, null, $port);
		if ($db->connect_errno) {
			echo json_encode(['status'=>false, 'content'=>lang_var('common')['query_failed']]);
		} else {
			$dirCheck = $db->query('SHOW DATABASES');
			while ($row = $dirCheck->fetch_row()) {
				$databases[] = $row[0];
			}
			echo json_encode(['status'=>true, 'content'=>$databases]);
		}
	}

	// 创建配置文件
	public function createConfFile($host, $port, $user, $pass, $name, $prefix, $timezone)
	{
		$content = file_get_contents(ROOT_PATH.'config/config.tpl');
		$pairs = ['[host]'=>$host, '[port]'=>$port, '[user]'=>$user, '[pass]'=>$pass, '[name]'=>$name, '[prefix]'=>$prefix, '[timezone]'=>$timezone];
		$content = strtr($content, $pairs);
		if (file_put_contents(ROOT_PATH.'config/config.php', $content)) {
			exit('ok');
		} else {
			exit(lang_var(''));
		}
	}
}