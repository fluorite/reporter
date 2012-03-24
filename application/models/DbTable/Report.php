<?php

class Application_Model_DbTable_Report extends Zend_Db_Table_Abstract
{

    protected $_name = 'report';

    public function insertReport($name){
        $data=array(
            'name'=>$name
        );
        $this->insert($data);
    }
    public function deleteReport($id){
        $this->delete('id ='.(int)$id);
    }
    public function getReport($id){
        $id = (int)$id;
        $row=$this->fetchRow('id='.$id);
        if (!$row) {
            throw new Exception("Отчет [$id] отсутствует");
        }
        return $row->toArray();
    }
    public function updateReport($id,$name){
        $data=array(
            'name'=>$name,
        );
        $this->update($data,'id='.(int)$id);
    }
}

