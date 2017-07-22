<?php

namespace Includes\Classes\Db;

/**
 * 数据库连接类
 */
abstract class Db
{
    /**
     * @var array $settings 数据库连接配置
     * @var object $linkID 数据库连接实例
     * @var string $table 表名
     * @var array $data 要绑定的数据
     */ 
    protected $settings, $linkID, $table, $data;
    
    /**
     * @var array $sql SQL组成
     */
    protected $sql = [
        'where' => ''
    ];

    /**
     * @var int $queryCount 查询次数
     */
    public $queryCount = 0;

    /**
     * 设置数据库连接配置
     *
     * @param array $settings
     */
    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }

    /**
     * 设置表名
     *
     * @param string $name
     * @return void
     */
    public function table($name)
    {
        $this->table = '`'.$this->settings['prefix'].$name.'`';
    }

    /**
     * 执行INSERT、UPDATE、REPLACE操作
     *
     * @param array $data
     * @param string $mode
     * @return bool
     */
    public function exec(array $data, $mode)
    {
        $sql = $mode.' '.$this->table.' SET ';
        foreach ($data as $col => $value) {
            $sql .= '`'.$col.'`=?,';
        }
        return $this->query(rtrim($sql, ','), array_values($data), false);
    }

    /**
     * 查询指定字段
     *
     * @param mixed $field
     * @return mixed
     */
    public function select($field = false)
    {
        if (is_array($field)) {
            $expr = array_map(function($v){
                return '`'.$v.'`';
            }, $field);
        } elseif (is_string($field)) {
            $expr = '`'.$field.'`';
        } else {
            $expr = '*';
        }
        $sql = 'SELECT '.$expr.' FROM '.$this->table.$this->sql['where'];
        $result = $this->query($sql, $this->data);
        if (count($result) == 1) {
            if (count($result[0]) == 1) {
                return $result[0][$field];
            } else {
                return $result[0];
            }
        } else {
            return $result;
        }
    }

    /**
     * 设置WHERE条件
     *
     * @param array $where
     * @return static
     */
    public function where($where)
    {
        foreach ($where as $col => $value) {
            $this->sql['where'] = ' WHERE `'.$col.'`=?';
        }
        $this->data = array_values($where);
        return $this;
    }

    /**
     * 预处理
     *
     * @param string $sql
     * @return mysqli_stmt|PDOStatement
     */
    protected function prepare($sql)
    {
        if (!$this->linkID) {
            if ($linkID = $this->connect()) {
                $this->linkID = $linkID;
            } else {
                return false;
            }
            if (!$this->settings['debug']) {
                $this->linkID->query("SET sql_mode=''");
            }  
        }
        return $this->linkID->prepare($sql);
    }

    /**
     * 错误处理
     *
     * @param string $msg
     * @return mixed
     */
    protected function error($msg)
    {
        if ($this->settings['debug']) {
            trigger_error($msg, E_USER_ERROR);
        } else {
            return false;
        }
    }

    /**
     * 执行查询
     *
     * @param string $sql
     * @param array $data
     * @param bool $returnResult
     * @return mixed
     */
    abstract public function query($sql, array $data, $returnResult);

    /**
     * 连接数据库
     *
     * @return void
     */
    abstract protected function connect();
}
