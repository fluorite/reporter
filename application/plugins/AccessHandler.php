<?php

class Application_Plugin_AccessHandler extends Zend_Controller_Plugin_Abstract
{   
    /**
     * Обработка запроса до передачи его диспетчеру запросов.
     * @param Zend_Controller_Request_Abstract $request запрос. 
    */
    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        // Проверка аутентификации пользователя.           
        if(!Zend_Auth::getInstance()->hasIdentity()){           
            // Пользователь не был аутентифицирован.
            if(($request->getControllerName() != 'user') || ($request->getActionName() != 'login'))
                // Переход на страницу аутентификации.
                $request->setControllerName('user')->setActionName('login');
        }
        else
            // Передача в макет полной информации о текущем пользователе.
            Zend_Layout::getMvcInstance()->getView()->user=Zend_Auth::getInstance()->getStorage()->read();
    }
}
?>
