<?php

class ReportValuesController extends Zend_Controller_Action
{

    public function init()
    {
        // Вызов действия confirm будет производиться с помощью ajax-запроса с возвратом значение в формате json.
        $this->_helper->AjaxContext()->addActionContext('confirm','json')->initContext('json');
    }

    public function indexAction()
    {
        // Получение идентификатора отчета из запроса.
        $reportid=$this->_getParam('reportid',0);
        // Получение идентификатора пользователя из запроса.
        $userid=$this->_getParam('userid',0);
        if ($reportid != 0){
            // Показатели отчёта.
            $items=new Application_Model_DbTable_ReportItems();
            $this->view->items=$items->getItems($reportid);
            // Значения показателей отчёта.
            $values=new Application_Model_DbTable_ReportValues();
            foreach($this->view->items as $item) {
                if ($item->isvalue == 1){
                    $data[$item->id]=$values->getValue($item->id,'value',$userid);
                    // Подтверждение ненулевого значения показателя.
                    if ($data[$item->id] != 0){
                        $isconfirmed[$item->id]=$values->getValue($item->id,'isconfirmed',$userid);
                    }
                }
                else
                    $data[$item->id]=$values->sumChildValues($item->id,$userid);
            }
            $this->view->values=$data;
            $this->view->isconfirmed=$isconfirmed;
            // Идентификатор пользователя.
            $this->view->userid=$userid;
            // Идентификатор текущего пользователя.
            $userid=2;
            // Пользователи.
            $users=new Application_Model_DbTable_User();           
            $this->view->users=$users->getChildUsers($userid);
            // Идентификатор отчёта.
            $this->view->reportid=$reportid;
        }
    }

    public function insertAction()
    {
        // Получение идентификатора отчета из запроса.
        $reportid=$this->_getParam('reportid',0);
        if ($reportid != 0){
            $form=new Application_Form_ReportValues();         
            $this->view->form=$form;
            if ($this->getRequest()->isPost()){
                $formData=$this->getRequest()->getPost();
                if ($form->isValid($formData)){
                    $itemid=$form->getValue('itemid');
                    $value=$form->getValue('value');
                    $values=new Application_Model_DbTable_ReportValues();
                    $values->insertValue($itemid,$value);
                    $this->_helper->redirector->gotoRoute(array('controller'=>'report-values','action'=>'index','reportid'=>$reportid));
                }
                else{
                    $form->populate($formData);
                }
            }
            else{
                // Получение идентификатора показателя из запроса.
                $itemid=$this->_getParam('itemid',0);
                if ($itemid != 0)
                    $form->populate(array('itemid'=>$itemid));
            }
        }
    }

    public function updateAction()
    {
        // Получение идентификатора отчета из запроса.
        $reportid=$this->_getParam('reportid',0);
        if ($reportid != 0){
            $form=new Application_Form_ReportValues();
            $form->submit->setLabel('Сохранить');
            $this->view->form=$form;
            if ($this->getRequest()->isPost()){
                $formData=$this->getRequest()->getPost();
                if ($form->isValid($formData)) {
                    $itemid=(int)$form->getValue('itemid');
                    $value=$form->getValue('value');
                    // Обновление значения показателя в базе данных.
                    $values=new Application_Model_DbTable_ReportValues();
                    $values->updateValue($itemid,$value);
                    // Переход на страницу с перечнем значений показателей требуемого отчета.
                    $this->_helper->redirector->gotoRoute(array('controller'=>'report-values','action'=>'index','reportid'=>$reportid));
                } 
                else {
                    $form->populate($formData);
                }
            } 
            else {
                $itemid=$this->_getParam('itemid', 0);
                if ($itemid != 0) {
                    $values=new Application_Model_DbTable_ReportValues();
                    $form->populate(array('itemid'=>$itemid,'value'=>$values->getValue($itemid,'value')));
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
                    $itemid=$this->getRequest()->getPost('itemid');
                    // Удаление значения показателя из базы данных.
                    $values=new Application_Model_DbTable_ReportValues();
                    $values->deleteValue($itemid);
                }
                // Переход на страницу с перечнем значений показателей требуемого отчета.
                $this->_helper->redirector->gotoRoute(array('controller'=>'report-values','action'=>'index','reportid'=>$reportid));
            } 
            else {
                $itemid=$this->_getParam('itemid',0);  
                $this->view->itemid=$itemid;
                $this->view->reportid=$reportid;
                // Получение описания удаляемого показателя.
                $items=new Application_Model_DbTable_ReportItems();
                $this->view->item=$items->getItem($itemid,'name');            
            }
        }
    }

    public function confirmAction()
    {
        $this->view->data=$this->_getParam('itemid',0);
    }


}









