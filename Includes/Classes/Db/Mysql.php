<?php

namespace Includes\Classes;

/**
 * 数据库操作类
 */
final class Mysql
{
    /**
     * @var float $queryTime 查询开始时间
     * @var int   $queryCount 查询次数
     */
    public $queryTime = 0, $queryCount = 0;

    /**
     * @var static $instance 当前类的对象
     */
    private static $instance;

    /**
     * @var array $setting 数据库连接配置
     * @var array $where 查询条件数组
     * @var object $connector 数据库连接类实例
     */
    private $settings, $where = [], $connector;

    /**
     * @var array $sql SQL组成
     */
    private $sql = [
        'where' => ''
    ];

    /**
     * 获取当前类的对象
     *
     * @param array $settings
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
     * @param array $settings
     * @param object $connector
     *
     * @return void
     */
    private function __construct(array $settings, $connector)
    {
        $this->settings = $settings;
        $this->settings['prefix'] = $settings['prefix'];
        $this->settings['charset'] = str_replace('-', '', $settings['charset']);        

        $this->connector = $connector;
    }

    /**
     * 设置表名
     *
     * @param string $name
     *
     * @return void
     */
    public function table($name)
    {
        $this->table = '`'.$this->settings['prefix'].$name.'`';
    }

    /**
     * 设置WHERE条件
     *
     * @param mixed $where
     *
     * @return static
     */
    public function where($where)
    {
        if (is_array($where)) {
            foreach ($where as $col => $value) {
                $this->sql['where'] = ' WHERE `'.$col.'`=?';
            }
            $this->where = array_values($where);
        } else {
            $this->sql['where'] = $where;
        }
        return $this;
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
        // 连接数据库
        if (!$this->connector->connected) {
            if ($this->connector->connect()) {
                $this->connector->setDebug();
            } else {
                return false;
            }
        }
        // 记录查询开始时间
        $this->queryTime = microtime(true);
        // 执行查询
        $sth = $this->connector->query($sql, $data);
        // 查询次数
        $this->queryCount++;
        
        return $sth;
    }

    /**
     * 插入数据
     *
     * @param array $data
     * @param bool  $replace
     *
     * @return PDOStatement
     */
    public function insert(array $data, $replace=false)
    {
        $sql = ($replace ? 'REPLACE ' : 'INSERT ').$this->table.' SET ';
        foreach ($data as $col => $value) {
            $sql .= '`'.$col.'`=?,';
        }
        $sql = rtrim($sql, ',');
        return $this->query($sql, array_values($data));
    }

    /**
     * 查询指定列
     *
     * @param string $expr
     *
     * @return array
     */
    public function select($expr = false)
    {
        $sql = $this->selectSQL($expr);
        $result = $this->query($sql, $this->where)->fetchAll(\PDO::FETCH_ASSOC);
        $this->reset();
        return $result;
    }

    /**
     * 查询单行数据
     *
     * @param string $expr
     *
     * @return mixed
     */
    public function find($expr = false)
    {
        $sql = $this->selectSQL($expr).' LIMIT 1';
        $result = $this->query($sql, $this->where)->fetch(\PDO::FETCH_ASSOC);
        $this->reset();
        if (count($result) == 1 && $expr) {
            return $result[$expr];
        } else {
            return $result;
        }
    }

    /**
     * 删除
     *
     * @param array $where
     *
     * @return mixed
     */
    public function delete(array $where)
    {
        $this->where($where);
        $sql = 'DELETE '.$this->table.$this->sql['where'];
        return $this->query($sql, $this->where);
    }

    /**
     * 给列名加上``
     *
     * @param string $expr
     *
     * @return string
     */
    private function selectSQL($expr)
    {
        if (!$expr) {
            $expr = '*';
        } else {
            $expr = implode(',', array_map(function($v){
                return '`'.$v.'`';
            }, explode(',', $expr)));
        }
        return 'SELECT '.$expr.' FROM '.$this->table.$this->sql['where'];
    }

    /**
     * 重置查询参数
     *
     * @return void
     */
    private function reset()
    {
        $this->sql['where'] = '';
        $this->where = '';
    }

    // prevent a secend instance of it
    private function __clone() {}
    private function __wakeup() {}
}