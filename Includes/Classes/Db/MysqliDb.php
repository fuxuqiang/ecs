<?php

namespace Includes\Classes\Db;

/**
 * 通过mysqli扩展连接MySQL
 */
class MysqliDb extends Db
{
    public function query($sql, array $data = [], $returnResult = true)
    {
        if (!$stmt = $this->prepare($sql)) {
            return $this->error($this->linkID->error);
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
        $stmt->execute();
        $this->queryCount++;
        if ($returnResult) {
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        } else {
            return true;
        }
    }

    protected function connect()
    {
        $linkID = @new \mysqli(
            $this->settings['host'], 
            $this->settings['user'], 
            $this->settings['pwd'], 
            $this->settings['name'], 
            $this->settings['port']
        );
        if ($linkID->connect_error) {
            return $this->error($linkID->connect_error);
        }
        $linkID->set_charset($this->settings['charset']);
        return $linkID;
    }
}
