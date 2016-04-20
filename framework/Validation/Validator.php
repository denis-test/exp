<?php
/**
 * Class Validation
 *
 * @package Framework\Validation
 */

namespace Framework\Validation;

class Validator
{
    private $obj;
    private $rules;
    private $errors = array();

    public function __construct($obj){
        $this->obj = $obj;
        $this->rules = $this->obj->getRules();
    }
    
    /**
     * 
     * @return type
     */
    public function isValid(){
        $result = false;
        foreach ($this->rules as $fild => $value){
            foreach ($value as $filter){
                $result = $filter->isValid($this->obj->$fild);
                if (!$result){
                    $this->errors[$fild] = $filter->getError();
                }
            }
        }
        
        return (empty($this->errors)? true : false);
    }
    
    /**
     * 
     * @return type
     */
    public function getErrors()
    {
        return (empty($this->errors)? null : $this->errors);
    }
}
