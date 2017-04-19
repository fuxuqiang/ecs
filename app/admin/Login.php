<?php

namespace app\admin;
use app\common\Init;

class Login extends Init
{
	public function index()
	{
		view('login', ['lang'=>lang_var('login'), 'gd_ver'=>gd_info()['GD Version']]);
	}
}