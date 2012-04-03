<?php

class Application_Model_DbTable_RolePrivileges extends Zend_Db_Table_Abstract
{
    protected $_name = 'role_privileges';

    /**
     * Выборка привилегий пользователя на ресурс.
     * @param int $resourceid идентификатор ресурса.
     * @param int $userid идентификатор пользователя.
     * @return mixed массив привилегий пользователя. 
    */
    public function getUserPrivileges($resourceid,$userid){
         $resourceid=(int)$resourceid;
         $userid=(int)$userid;
         
         return $this->fetchAll($this
             ->select()
             ->distinct()
             ->from(array('rp'=>'role_privileges'),array('name'))    
             ->join(array('rl'=>'role'),'rl.id=rp.roleid',array())
             ->join(array('ur'=>'user_roles'),'ur.roleid=rl.id',array())      
             ->where('ur.userid=?',$userid)
             ->where('rp.resourceid=?',$resourceid));
    }
}

