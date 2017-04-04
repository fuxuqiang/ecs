<?php

namespace app\admin;

class Login
{
	public function index()
	{
		view('login', ['lang'=>lang_var('login')]);
	}
}