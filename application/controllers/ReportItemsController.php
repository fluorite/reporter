<?php

class ReportItemsController extends Zend_Controller_Action
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
            // Показатели отчёта.
            $items=new Application_Model_DbTable_ReportItems();
            $this->view->items=$items->getItems($reportid);
            // Свойства показателей отчёта.
            foreach($this->view->items as $item) {
                $properties[$item->id]=$items->hasChildren($item->id);
            }
            $this->view->properties=$properties;
            // Идентификатор последнего уровня отчёта.
            $levels=new Application_Model_DbTable_ReportLevels();
            $this->view->lastlevelid=$levels->getLastLevel($reportid);
            // Идентификатор отчёта.
            $this->view->reportid=$reportid;
        }
    }

    public function insertAction()
    {
        // Получение идентификатора отчета из запроса.
        $reportid=$this->_getParam('reportid',0);
        if ($reportid != 0){
            $form=new Application_Form_ReportItems();         
            $this->view->form=$form;
            if ($this->getRequest()->isPost()){
                $formData=$this->getRequest()->getPost();
                if ($form->isValid($formData)){
                    $parentid=$form->getValue('parentid');
                    $levelid=$form->getValue('levelid');
                    $name=$form->getValue('name');
                    $number=$form->getValue('number');
                    $isvalue=$form->getValue('isvalue');
                    $items=new Application_Model_DbTable_ReportItems();
                    $items->insertItem($parentid,$levelid,$name,$number,$isvalue);
                    $this->_helper->redirector->gotoRoute(array('controller'=>'report-items','action'=>'index','reportid'=>$reportid));
                }
                else{
                    $form->populate($formData);
                }
            }
            else{
                // Получение идентификатора родительского показателя из запроса.
                $parentid=$this->_getParam('parentid',0);
                $levels=new Application_Model_DbTable_ReportLevels();
                $items=new Application_Model_DbTable_ReportItems();
                if ($parentid != 0){
                    // Получение идентификатора уровня отчёта из запроса.
                    $levelid=$this->_getParam('levelid',0);
                    $form->populate(array(
                        'parentid'=>$parentid,
                        'levelid'=>$levels->getNextLevel($levelid),
                        'number'=>$items->getItem($parentid,'number').'.'.($items->getLastChildNumber($parentid)+1)));
                }
                else{
                    $form->populate(array(
                        'parentid'=>$parentid,
                        'levelid'=>$levels->getFirstLevel($reportid),
                        'number'=>($items->getLastLevelNumber($levels->getFirstLevel($reportid))+1)));
                }
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
                    $items=new Application_Model_DbTable_ReportItems();
                    $items->deleteItem($id);
                }
                $this->_helper->redirector->gotoRoute(array('controller'=>'report-items','action'=>'index','reportid'=>$reportid));
            } 
            else {
                $id=$this->_getParam('id',0);          
                $items=new Application_Model_DbTable_ReportItems();
                $this->view->item=$items->getItem($id);
                $this->view->reportid=$reportid;
            }
        }
    }

    public function updateAction()
    {
        // Получение идентификатора отчета из запроса.
        $reportid=$this->_getParam('reportid',0);
        if ($reportid != 0){
            $form=new Application_Form_ReportItems(); 
            $form->submit->setLabel('Сохранить');
            $this->view->form=$form;
            if ($this->getRequest()->isPost()){
                $formData=$this->getRequest()->getPost();
                if ($form->isValid($formData)){
                    $id=(int)$form->getValue('id');                 
                    $name=$form->getValue('name');                    
                    $isvalue=$form->getValue('isvalue');
                    $items=new Application_Model_DbTable_ReportItems();
                    $items->updateItem($id,$name,$isvalue);
                    $this->_helper->redirector->gotoRoute(array('controller'=>'report-items','action'=>'index','reportid'=>$reportid));
                }
                else{
                    $form->populate($formData);
                }
            }
            else{
                // Получение идентификатора показателя из запроса.
                $id=$this->_getParam('id',0);
                $items=new Application_Model_DbTable_ReportItems();    
                $item=$items->getItem($id);
                $form->populate(array(
                    'id'=>$item['id'],
                    'name'=>$item['name'],
                    'number'=>$item['number'],
                    'isvalue'=>$item['isvalue']));              
            }
        }
    }


}







