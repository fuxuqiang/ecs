<?php

function config($name)
{
	static $config;
	if ($config === null) {
		$config = require ROOT_PATH.'data/config.php';
	}
	return isset($config[$name])? $config[$name]:'';
}

function local_date($format)
{
    $time = isset(Config::$data['zone'])?:0;
    return gmdate($format, time() + $time*3600);
}

/**
 * 判断是否为搜索引擎蜘蛛
 *
 * @param boolean
 * @return string
 */
function is_spider($record = true)
{
    static $spider;
    if ($spider === null) {
        $spider = '';
        if (!empty($_SERVER['HTTP_USER_AGENT'])) {
            $searchengine = [
                'googlebot' => 'GOOGLE',
                'mediapartners-google' => 'GOOGLE ADSENSE',
                'baiduspider+' => 'BAIDU',
                'msnbot' => 'MSN',
                'yodaobot' => 'YODAO',
                'yahoo! slurp;' => 'YAHOO',
                'yahoo! slurp china;' => 'Yahoo China',
                'iaskspider' => 'IASK',
                'sogou web spider' => 'SOGOU',
                'sogou push spider' => 'SOGOU',
                'bingbot' => 'BING',
                'qihoobot' => '360'
            ];
            $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
            foreach ($searchengine AS $key => $value) {
                if (strpos($agent, $key) !== false) {
                    $spider = $value;
                    if ($record === true) {
                        db()->query("INSERT __searchengine__ (`date`,`searchengine`,`count`) VALUES ('".local_date('Y-m-d')."','".$spider."',1) ON DUPLICATE SET `count`=`count`+1");
                    }
                }
            }
        }
    }
    return $spider;
}