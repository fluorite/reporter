<?php

class Controller_Action_Helper_Acl extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * Определение разрешения текущего пользователя на доступ к ресурсу.
     * @param string $resource название ресурса.
     * @param string $privilege название привилегии.
     * @return boolean true, если пользователю разрешен доступ к ресурсу. 
    */
    function direct($resource,$privilege)
    {
        $isAllowed=false;
        try{ 
            if (Zend_Acl_Factory::getInstance()->isAllowed(Zend_Auth::getInstance()->getIdentity()->login,$resource,$privilege)){ 
                $isAllowed=true;
            }  
        }
        catch(Exception $e){
        }
        
        return $isAllowed;
    }
}
?>
