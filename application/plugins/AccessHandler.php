<?php

class Application_Plugin_AccessHandler extends Zend_Controller_Plugin_Abstract
{   
    /**
     * Обработка запроса до передачи его диспетчеру запросов.
     * @param Zend_Controller_Request_Abstract $request запрос. 
    */
    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        $resource=$request->getControllerName();
        $privilege=$request->getActionName();
        // Проверка аутентификации пользователя.           
        if(!Zend_Auth::getInstance()->hasIdentity()){           
            // Пользователь не был аутентифицирован.
            if(($resource != 'user') || ($privilege != 'login'))
                // Переход на страницу аутентификации.
                $request->setControllerName('user')->setActionName('login');
        }
        else {
            // Чтение полной информации о пользователе.
            $user=Zend_Auth::getInstance()->getStorage()->read();
            if (Zend_Acl_Factory::getInstance()->isAllowed($user->login,$resource,$privilege)){
                // Передача в макет полной информации о текущем пользователе.
                Zend_Layout::getMvcInstance()->getView()->user=$user;
            }
        }
    }
}

class Zend_Acl_Factory extends Zend_Acl{
    static private $instance=null; 
 
    private function __construct() { 
    } 
    static public function getInstance() {  
        if (self::$instance == null) { 
            self::$instance=new Zend_Acl(); 
        } 
        return self::$instance; 
    }  
}
?>
