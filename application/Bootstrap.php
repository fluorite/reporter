<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initPlugins()
    {
        $front=Zend_Controller_Front::getInstance();
        // Регистрация плагина контроля доступа пользователей.
        $front->registerPlugin(new Application_Plugin_AccessHandler()); 
    }
    protected function _initHelpers()
    {
        Zend_Controller_Action_HelperBroker::addPath(APPLICATION_PATH .'/controllers/helpers','Controller_Action_Helper');
    }
}

