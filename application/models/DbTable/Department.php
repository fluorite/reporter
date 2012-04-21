<?php

class Application_Model_DbTable_Department extends Zend_Db_Table_Abstract
{

    protected $_name = 'department';

    /**
     * Выборка подразделения, возглавляемого пользователем.
     * @param int $userid идентификатор показателя.
     * @return int идентификатор подразделения, возглавляемого пользователем. 
    */
    public function headOf($userid){
        $userid = (int)$userid;
        // Выборка подразделения, возглавляемого пользователем.
        $row=$this->fetchRow('headid='.$userid);
        if (!$row)
            return 0;
        else
            return $row->id;
    }
    public function getDepartment($id){
        $id=(int)$id;
        $row=$this->fetchRow('id='.$id);
        if (!$row) {
            throw new Exception("Подразделение [$id] отсутствует");
        }
        return $row->toArray();
    }
}

