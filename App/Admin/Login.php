<?php

namespace App\Admin;

use App\Common\Init;
use Includes\Classes\Captcha\Captcha;

class Login extends Init
{
	public function index()
	{
		// 获取语言变量值
		$lang = lang_var('login');
		// 是否有登录操作
		if (empty($_POST)) {
			// 显示登录页面
			view('login', ['lang' => $lang, 'gd_ver' => gd_info()['GD Version']]);
		} else {
			// 检查验证码
			if (!isset($_POST['no-captcha'])) {
				if (!isset($_POST['captcha'])) {
					exit('Hacking attempt');
				} elseif (!Captcha::check($_POST['captcha'])) {
					sys_msg($lang['captcha_error'], 1);
				}
			}
			// 验证登录信息
			if (md5($_POST['password']) == db('admin_user')->where(['user_name' => $_POST['username']])->select('password')) {
				die('success');
			} else {
				sys_msg($lang['login_failed'], 1);
			}
		}
	}
}