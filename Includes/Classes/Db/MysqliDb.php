<?php

namespace Includes\Classes\Db;

/**
 * 通过mysqli扩展连接MySQL
 */
class MysqliDb extends Db
{
    public function execute($sql, array $data = [])
    {
        return $this->getStatement($sql, $data)->execute();
    }

    protected function connect()
    {
        $linkID = new \mysqli(
            $this->settings['host'], 
            $this->settings['user'], 
            $this->settings['pwd'], 
            $this->settings['name'], 
            $this->settings['port']
        );
        if ($linkID->connect_error) {
            if ($this->settings['debug']) {
                trigger_error($linkID->connect_error, E_USER_ERROR);
            } else {
                return false;
            }
        }
        $linkID->set_charset($this->settings['charset']);
        return $linkID;
    }

    protected function getStatement($sql, $data)
    {
        if (!$stmt = $this->prepare($sql)) {
            return false;
        }
        if ($this->linkID->error) {
            if ($this->settings['debug']) {
                trigger_error($this->linkID->error, E_USER_ERROR);
            } else {
                return false;
            }
        }
        if ($data) {
            $types = '';
            foreach ($data as $key => $value) {
                $types .= 's';
                $vars[] = &$data[$key];
            }
            array_unshift($vars, $types);
            call_user_func_array([$stmt, 'bind_param'], $vars);
        }
        return $stmt;
    }
}
