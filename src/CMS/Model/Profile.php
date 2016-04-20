<?php
/**
 * Created by PhpStorm.
 * User: dgilan
 * Date: 10/17/14
 * Time: 12:09 PM
 */

namespace CMS\Model;

use Framework\Model\ActiveRecord;
use Framework\Validation\Filter\Length;

class Profile extends ActiveRecord
{
    public $id;
    public $user_id;
    public $name;
    public $second_name;
    public $info;

    public static function getTable()
    {
        return 'profile';
    }
        
    public function getRules()
    {
        return array(
            'name'   => array(
                new Length(1, 255)
            ),
            
            'second_name'   => array(
                new Length(1, 255)
            ),
            
            'info'   => array(
                new Length(0, 255)
            )
        );
    }
}
