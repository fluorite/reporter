<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initPlugins()
    {
        $front=Zend_Controller_Front::getInstance();
        // Регистрация плагина контроля доступа пользователей.
        $front->registerPlugin(new Application_Plugin_AccessHandler()); 
    }
}

