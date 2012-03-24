<?php

class Application_Form_User extends Zend_Form
{

    public function init()
    {
        // Задаём имя форме
        $this->setName('user');

        // Идентификатор пользователя.
        $id = new Zend_Form_Element_Hidden('id');
        $id->addFilter('Int');
        $login=new Zend_Form_Element_Text('login');
        $login->setLabel('Логин')
             ->setRequired(true)
             ->addFilter('StripTags')
             ->addFilter('StringTrim')
             ->addValidator('NotEmpty');
        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('Пароль')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty');
        $firstname = new Zend_Form_Element_Text('firstname');
        $firstname->setLabel('Имя')
             ->setRequired(true)
             ->addFilter('StripTags')
             ->addFilter('StringTrim')
             ->addValidator('NotEmpty');
        $middlename = new Zend_Form_Element_Text('middlename');
        $middlename->setLabel('Отчество')
             ->setRequired(true)
             ->addFilter('StripTags')
             ->addFilter('StringTrim')
             ->addValidator('NotEmpty');
        $lastname = new Zend_Form_Element_Text('lastname');
        $lastname->setLabel('Фамилия')
             ->setRequired(true)
             ->addFilter('StripTags')
             ->addFilter('StringTrim')
             ->addValidator('NotEmpty');       
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton');
        $this->addElements(array($id, $login, $password, $firstname, $middlename, $lastname, $submit));
    }


}

