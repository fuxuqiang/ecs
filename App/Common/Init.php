<?php

namespace App\Common;
use Includes\Classes\Cache;

abstract class Init
{
	public function __construct()
	{
		$cache = new Cache(ROOT_PATH.config('cache_path'));
		\Includes\Config::load($cache);
	}
}