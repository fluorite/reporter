<?php

class Application_Model_DbTable_User extends Zend_Db_Table_Abstract
{

    protected $_name = 'user';

    public function getUser($id){
        // Получаем id как параметр
        $id = (int)$id;

        // Используем метод fetchRow для получения записи из базы.
        // В скобках указываем условие выборки (привычное для вас where)
        $row = $this->fetchRow('id = ' . $id);

        // Если результат пустой, выкидываем исключение
        if(!$row) {
                throw new Exception("Нет записи с id - $id");
        }
        // Возвращаем результат, упакованный в массив
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
    // Метод для добавление новой записи
    public function insertUser($login, $password, $firstname, $middlename, $lastname){
        // Формируем массив вставляемых значений
        $data = array(
                'login' => $login,
                'password' => md5($password),
                'firstname' => $firstname,
                'middlename' => $middlename,
                'lastname' => $lastname,
        );

        // Используем метод insert для вставки записи в базу
        $this->insert($data);
    }
	 
    // Метод для обновления записи
    public  function updateUser($id, $login, $password, $firstname, $middlename, $lastname){
        // Формируем массив значений
        $data = array(
                'login' => $login,
                'password' => md5($password),
                'firstname' => $firstname,
                'middlename' => $middlename,
                'lastname' => $lastname,
        );

        // Используем метод update для обновления записи
        // В скобках указываем условие обновления (привычное для вас where)
        $this->update($data, 'id = ' . (int)$id);
    }

    // Метод для удаления записи
    public function deleteUser($id){
        // В скобках указываем условие удаления (привычное для вас where)
        $this->delete('id = ' . (int)$id);
    }
}

