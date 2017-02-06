<?php

namespace includes\classes;

class Cache
{
	private $_cachePath;

	public function __construct($cachePath)
	{
		$this->_cachePath = $cachePath;
	}

	/**
	 * 写结果缓存文件
	 *
	 * @param string $cacheName
	 * @param string $caches
	 *
	 * @return
	 */
	public function write($cacheName, $caches)
	{
	    $cacheFile = $this->_cachePath . $cacheName.'.php';
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
	    $cacheFile = $this->_cachePath . $cacheName.'.php';
	    if (file_exists($cacheFile)) {
	        $result[$cacheName] = require $cacheFile;
	        return $result[$cacheName];
	    } else {
	        return false;
	    }
	}
}