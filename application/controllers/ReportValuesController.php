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
            // Полная сумма показателей отчёта.
            $summary=0;
            // Отчет полностью подтвержден.
            $allconfirmed=true;
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
                        if ($isconfirmed[$item->id] == 0)
                            $allconfirmed=false;
                    }
                }
                else
                    $data[$item->id]=$values->sumChildValues($item->id,$userid);
                // Суммирование значений показателей верхнего уровня.
                if ($item->parentid == null)
                    $summary+=$data[$item->id];
            }
            $this->view->values=$data;
            $this->view->isconfirmed=$isconfirmed;
            $this->view->summary=$summary;
            $this->view->allconfirmed=$allconfirmed;
            // Идентификатор пользователя.
            $this->view->userid=$userid;
            // Подразделения.
            $departments=new Application_Model_DbTable_Department();
            $departmentid=$departments->headOf(Zend_Auth::getInstance()->getIdentity()->id);
            if ($departmentid != 0){
                // Пользователи.
                $users=new Application_Model_DbTable_User();           
                $this->view->users=$users->getDepartment($departmentid);
            }
            // Отчёты.
            $reports=new Application_Model_DbTable_Report();
            // Идентификатор отчёта.
            $this->view->report=$reports->getReport($reportid);
            // Права доступа пользователя.
            $acl['ReportValues|Confirm']=$this->_helper->acl('report-values','confirm');
            $acl['ReportValues|Insert']=$this->_helper->acl('report-values','insert');
            $acl['ReportValues|Update']=$this->_helper->acl('report-values','update');
            $acl['ReportValues|Delete']=$this->_helper->acl('report-values','delete');
            $this->view->acl=$acl;
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
                if ($formData['submit']){
                    // Обработка нажатия кнопки "Добавить".
                    if ($form->isValid($formData)){
                        $itemid=$form->getValue('itemid');
                        $value=$form->getValue('value');
                        $values=new Application_Model_DbTable_ReportValues();
                        $values->insertValue($itemid,$value);
                        // Переход на страницу с перечнем значений показателей требуемого отчета.
                        $this->_helper->redirector->gotoRoute(array('controller'=>'report-values','action'=>'index','reportid'=>$reportid));
                    }
                    else{
                        // Заполнение формы данными.                       
                        $items=new Application_Model_DbTable_ReportItems(); 
                        $item=$items->getItem($formData['itemid']);
                        $form->populate(array(
                            'itemid'=>$formData['itemid'],
                            'title'=>$item['number'].' '.$item['name'],
                            'value'=>$formData['value']));
                    }
                }
                else {
                    // Переход на страницу с перечнем значений показателей требуемого отчета.
                    $this->_helper->redirector->gotoRoute(array('controller'=>'report-values','action'=>'index','reportid'=>$reportid));
                } 
            }
            else{
                // Получение идентификатора показателя из запроса.
                $itemid=$this->_getParam('itemid',0);               
                if ($itemid != 0){
                    // Показатели отчёта.
                    $items=new Application_Model_DbTable_ReportItems();
                    $item=$items->getItem($itemid);
                    $form->populate(array(
                        'itemid'=>$itemid,
                        'title'=>$item['number'].' '.$item['name'],));
                }
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
                if ($formData['submit']){
                    // Обработка нажатия кнопки "Сохранить".
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
                        // Заполнение формы данными.                       
                        $items=new Application_Model_DbTable_ReportItems(); 
                        $item=$items->getItem($formData['itemid']);
                        $form->populate(array(
                            'itemid'=>$formData['itemid'],
                            'title'=>$item['number'].' '.$item['name'],
                            'value'=>$formData['value']));
                    }
                }
                else {
                    // Переход на страницу с перечнем значений показателей требуемого отчета.
                    $this->_helper->redirector->gotoRoute(array('controller'=>'report-values','action'=>'index','reportid'=>$reportid));
                }               
            } 
            else {
                $itemid=$this->_getParam('itemid', 0);
                if ($itemid != 0) {
                    // Показатели отчёта.
                    $items=new Application_Model_DbTable_ReportItems(); 
                    $item=$items->getItem($itemid);
                    $values=new Application_Model_DbTable_ReportValues();
                    $form->populate(array(
                        'itemid'=>$itemid,
                        'title'=>$item['number'].' '.$item['name'],
                        'value'=>$values->getValue($itemid,'value')));
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
                if ($isConfirmed == 'Удалить'){
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
        // Получение идентификатора показателя отчета из запроса.
        $itemid=$this->_getParam('itemid',0);
        // Получение идентификатора пользователя из запроса.
        $userid=$this->_getParam('userid',0);
        if (($itemid != 0) && ($userid != 0)){
            // Получение состояния подтверждения значения показателя из запроса.
            $isconfirmed=$this->_getParam('isconfirmed',-1);   
            if (($isconfirmed == 0) || ($isconfirmed == 1)){
                // Изменение подтверждение значения показателя в базе данных.
                $values=new Application_Model_DbTable_ReportValues();
                $values->confirmValue($itemid, $userid, $isconfirmed);
            }
        }
    }

    public function combineAction()
    {
        // Получение идентификатора отчета из запроса.
        $reportid=$this->_getParam('reportid',0);
        if ($reportid != 0){
            // Подразделения.
            $departmentsdb=new Application_Model_DbTable_Department();
            $departmentid=$departmentsdb->headOf(Zend_Auth::getInstance()->getIdentity()->id);
            if ($departmentid != 0){
                // Пользователи.
                $usersdb=new Application_Model_DbTable_User();           
                $users=$usersdb->getDepartment($departmentid); 
                // Показатели отчёта.
                $itemsdb=new Application_Model_DbTable_ReportItems();
                $items=$itemsdb->getItems($reportid);
                // Значения показателей отчёта.
                $valuesdb=new Application_Model_DbTable_ReportValues();
                foreach($items as $item) {                
                    if ($item->parentid == null){
                        // Показатель верхнего уровня.
                        $parents[]=$item;
                        foreach($users as $user){
                            // Значение показателя верхнего уровня для сотрудника подразделения.
                            if ($item->isvalue == 1)
                                $values[$item->id][$user->id]=$valuesdb->getValue($item->id,'value',$user->id);
                            else
                                $values[$item->id][$user->id]=$valuesdb->sumChildValues($item->id,$user->id); 
                            $values[$item->id][-1]+=$values[$item->id][$user->id];
                            $values[-1][$user->id]+=$values[$item->id][$user->id];
                        }
                    }
                }
                // Массив сотрудников подразделения.
                $this->view->users=$users;
                // Массив показателей верхнего уровня.
                $this->view->parents=$parents;
                // Массив значений показателей верхненего уровня для сотрудников подразделения.
                $this->view->values=$values;
            }
        }
    }


}











