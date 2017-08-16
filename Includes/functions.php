<?php

use Includes\App;
use Includes\Config;
use Includes\Classes\Db\MysqliDb;
use Includes\Classes\Db\PdoDb;

/**
 * 获取配置
 *
 * @param string $name
 *
 * @return mixed
 */
function config($name)
{
    static $config;
    if ($config === null) {
        $config = require ROOT_PATH.'config/config.php';
    }
    return isset($config[$name])? $config[$name]:'';
}

/**
 * 数据库操作类实例
 *
 * @param string $name 
 *
 * @return Db
 */
function db($name = false)
{
    static $settings, $db;
    if (!$settings) {
        $settings = config('db');
        $settings['debug'] = config('debug');
    }
    if (!$db) {
        switch ($settings['api']) {
            case 'mysqli':
                $db = new MysqliDb($settings);
                break;
            case 'PDO':
            default:
                $db = new PdoDb($settings);
                break;
        }
    }
    if ($name) {
        $db->table($name);
    }
    return $db;
}

/**
 * 获取变量值，不存在则返回默认值
 *
 * @param mixed $var
 * @param mixed $default
 *
 * @return mixed
 */
function get(&$var, $default)
{
    return isset($var) ? $var : $default;
}

/**
 * 获取当前选择语言
 *
 * @return string
 */
function lang()
{
    static $lang;
    if ($lang === null) {
        if (isset($_REQUEST['lang'])) {
            $lang = $_REQUEST['lang'];
        } else {
            $lang = isset(Config::$data['lang']) ? Config::$data['lang'] : 'cmn-Hans';
        }
    }
    return $lang;
}

/**
 * 获取语言变量值
 *
 * @param string  $name
 * @param boolean $getCommon
 * 
 * @return mixed
 */
function lang_var($name, $getCommon = false)
{
    $lang = lang();
    $langPath = module_path('lang/'.$lang.'/');
    $langFile = $langPath.$name.'.php';
    if (is_file($langFile)) {
        $langVar = require($langFile);
    } else {
        trigger_error("Can't find language package '$name'!", E_USER_ERROR);
    }
    if ($getCommon) {
        $commonLangFile = $langPath.'common.php';
        if (is_file($commonLangFile)) {
            $langVar += require $commonLangFile; 
        } else {
            trigger_error("Can't find language package 'common'!", E_USER_ERROR);
        }
    }
    return $langVar;
}

/**
 * 获取当前模块路径
 *
 * @param string $path
 *
 * @return string
 */
function module_path($path = '')
{
    static $module_path;
    if ($module_path === null) {
        $module_path = ROOT_PATH.'App/'.App::$dispatch['module'].'/';
    }
    return $module_path.$path;
}

/**
 * 重定向
 *
 * @param string $url
 *
 * @return void
 */
function redirect($url)
{
    header('Location: '.$url);
    exit();
}

/**
 * 显示视图
 *
 * @param string $filename
 * @param array  $var
 * @param boolean $direct_output
 * 
 * @return void
 */
function view($filename, $var, $direct_output=false)
{
    $template = new \Includes\Classes\Template;
    $template->tplDir = module_path('views/');
    $template->compileDir = ROOT_PATH.'temp/compile/';
    if ($direct_output) {
        $template->directOutput = true;
    }
    $template->display($filename, $var);
}