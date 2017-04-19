<?php

namespace App\Common;
use Includes\Classes\Captcha\Captcha;

class Verify
{
	public function Index()
	{
		Captcha::generate(104, 36);
	}
}