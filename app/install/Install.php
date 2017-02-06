<?php

namespace app\install;

class Install
{
	public function index()
	{
		if (is_file(ROOT_PATH.'data/install.lock')) {
			view('error', ['lang' => lang_var('error', false)], true);
		} else {
			view('welcome', ['lang' => lang_var('welcome')], true);
		}
	}

	public function checking()
	{
		view('checking', ['lang' => lang_var('check')], true);
	}

	public function checked()
	{
		view('checked', ['lang' => lang_var('check')], true);
	}

	public function set()
	{
		view('set', ['lang' => lang_var('set')]);
	}

	public function getDbList($host, $port, $user, $pass)
	{
		$db = @new \mysqli($host, $user, $pass, null, $port);
		if ($db->connect_errno) {
			echo json_encode(['status'=>false, 'content'=>lang_var('common')['query_failed']]);
		} else {
			$rst = $db->query('SHOW DATABASES');
			while ($row = $rst->fetch_row()) {
				$databases[] = $row[0];
			}
			echo json_encode(['status'=>true, 'content'=>$databases]);
		}
	}
}