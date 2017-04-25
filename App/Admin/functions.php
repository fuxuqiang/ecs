<?php

function sys_msg($msg, $type)
{
	// 获取语言变量值
	$lang = lang_var('login', true);
	// 计算查询时间
	if (db()->queryTime == 0) {
		$queryTime = 0;
	} else {
		$queryTime = number_format(microtime(true) - db()->queryTime, 6);
	}
	// 显示视图
	view('message', [
		'lang' => $lang,
		'msg' => $msg,
		'type' => $type,
		'query_info' => sprintf($lang['query_info'], db()->queryCount, $queryTime),
		'memory_info' => sprintf($lang['memory_info'], memory_get_usage()/1048576)
	]);
}