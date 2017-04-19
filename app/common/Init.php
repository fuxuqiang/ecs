<?php

namespace app\common;
use \includes\classes\Cache;

abstract class Init
{
	public function __construct()
	{
		$cache = new Cache(ROOT_PATH.config('cache_path'));
		\includes\Config::load($cache);
	}
}