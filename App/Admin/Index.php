<?php

namespace App\Admin;
use App\Common\Init;

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