<?php
/**    
 * NotBlank.php
 * 
 */

namespace Framework\Validation\Filter;

class NotBlank implements ValidationFilterInterface {
    public $error = null;
    
    /**
     * 
     * @param type $value
     * @return type
     */
    public function isValid($value){
        $result = !empty($value);
        
        if (!$result) {
            $this->error = 'The field must not be blank';
        }

        return $result;
    }
    
    /**
     * 
     * @return type
     */
    public function getError()
    {
        return $this->error;
    }
} 
