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
//            if (Zend_Acl_Factory::getInstance()->isAllowed($user->login,$resource,$privilege)){
//                // Передача в макет полной информации о текущем пользователе.
//                Zend_Layout::getMvcInstance()->getView()->user=$user;
//            }
//            else 
                //print_r($user);
                //print($resource." ".$privilege);
            if (Zend_Acl_Factory::getInstance("2")->hasRole($user->login))
                print("OK");
            else
                print("BAD");
        }
    }
}

class Zend_Acl_Factory extends Zend_Acl{
    static private $instance=null; 
 
    private function __construct() { 
    } 
    static public function getInstance($n="empty") {  
        if (self::$instance == null) { 
            self::$instance=new Zend_Acl(); 
            $writer = new Zend_Log_Writer_Stream('c:/tmp/test.txt');
        $logger = new Zend_Log($writer);
        $logger->log('Acl getting '.$n, Zend_Log::INFO);
        } 
        return self::$instance; 
    }  
}
?>
