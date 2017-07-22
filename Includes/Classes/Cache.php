<?php

namespace Includes\Classes;

class Cache
{
	private $cachePath;

	public function __construct($cachePath)
	{
		$this->cachePath = rtrim($cachePath, '/').'/';
	}

	/**
	 * 写结果缓存文件
	 *
	 * @param string $cacheName
	 * @param string $caches
	 *
	 * @return void
	 */
	public function write($cacheName, $caches)
	{
	    $cacheFile = $this->cachePath . $cacheName.'.php';
	    $content = "<?php\r\n";
	    $content .= "return ".var_export($caches, true).';';
	    file_put_contents($cacheFile, $content, LOCK_EX);
	}

	/**
	 * 读结果缓存文件
	 *
	 * @param string $cacheName
	 *
	 * @return array
	 */
	public function read($cacheName)
	{
		static $result = [];
	    if (!empty($result[$cacheName])) {
	        return $result[$cacheName];
	    }
	    $cacheFile = $this->cachePath . $cacheName.'.php';
	    if (file_exists($cacheFile)) {
	        $result[$cacheName] = require $cacheFile;
	        return $result[$cacheName];
	    } else {
	        return false;
	    }
	}
}