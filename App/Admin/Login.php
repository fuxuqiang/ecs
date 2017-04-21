<?php

namespace App\Admin;

use App\Common\Init;
use Includes\Classes\Captcha\Captcha;

class Login extends Init
{
	public function index()
	{
		$lang = lang_var('login');

		if (empty($_POST)) {
			// 显示登录页面
			view('login', ['lang'=>$lang, 'gd_ver'=>gd_info()['GD Version']]);
		} else {
			// 检查验证码
			if (!isset($_POST['no-captcha'])) {
				if (!isset($_POST['captcha'])) {
					exit('Hacking attempt');
				} elseif (!Captcha::check($_POST['captcha'])) {
					sys_msg($lang['captcha_error'], 1);
				}
			}
		}
	}
}