<?php

class ReportLevelsController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // Получение идентификатора отчета из запроса.
        $reportid=$this->_getParam('reportid',0);
        if ($reportid != 0){
            $levels=new Application_Model_DbTable_ReportLevels();
            $this->view->levels=$levels->getLevels($reportid);
            $this->view->reportid=$reportid;
            $this->view->lastnumber=$levels->getLastNumber($reportid);
        }
    }

    public function insertAction()
    {
        // Получение идентификатора отчета из запроса.
        $reportid=$this->_getParam('reportid',0);
        if ($reportid != 0){
            $form=new Application_Form_ReportLevels();         
            $this->view->form=$form;
            if ($this->getRequest()->isPost()){
                $formData=$this->getRequest()->getPost();
                if ($form->isValid($formData)){
                    $reportid=$form->getValue('reportid');
                    $name=$form->getValue('name');
                    $number=$form->getValue('number');
                    $levels=new Application_Model_DbTable_ReportLevels();
                    $levels->insertLevel($reportid,$name,$number);
                    $this->_helper->redirector->gotoRoute(array('controller'=>'report-levels','action'=>'index','reportid'=>$reportid));
                }
                else{
                    $form->populate($formData);
                }
            }
            else{
                $levels=new Application_Model_DbTable_ReportLevels();
                $form->populate(array('reportid'=>$reportid,'number'=>$levels->getLastNumber($reportid)+1));
            }
        }
    }

    public function updateAction()
    {
        $form=new Application_Form_ReportLevels();
        $form->submit->setLabel('Сохранить');
        $this->view->form=$form;
        if ($this->getRequest()->isPost()){
            $formData=$this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $id=(int)$form->getValue('id');
                $reportid=(int)$form->getValue('reportid');
                $name=$form->getValue('name');
                $levels=new Application_Model_DbTable_ReportLevels();
                $levels->updateLevel($id,$name);
                $this->_helper->redirector->gotoRoute(array('controller'=>'report-levels','action'=>'index','reportid'=>$reportid));
            } 
            else {
                $form->populate($formData);
            }
        } 
        else {
            $id=$this->_getParam('id', 0);
            if ($id != 0) {
                $levels=new Application_Model_DbTable_ReportLevels();
                $form->populate($levels->getLevel($id));
            }
        }
    }

    public function deleteAction()
    {
        // Получение идентификатора отчета из запроса.
        $reportid=$this->_getParam('reportid',0);
        if ($reportid != 0){
            if ($this->getRequest()->isPost()) {
                $isConfirmed=$this->getRequest()->getPost('confirm');
                if ($isConfirmed == 'Да'){
                    $id=$this->getRequest()->getPost('id');
                    $levels=new Application_Model_DbTable_ReportLevels();
                    $levels->deleteLevel($id);
                }
                $this->_helper->redirector->gotoRoute(array('controller'=>'report-levels','action'=>'index','reportid'=>$reportid));
            } 
            else {
                $id=$this->_getParam('id',0);          
                $levels=new Application_Model_DbTable_ReportLevels();
                $this->view->level=$levels->getLevel($id);
                $this->view->reportid=$reportid;
            }
        }
    }
}







