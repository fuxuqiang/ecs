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
     * @var PDO   $linkID PDO实例
     */
    private $settings, $where = [], $linkID;

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
        if ($this->linkID === null) {
            $this->connect();
        }
        // 记录查询开始时间
        $this->queryTime = microtime(true);
        // 执行查询
        if (!$stmt = $this->linkID->prepare($sql)) {
            if ($this->settings['quiet']) {
                $this->error = $this->linkID->error;
                return false;
            } else {
                trigger_error($this->linkID->error, E_USER_ERROR);
            }
        }
        // 
        if ($bindings) {
            $types = '';
            foreach ($bindings as $v) {
                $types .= 's';
            }
            $bindings = array_unshift($bindings, $types);
            call_user_func_array([$stmt, 'bind_param'], $bindings);
        }
        // 
        $stmt->execute();
        // 查询次数
        $this->queryCount++;
        // 返回查询结果
        return $stmt->get_result();
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