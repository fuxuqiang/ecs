<?php

namespace includes\classes;

/**
 * 数据库操作类
 */
final class Mysql
{
    private static $instance;

    private $prefix;

    private $table;

    private $linkID;

    public static function getInstance(array $settings)
    {
        if (self::$instance === null) {
            self::$instance = new self($settings);
        }
        return self::$instance;
    }

    private function __construct($settings)
    {
        // 处理配置参数
        $this->prefix = $settings['prefix'];
        $settings['charset'] = str_replace('-', '', $settings['charset']);
        // 连接数据库
        $this->linkID = new \PDO('mysql:host='.$settings['host'].';dbname='.$settings['name'].';charset='.$settings['charset'], $settings['user'], $settings['pass']);
        // 设置sql_mode
        if (!$settings['debug']) {
            $this->linkID->query("SET sql_mode=''");
        }
    }

    public function table($name)
    {
        $this->table = $this->prefix.$name;
    }

    public function query($sql, array $data=[])
    {
        $sth = $this->linkID->prepare($sql);
        $sth->execute($data);
        return $sth;
    }

    public function insert(array $data)
    {
        $sql = 'INSERT `'.$this->table.'` SET ';
        foreach ($data as $field => $value) {
            $sql .= '`'.$field.'`=?,';
        }
        $sql = rtrim($sql, ',');
        return $this->query($sql, array_values($data));
    }

    public function fetchAll()
    {
        $sql = 'SELECT * FROM `'.$this->table.'`';
        return $this->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }

    private function __clone() {}

    private function __wakeup() {}
}