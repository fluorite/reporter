<?php

class Application_Model_DbTable_User extends Zend_Db_Table_Abstract
{

    protected $_name = 'user';
 
    /**
     * Выборка требуемого пользователя.
     * @param int $userid идентификатор пользователя (если 0 или отсутствует, то текущий пользователь).
     * @return mixed значения полей пользователя. 
    */
    public function getUser($id=0){
        $id=(int)$id;
        if($id == 0)
            // Идентификатор текущего пользователя.
            $id=Zend_Auth::getInstance()->getIdentity()->id;
        $row=$this->fetchRow('id='.$id);
        if(!$row)
            throw new Exception("Пользователь [$id] отсутствует");
        return $row->toArray();
    }
    /**
     * Выборка пользователей подразделения.
     * @param int $departmentid идентификатор подразделения.
     * @return mixed массив пользователей подразделения. 
    */
    public function getDepartment($departmentid){
        $departmentid=(int)$departmentid;
        // Выборка пользователей подразделения.
        return $this->fetchAll($this->
            select()->
            from(array('user'),array('id','firstname','middlename','lastname'))->
            where('departmentid=?',$departmentid)->
            order(array('lastname asc','firstname asc','middlename asc')));
    }	 
    public function insertUser($login,$password,$firstname,$middlename,$lastname){
        $data=array(
            'login'=>$login,
            'password'=>$password,
            'firstname'=>$firstname,
            'middlename'=>$middlename,
            'lastname'=>$lastname,
        );
        $this->insert($data);
    }
    public function updateUser($id,$login,$password,$firstname,$middlename,$lastname){
        $data=array(
            'login'=>$login,
            'password'=>$password,
            'firstname'=>$firstname,
            'middlename'=>$middlename,
            'lastname'=>$lastname,
        );
        $this->update($data,'id='.(int)$id);
    }
    public function deleteUser($id){
        $this->delete('id='.(int)$id);
    }
}

