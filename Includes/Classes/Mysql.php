<?php

namespace Includes\Classes;

/**
 * 数据库操作类
 */
final class Mysql
{
    /**
     * @var float $quryTime 查询开始时间
     * @var int   $queryCount 查询次数
     */
    public $queryTime = 0, $queryCount = 0;

    /**
     * @var static $instance 当前类的对象
     */
    private static $instance;

    /**
     * @var array  $setting 数据库连接配置
     * @var PDO    $linkID PDO实例
     */
    private $settings, $linkID;

    /**
     * 获取当前类的对象
     *
     * @param array
     *
     * @return static
     */
    public static function getInstance(array $settings)
    {
        if (static::$instance === null) {
            static::$instance = new static($settings);
        }
        return static::$instance;
    }

    /**
     * 构造函数
     *
     * @param array
     *
     * @return void
     */
    private function __construct($settings)
    {
        $this->settings = $settings;
        $this->settings['prefix'] = $settings['prefix'];
        $this->settings['charset'] = str_replace('-', '', $settings['charset']);        
    }

    /**
     * 连接数据库
     *
     * @return void
     */
    private function connect()
    {
        // 连接数据库
        $this->linkID = new \PDO(
            'mysql:host='.$this->settings['host'].';dbname='.$this->settings['name'].';charset='.$this->settings['charset'], 
            $this->settings['user'], 
            $this->settings['pass']
        );
        // 设置sql_mode
        if (!$this->settings['debug']) {
            $this->linkID->query("SET sql_mode=''");
        }
    }

    /**
     * 设置表名
     *
     * @param string
     *
     * @return void
     */
    public function table($name)
    {
        $this->table = $this->prefix.$name;
    }

    /**
     * 执行查询
     *
     * @param string $sql
     * @param array  $data
     *
     * @return PDOStatement
     */
    public function query($sql, array $data=[])
    {
        if ($this->linkID === null) {
            $this->connect();
        }
        // 记录查询开始时间
        $this->queryTime = microtime(true);
        // 执行查询
        $sth = $this->linkID->prepare($sql);
        $sth->execute($data);
        // 查询次数
        $this->queryCount++;
        
        return $sth;
    }

    /**
     * 插入数据
     *
     * @param array $data
     *
     * @return PDOStatement
     */
    public function insert(array $data)
    {
        $sql = 'INSERT `'.$this->table.'` SET ';
        foreach ($data as $field => $value) {
            $sql .= '`'.$field.'`=?,';
        }
        $sql = rtrim($sql, ',');
        return $this->query($sql, array_values($data));
    }

    /**
     * 查询全部数据
     *
     * @return array
     */
    public function fetchAll()
    {
        $sql = 'SELECT * FROM `'.$this->table.'`';
        return $this->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }

    // prevent a secend instance of it
    private function __clone() {}
    private function __wakeup() {}
}