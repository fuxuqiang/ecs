<?php

namespace includes;
use classes\Cache;

class Config
{
	public static $data;

	public static function load(Cache $cache)
	{
		if (config('cache_on')) {
			$data = $cache->read('shop_config');
			if ($data === false) {
				self::$data = self::read();
				$cache->write('shop_config', self::$data);
			} else {
				self::$data = $data;
			}
		} else {
			self::$data = self::read();
		}
	}

	private static function read()
	{
		$data = [];
		$res = db()->getAll('SELECT `code`,`value` FROM __shop_config__');
		foreach ($res as $row) {
			$data[$row['code']] = $row['value'];
		}
		return $data;
	}

	public static function get($name)
	{
		return isset(self::$data[$name])? self::$data[$name]:'';
	}
}