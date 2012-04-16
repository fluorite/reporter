<?php

class Application_Plugin_AccessHandler extends Zend_Controller_Plugin_Abstract
{   
    /**
     * Обработка запроса до передачи его диспетчеру запросов.
     * @param Zend_Controller_Request_Abstract $request запрос. 
    */
    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        $controller=$request->getControllerName();
        $action=$request->getActionName();
        // Проверка аутентификации пользователя.           
        if(!Zend_Auth::getInstance()->hasIdentity()){           
            // Пользователь не был аутентифицирован.
            if(($controller != 'user') || ($action != 'login'))
                // Переход на страницу аутентификации.
                $request->setControllerName('user')->setActionName('login');
        }
        else {
            // Чтение полной информации о пользователе.
            $user=Zend_Auth::getInstance()->getStorage()->read();
            // Передача в макет полной информации о текущем пользователе.
            Zend_Layout::getMvcInstance()->getView()->user=$user;
            try{
                if (!Zend_Acl_Factory::getInstance()->isAllowed($user->login,$controller,$action))                      
                    $request->setControllerName('error')->setActionName('access');
            }
            catch (Exception $e){
                $request->setControllerName('error')->setActionName('access');    
            }
        }
    }
}

class Zend_Acl_Factory extends Zend_Acl{
    static private $instance=null; 
 
    private function __construct() { 
    } 
    static public function getInstance() {  
        if (is_null(self::$instance)) { 
            $session=new Zend_Session_Namespace("ugrasu");
            if (!isset($session->acl)){
                self::$instance=new Zend_Acl();
                // Сохранение прав пользователя в сессии.
                $session->acl=self::$instance;
            }
            else
                // Чтение прав пользователя из сессии.
                self::$instance=$session->acl;
        } 
        return self::$instance; 
    }  
    static public function clearInstance(){
        // Удаление прав пользователя из сессии.
        $session=new Zend_Session_Namespace("ugrasu");
        unset($session->acl);
    }
}
?>