<?php

namespace App\Common;
use Includes\Classes\Cache;

abstract class Init
{
	public function __construct()
	{
		if (!is_file(ROOT_PATH.'config/install.lock')) {
			redirect('/install');
		}
		$cache = new Cache(ROOT_PATH.config('cache')['path']);
		\Includes\Config::load($cache);
	}
}