<?php

namespace app\common;
use includes\classes\captcha\Captcha;

class Verify
{
	public function Index()
	{
		Captcha::generate(104, 36);
	}
}