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
     */ 
    protected $settings, $linkID, $table;
    
    /**
     * 设置数据库连接配置
     *
     * @param array $settings
     */
    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }

    // 设置表名
    public function table($name)
    {
        $this->table = '`'.$this->settings['prefix'].$name.'`';
    }

    /**
     * 执行INSERT、UPDATE、REPLACE操作
     *
     * @param array $data
     * @param string $mode
     * @return string
     */
    public function exec(array $data, $mode)
    {
        $sql = $mode.' '.$this->table.' SET ';
        foreach ($data as $col => $value) {
            $sql .= '`'.$col.'`=?,';
        }
        return $this->execute(rtrim($sql, ','), $data);
    }

    /**
     * 预处理
     *
     * @param string $sql
     * @return object
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
     * 连接数据库
     *
     * @return void
     */
    abstract protected function connect();

    /**
     * 执行查询
     *
     * @param string $sql
     * @param array $data
     * @return mixed
     */
    abstract public function execute($sql, array $data);
}
