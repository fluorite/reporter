<?php

class Application_Model_DbTable_User extends Zend_Db_Table_Abstract
{

    protected $_name = 'user';

    public function getUser($id){
        $id=(int)$id;
        $row=$this->fetchRow('id='.$id);
        if(!$row)
            throw new Exception("Пользователь [$id] отсутствует");
        return $row->toArray();
    }
    /**
     * Выборка подчиненных пользователей.
     * @param int $id идентификатор пользователя.
     * @return mixed массив подчиненных пользователей. 
    */
    public function getChildUsers($id){
        $id=(int)$id;
        // Выборка подчиненных пользователей.
        return $this->fetchAll($this->select()->from(array('user'),array('id','firstname','middlename','lastname')));
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

