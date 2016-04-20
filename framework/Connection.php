<?php
/**
 * Class Connection
 *
 * @package Framework
 */

namespace Framework;

use Framework\DI\Service;

class Connection {
    private $db;

    /**
     * Class constructor
     */
    public function __construct($pdo)
    {
        $this->db =  new \PDO($pdo['dns'], $pdo['user'], $pdo['password']);
    }

    /**
     * 
     * @return type
     */
    public function getDBCon()
    {
        return $this->db;
    }
	
}
