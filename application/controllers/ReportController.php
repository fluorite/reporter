<?php

class ReportController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $reports=new Application_Model_DbTable_Report();
        $this->view->reports=$reports->fetchAll();
        $acl['Report|Insert']=$this->_helper->acl('report','insert');
        $acl['Report|Update']=$this->_helper->acl('report','update');
        $acl['Report|Delete']=$this->_helper->acl('report','delete');
        $acl['ReportLevels|Index']=$this->_helper->acl('report-levels','index');
        $acl['ReportItems|Index']=$this->_helper->acl('report-items','index');
        $acl['ReportValues|Index']=$this->_helper->acl('report-values','index');
        $acl['ReportValues|Combine']=$this->_helper->acl('report-values','combine');
        $acl['ReportValues|Print']=$this->_helper->acl('report-values','print');
        $this->view->acl=$acl;
    }

    public function insertAction()
    {
        $form=new Application_Form_Report();
        $this->view->form=$form;
        if ($this->getRequest()->isPost()){
            $formData=$this->getRequest()->getPost();
            if ($formData['submit']){
                if ($form->isValid($formData)){
                    $name=$form->getValue('name');
                    $reports=new Application_Model_DbTable_Report();
                    $reports->insertReport($name);
                    $this->_helper->redirector('index');
                }
                else{
                    $form->populate($formData);
                }
            }
            else {
                $this->_helper->redirector('index');
            }
        }
    }

    public function updateAction()
    {
        $form=new Application_Form_Report();
        $form->submit->setLabel('Сохранить');
        $this->view->form=$form;
        if ($this->getRequest()->isPost()){
            $formData=$this->getRequest()->getPost();
            if ($formData['submit']){
                if ($form->isValid($formData)) {
                    $id=(int)$form->getValue('id');
                    $name=$form->getValue('name');
                    $reports=new Application_Model_DbTable_Report();
                    $reports->updateReport($id,$name);
                    $this->_helper->redirector('index');
                } 
                else {
                    $form->populate($formData);
                }
            }
            else {
                $this->_helper->redirector('index');
            }
        } 
        else {
            $id=$this->_getParam('id', 0);
            if ($id > 0) {
                $reports=new Application_Model_DbTable_Report();
                $form->populate($reports->getReport($id));
            }
        }
    }

    public function deleteAction()
    {
        if ($this->getRequest()->isPost()) {
            $isConfirmed=$this->getRequest()->getPost('confirm');
            if ($isConfirmed == 'Удалить'){
                $id=$this->getRequest()->getPost('id');
                $reports=new Application_Model_DbTable_Report();
                $reports->deleteReport($id);
            }
            $this->_helper->redirector('index');
        } 
        else {
            $id=$this->_getParam('id',0);
            $reports=new Application_Model_DbTable_Report();
            $this->view->report=$reports->getReport($id);
        }
    }


}







