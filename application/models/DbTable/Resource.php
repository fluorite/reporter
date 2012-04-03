<?php

class Application_Model_DbTable_Resource extends Zend_Db_Table_Abstract
{
    protected $_name = 'resource';
    
    /**
     * Выборка ресурса.
     * @param int $id идентификатор ресурса.
     * @return mixed массив значений полей ресурса. 
    */
    public function getResource($id){
        $id = (int)$id;
        $row=$this->fetchRow('id='.$id);
        if (!$row) {
            throw new Exception("Ресурс [$id] отсутствует");
        }
        return $row->toArray();
    }
    /**
     * Выборка ресурсов пользователя.
     * @param int $userid идентификатор пользователя.
     * @return mixed массив ресурсов пользователя. 
    */
    public function getUserResources($userid){
         $userid=(int)$userid;
         
         return $this->fetchAll($this
             ->select()
             ->distinct()
             ->from(array('r'=>'resource'),array('id','name'))
             ->join(array('rp'=>'role_privileges'),'rp.resourceid=r.id',array())
             ->join(array('rl'=>'role'),'rl.id=rp.roleid',array())
             ->join(array('ur'=>'user_roles'),'ur.roleid=rl.id',array())      
             ->where('ur.userid=?',$userid));
    }
}

