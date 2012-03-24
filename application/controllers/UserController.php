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
                $password = $form->getValue('password');
                $firstname = $form->getValue('firstname');
                $middlename = $form->getValue('middlename');
                $lastname = $form->getValue('lastname');
                $users = new Application_Model_DbTable_User();
                $users->insertUser($login, $password, $firstname, $middlename, $lastname);


                $this->_helper->redirector('index');
            }else{
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
        } else {
            $id=$this->_getParam('id');
            $users=new Application_Model_DbTable_User();
            $this->view->user = $users->getUser($id);
        }
    }


}





