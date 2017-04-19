<?php

namespace App\Admin;

use App\Common\Init;
use Includes\Classes\Captcha\Captcha;

class Login extends Init
{
	public function index()
	{
		if (empty($_POST)) {
			view('login', ['lang'=>lang_var('login'), 'gd_ver'=>gd_info()['GD Version']]);
		} else {
			if (!Captcha::check($_POST['captcha'])) {
				
			}
		}
	}
}