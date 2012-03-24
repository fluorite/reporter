<?php

class Application_Model_DbTable_ReportValues extends Zend_Db_Table_Abstract
{

    protected $_name = 'report_values';

    public function getValues($reportid){
         $reportid=(int)$reportid;
         
    }
    /**
     * Выборка значения требуемого показателя.
     * @param int $itemid идентификатор показателя.
     * @param string $field имя поля показателя.
     * @return mixed значения полей (или указанного поля) значения показателя. 
    */
    public function getValue($itemid,$field='',$userid=0){
        $itemid=(int)$itemid;
        if($userid == 0)
            // Идентификатор текущего пользователя.
            $userid=2;
        // Выборка значения требуемого показателя для текущего пользователя.
        $row=$this->fetchRow($this->select()->where('itemid=?',$itemid)->where('userid=?',$userid));
        if($row)
            if($field == '')
                // Возвращение всех полей значения показателя.
                return $row->toArray();
            else 
                // Возвращение значения только указанного поля значения показателя.
                return $row[$field];
        else
            return 0;
    }
    /**
     * Суммирование значений подчиненных показателей.
     * @param int $itemid идентификатор показателя.
     * @return int сумма значения подчиненных показателей. 
    */
    public function sumChildValues($itemid,$userid=0){
        $itemid=(int)$itemid;
        // Показатели отчёта.
        $items=new Application_Model_DbTable_ReportItems();
        $item=$items->getItem($itemid);
        if ($item['isvalue'] == 1){
            return $this->getValue($itemid,'value',$userid);
        }
        else{
            $sum=0;
            $children=$items->getChildItems($itemid);
            if (!is_null($children)){
                foreach($children as $child){
                    $sum+=$this->sumChildValues($child->id,$userid);
                }
            }
            return $sum;
        }
    }
    /**
     * Добавление значения требуемого показателя.
     * @param int $itemid идентификатор показателя. 
     * @param int $value значение показателя. 
    */
    public function insertValue($itemid,$value){
        $itemid=(int)$itemid;
        // Идентификатор текущего пользователя.
        $userid=2;
        $data=array(
            'itemid'=>$itemid,
            'userid'=>$userid,
            'value'=>$value,
            'isconfirmed'=>0
        );
        $this->insert($data);
    }
    /**
     * Удаление требуемого значения показателя.
     * @param int $itemid идентификатор показателя.
    */
    public function deleteValue($itemid){
        $itemid=(int)$itemid;
        // Идентификатор текущего пользователя.
        $userid=2;
        $this->delete(array('itemid ='.$itemid,'userid ='.$userid));
    }
    /**
     * Изменение значения требуемого показателя.
     * @param int $itemid идентификатор показателя. 
     * @param int $value значение показателя. 
    */
    public function updateValue($itemid,$value){
        $itemid=(int)$itemid;
        // Идентификатор текущего пользователя.
        $userid=2;
        $data=array(
            'value'=>$value
        );
        $this->update($data,array('itemid ='.$itemid,'userid ='.$userid));
    }
}

