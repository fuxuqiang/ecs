<?php

namespace app;
use \includes\classes\Cache;

abstract class Common
{
	public function __construct()
	{
		$cache = new Cache(ROOT_PATH.config('cache_path'));
		\includes\Config::load($cache);
	}
}