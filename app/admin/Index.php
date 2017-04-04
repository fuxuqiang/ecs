<?php

namespace app\admin;
use app\Common;

class Index extends Common
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