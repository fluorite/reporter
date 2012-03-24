<?php

class Application_Model_DbTable_ReportLevels extends Zend_Db_Table_Abstract
{

    protected $_name = 'report_levels';

    public function getLevels($reportid){
         $reportid=(int)$reportid;       
         return $this->fetchAll($this->select()->where('reportid=?',$reportid)->order('number'));
    }
    public function getLevel($id){
        $id = (int)$id;
        $row=$this->fetchRow('id='.$id);
        if (!$row) {
            throw new Exception("Уровень [$id] отсутствует");
        }
        return $row->toArray();
    }
    public function getLastNumber($reportid){
        $reportid = (int)$reportid;
        // Выборка уровня с наибольшим номером.
        $row=$this->fetchRow($this->select()->where('reportid=?',$reportid)->order('number desc'));
        if ($row)
            // Возвращение наибольшего значения номера уровня.
            return $row['number'];
        else
            // Если для отчета не создано ни одного уровня, то возвращение нуля.
            return 0;
    }
    /**
     * Поиск первого по номеру уровня.
     * @param int $reportid идентификатор отчёта.
     * @return int идентификатор первого по номеру уровня или исключение, если для отчёта отсутствуют уровни. 
    */
    public function getFirstLevel($reportid){
        $reportid=(int)$reportid;
        $row=$this->fetchRow($this->select()->where('reportid=?',$reportid)->order('number asc'));
        if ($row)
            // Возвращение идентификатора первого уровня.
            return $row['id'];
        else
            // Если для отчета не создано ни одного уровня, то возвращение исключения.
            throw new Exception("Для отчёта [$reportid] нет уровней");
    }
    /**
     * Поиск последнего по номеру уровня.
     * @param int $reportid идентификатор отчёта.
     * @return int идентификатор последнего по номеру уровня или исключение, если для отчёта отсутствуют уровни. 
    */
    public function getLastLevel($reportid){
        $reportid=(int)$reportid;
        $row=$this->fetchRow($this->select()->where('reportid=?',$reportid)->order('number desc'));
        if ($row)
            // Возвращение идентификатора последнего уровня.
            return $row['id'];
        else
            // Если для отчета не создано ни одного уровня, то возвращение исключения.
            throw new Exception("Для отчёта [$reportid] нет уровней");
    }
    /**
     * Поиск следующего по номеру уровня.
     * @param int $id идентификатор текущего уровня.
     * @return int идентификатор следующего по номеру уровня или исключение, если следующий уровень отсутствует. 
    */
    public function getNextLevel($id){
        $id = (int)$id;
        // Выборка текущего уровня.
        $level=$this->getLevel($id);
        // Выборка следующих уровней.
        $row=$this->fetchRow($this->
            select()->
            where('reportid=?',$level['reportid'])->
            where('number>?',$level['number'])->
            order('number asc'));
        if ($row)
            // Возвращение идентификатора следующего уровня.
            return $row['id'];
        else
            // Если текущий уровень последний, то возвращение исключения.
            throw new Exception("Уровень [$id] последний");
    }
    public function insertLevel($reportid,$name,$number){
        $data=array(
            'reportid'=>$reportid,
            'name'=>$name,
            'number'=>$number
        );
        $this->insert($data);
    }
    public function deleteLevel($id){
        $this->delete('id ='.(int)$id);
    }
    public function updateLevel($id,$name){
        $data=array(
            'name'=>$name
        );
        $this->update($data,'id='.(int)$id);
    }
}

