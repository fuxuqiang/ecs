<?php

namespace app\admin;
use app\common\Init;

class Index extends Init
{
	public function __construct()
	{
		if (!isset($_SESSION['admin_id']) && !isset($_COOKIE['admin_id'])) {
			redirect('/admin/login');
		}
	}

	public function index()
	{
		
	}
}