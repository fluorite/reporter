<?php

class UserController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $users = new Application_Model_DbTable_User();
        $this->view->users = $users->fetchAll();
    }

    public function insertAction()
    {
        $form = new Application_Form_User();
        $form->submit->setLabel('Добавить');
        $this->view->form = $form;

        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();

            if ($form->isValid($formData)) {
                $login = $form->getValue('login');
                $password = md5($form->getValue('password'));
                $firstname = $form->getValue('firstname');
                $middlename = $form->getValue('middlename');
                $lastname = $form->getValue('lastname');
                $users = new Application_Model_DbTable_User();
                $users->insertUser($login, $password, $firstname, $middlename, $lastname);
                $this->_helper->redirector('index');
            }
            else{
                $form->populate($formData);
            }
        }
    }

    public function deleteAction()
    {
        if ($this->getRequest()->isPost()) {
            $isConfirmed=$this->getRequest()->getPost('confirm');
            if ($isConfirmed == 'Да') {
                $id=$this->getRequest()->getPost('id');
                $users=new Application_Model_DbTable_User();
                $users->deleteUser($id);
            }
            $this->_helper->redirector('index');
        } 
        else {
            $id=$this->_getParam('id');
            $users=new Application_Model_DbTable_User();
            $this->view->user = $users->getUser($id);
        }
    }

    public function loginAction()
    {
        // Проверка аутентификации пользователя. 
        if (Zend_Auth::getInstance()->hasIdentity())        
            $this->_helper->redirector('index','index');
        $form=new Application_Form_Login();
        $this->view->form=$form;
        if ($this->getRequest()->isPost()){
            $formData=$this->getRequest()->getPost();
            if ($form->isValid($formData)){
                // Создание адаптера для аутентификации пользователя.
                $adapter=new Zend_Auth_Adapter_DbTable(Zend_Db_Table::getDefaultAdapter());
                $adapter->setTableName('user')->setIdentityColumn('login')->setCredentialColumn('password');
                // Получение логина и пароля пользователя из формы.
                $login=$form->getValue('login');
                $password=md5($form->getValue('password'));
                $adapter->setIdentity($login)->setCredential($password);
                // Проверка логина и пароля пользователя.
                $result = Zend_Auth::getInstance()->authenticate($adapter);
                if ($result->isValid()) {
                    // Получение полной информации о пользователе.
                    $user=$adapter->getResultRowObject();
                    // Сохранение полной информации о пользователе.
                    Zend_Auth::getInstance()->getStorage()->write($user);
                    // Добавление роли пользователя.
                    Zend_Acl_Factory::getInstance("1")->addRole(new Zend_Acl_Role($user->login));
                    // Получение ресурсов пользователя.
                    $resources=new Application_Model_DbTable_Resource();
                    foreach($resources->getUserResources($user->id) as $resource){
                        // Добавление ресурсов пользователя.
                        Zend_Acl_Factory::getInstance()->addResource(new Zend_Acl_Resource($resource->name));
                        // Получение привилегий пользователя.
                        $privileges=new Application_Model_DbTable_RolePrivileges();
                        foreach($privileges->getUserPrivileges($resource->id,$user->id) as $privilege){
                            // Добавление привилегий пользователя.
                            Zend_Acl_Factory::getInstance()->allow($user->login,$resource->name,$privilege->name);   
                        }
                    }                  
                    $this->_helper->redirector('index','index');
                } 
            }
        }
    }

    public function logoutAction()
    {
        // Удаление информации о текущем пользователе.
        Zend_Auth::getInstance()->clearIdentity();  
        $this->_helper->redirector('index','index');
    }

    public function updateAction()
    {
        // action body
    }


}











