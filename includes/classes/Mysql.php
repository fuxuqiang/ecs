<?php

namespace includes\classes;

/**
 * 数据库操作类
 */
final class Mysql
{
    private static $instance;

    private $settings = [
        'name'    => null,   // 数据库名
        'prefix'  => '',     // 表前缀
        'port'    => '3306', // 端口号
        'quiet'   => false,  // 是否忽略错误
        'charset' => 'utf8'  // 字符编码
    ];

    private $linkID;

    private function __construct($settings)
    {
        $this->settings = $settings + $this->settings;
        $this->settings['charset'] = str_replace('-', '', $this->settings['charset']);
    }

    public static function getInstance(array $settings)
    {
        if (self::$instance === null) {
            self::$instance = new self($settings);
        }
        return self::$instance;
    }

    public function query($sql, array $bindings=[])
    {
        if ($this->linkID === null) {
            // 连接数据库
            $this->linkID = @new \mysqli($this->settings['host'], $this->settings['user'], $this->settings['pass'], $this->settings['name'], $this->settings['port']);
            // 处理错误信息
            if ($this->linkID->connect_error) {
                if ($this->settings['quiet']) {
                    return false;
                } else {
                    trigger_error($this->linkID->connect_error, E_USER_ERROR);
                }
            }
            // 设置字符编码
            $this->linkID->set_charset($this->settings['charset']);
            // 设置sql_mode
            if ($this->settings['quiet']) {
                $this->linkID->query("SET sql_mode=''");
            }
        }
        // 编译sql中的表名
        $sql = preg_replace('/__(\w+?)__/', '`'.$this->settings['prefix'].'$1`', $sql);
        // 执行查询
        if (!$stmt = $this->linkID->prepare($sql)) {
            trigger_error($this->linkID->error, E_USER_ERROR);
        }
        $stmt->execute();
        // 返回查询结果
        return $stmt->get_result();
    }

    public function getAll($sql, array $bindings=[])
    {
        $result = $this->query($sql, $bindings);
        if ($result === false) {
            return false;
        } else {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
    }

    private function __clone() {}

    private function __wakeup() {}
}