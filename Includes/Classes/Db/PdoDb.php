<?php

namespace Includes\Classes\Db;

/**
 * 通过PDO扩展连接MySQL
 */
class PdoDb extends Db
{
    public function execute($sql, array $data = [])
    {
        return $this->prepare($sql)->execute($data);
    }

    protected function connect()
    {
        try {
            return new \PDO(
                'mysql:host='.$this->settings['host'].';dbname='.$this->settings['name'].';charset='.$this->settings['charset'], 
                $this->settings['user'], 
                $this->settings['pwd']
            );
        } catch (PDOException $e) {
            if ($this->settings['debug']) {
                trigger_error($e->getMessage(), E_USER_ERROR);
            } else {
                return false;
            }
        }
    }
}
