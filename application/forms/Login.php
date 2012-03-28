<?php

class Application_Form_Login extends Zend_Form
{

    public function init(){
        $this->setName('login');
        $this->setMethod('post');
        $login=new Zend_Form_Element_Text('login');
        $login->setLabel('Пользователь')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty');
        $password=new Zend_Form_Element_Password('password');
        $password->setLabel('Пароль')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty');
        $submit=new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id','submitbutton')->setLabel('Войти');
        $this->addElements(array($login,$password,$submit));     
    }


}

