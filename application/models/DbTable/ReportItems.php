<?php

class Application_Model_DbTable_ReportItems extends Zend_Db_Table_Abstract
{

    protected $_name = 'report_items';

    public function getItems($reportid){
         $reportid=(int)$reportid;
         $levelsdb=new Application_Model_DbTable_ReportLevels();
         // Определение количества уровней в отчете.
         $levels=$levelsdb->getLastNumber($reportid);
         // Формирование списка сортировки показателей отчета.
         $orderby=array();
         for($i=1; $i <= $levels; $i++){
             $orderby[]="cutlevel(i.number,$i)";
         }
         return $this->fetchAll($this
             ->select()
             ->from(array('i'=>'report_items'),array('id','parentid','levelid','name','number','isvalue'))
             ->join(array('l'=>'report_levels'),'i.levelid=l.id',array())
             ->where('l.reportid=?',$reportid)    
             ->order($orderby));
    }
    /**
     * Выборка требуемого показателя.
     * @param int $id идентификатор показателя.
     * @param string $field имя поля показателя.
     * @return mixed значения полей (или указанного поля) показателя. 
    */
    public function getItem($id,$field=''){
        $id=(int)$id;
        $row=$this->fetchRow('id='.$id);
        if (!$row) {
            throw new Exception("Показатель [$id] отсутствует");
        }
        if($field == '')
            // Возвращение значения всех полей показателя.
            return $row->toArray();
        else
            // Возвращение значения только указанного поля показателя.
            return $row[$field];
    }
    /**
     * Поиск последнего номера среди подчиненных показателей.
     * @param int $parentid идентификатор родительского показателя.
     * @return string номер последнего показателя или ноль, если подчиненные показатели отсутствуют. 
    */
    public function getLastChildNumber($parentid){
        $parentid = (int)$parentid;
        // Выборка показателя с наибольшим номером.
        $row=$this->fetchRow($this->select()->from(array('report_items'),array('last'=>'count(*)'))->where('parentid=?',$parentid));
        // Возвращение наибольшего значения номера показателя.
        return $row['last'];
    }
    /**
     * Поиск последнего номера среди показателей уровня.
     * @param int $levelid идентификатор уровня отчёта.
     * @return string номер последнего показателя или ноль, если показатели уровня отсутствуют. 
    */
    public function getLastLevelNumber($levelid){
        $levelid=(int)$levelid;
        // Выборка показателя с наибольшим номером.
        $row=$this->fetchRow($this->select()->from(array('report_items'),array('last'=>'count(*)'))->where('levelid=?',$levelid));
        // Возвращение наибольшего значения номера показателя.
        return $row['last'];
    }
    /**
     * Проверка наличия подчиненных показателей.
     * @param int $id идентификатор показателя.
     * @return bool true, если имеются подчиненные показатели. 
    */
    public function hasChildren($id){
        $id = (int)$id;
        // Выборка количества подчиненных показателей.
        $row=$this->fetchRow($this->select()->from(array('report_items'),array('has'=>'count(*)'))->where('parentid=?',$id));
        if ($row['has'] != 0)
            return true;
        else
            return false;
    }
    /**
     * Выборка подчиненных показателей.
     * @param int $id идентификатор показателя.
     * @return mixed массив подчиненных показателей. 
    */
    public function getChildItems($id){
        $id = (int)$id;
        // Выборка подчиненных показателей.
        return $this->fetchAll($this->select()->from(array('report_items'),array('id','parentid','levelid','name','number','isvalue'))->where('parentid=?',$id));
    }
    public function insertItem($parentid,$levelid,$name,$number,$isvalue){
        if ($parentid != 0)
            $data=array(
                'parentid'=>$parentid,
                'levelid'=>$levelid,
                'name'=>$name,
                'number'=>$number,
                'isvalue'=>$isvalue
            );
        else
            // Добавление показателя верхнего уровня.
            $data=array(
                'levelid'=>$levelid,
                'name'=>$name,
                'number'=>$number,
                'isvalue'=>$isvalue
            );
        $this->insert($data);
    }
    /**
     * Изменение требуемого показателя.
     * @param int $id идентификатор показателя.
     * @param string $name название показателя.
     * @param int $isvalue наличие значения для показателя (1, если значение есть, и 0 в противном случае).
    */
    public function updateItem($id,$name,$isvalue){
        if (($isvalue != 0) && ($isvalue != 1)) {
            throw new Exception("Ошибочное наличие значения для показателя [$isvalue]. Наличие значения для показателя [$id] должно быть 1 или 0.");
        }     
        $data=array(            
            'name'=>$name,
            'isvalue'=>$isvalue
        );
        $this->update($data,'id='.(int)$id);
    }
    /**
     * Удаление требуемого показателя.
     * @param int $id идентификатор показателя.
    */
    public function deleteItem($id){
        $this->delete('id ='.(int)$id);
    }
}

