<?php
/**
 * Class ActiveRecord
 *
 * @package Framework\Model\ActiveRecord
 */

namespace Framework\Model;

use Framework\DI\Service;

abstract class ActiveRecord
{
    public $name;

    public static function getTable()
    {

    }
    
    /**
     * 
     * @return string
     */
    public static function getPrimaryKey()
    {
        return 'id';
    }

    /**
     * 
     * @param type $mode
     * @return type
     */
    public static function find($mode = 'all')
    {
        $table  = static::getTable();
        $params = array();
        $pdo    = Service::get('db')->getDBCon();

        $sql = "SELECT * FROM " . $table;
        if(is_numeric($mode)){
            $sql .= " WHERE id= :mode";
            $params = array('mode' => (int)$mode);
        }

        $res = $pdo->prepare($sql);
        $res->execute($params);

        if(is_numeric($mode)){
            $result = $res->fetchObject("Blog\Model\Post");
        }else{
            $result = $res->fetchAll(\PDO::FETCH_CLASS, "Blog\Model\Post");
        }

        return $result;
    }

    /**
     * 
     * @param type $email
     * @return type
     */
    public static function findByEmail($email)
    {
        $table  = static::getTable();
        $params = array('email' => (string)$email);
        $pdo    = Service::get('db')->getDBCon();

        $sql = "SELECT * FROM " . $table." WHERE email= :email";
        $res = $pdo->prepare($sql);
        $res->execute($params);
        $row = $res->fetchObject("Blog\Model\User");

        return $row;
    }

    /**
     * 
     * @return type
     */
    protected function getFields()
    {
        return get_object_vars($this);
    }

    /**
     * 
     */
    public function save()
    {
        $fields       = $this->getFields();
        $table        = static::getTable();
        $primaryKey   = $this->getPrimaryKey();
        $plaseholders = '';
        $data         = array();
        $pdo          = Service::get('db')->getDBCon();

        foreach($fields as $key=>$value){
            if (!empty($value)){
                $plaseholders .= $key.' = :'.$key.', ';
                $data[$key] = $value;
            }
        }

        $plaseholders = substr($plaseholders, 0, -2);

        if (isset($data[$primaryKey])) {
            $sql   = "UPDATE ";
            $where = " WHERE ".$primaryKey." = :".$primaryKey;
        }else{
            $sql   = "INSERT INTO ";
            $where = '';
        }

        $sql .= $table." SET ".$plaseholders.$where;

        $res = $pdo->prepare($sql);
        $res->execute($data);
    }

    /**
     * 
     * @param type $user_id
     * @return type
     */
    public static function getProfile($user_id)
    {
        $pdo = Service::get('db')->getDBCon();

        $table = static::getTable();
        $params = array('user_id' => (int)$user_id);

        $sql = "SELECT * FROM " . $table." WHERE user_id= :user_id";
        
        $res = $pdo->prepare($sql);
        $res->execute($params);
        $row = $res->fetchObject("CMS\Model\Profile");

        return $row;
    }
}
