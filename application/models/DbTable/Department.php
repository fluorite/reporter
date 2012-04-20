<?php

class Application_Model_DbTable_Department extends Zend_Db_Table_Abstract
{

    protected $_name = 'department';

    /**
     * Выборка подразделения, возглавляемого пользователем.
     * @param int $id идентификатор показателя.
     * @return bool true, если пользователь руководит каким-либо подразделением. 
    */
    public function isManager($userid){
        $userid = (int)$userid;
        // Выборка количества подчиненных показателей.
        $row=$this->fetchRow($this->select()->from(array('report_items'),array('has'=>'count(*)'))->where('parentid=?',$id));
        if ($row['has'] != 0)
            return true;
        else
            return false;
    }
}

