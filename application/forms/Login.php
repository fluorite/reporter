<?php

class Application_Form_Login extends Zend_Form
{

    public function init(){
        // Включение декларативного определения компонент Dojo.
        //Zend_Dojo_View_Helper_Dojo::setUseDeclarative();
        $this->setName('user');
        $this->setMethod('post');
        // Использование стилевого класса bootstrap.
        $this->setAttrib('class','well');
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
        // Добавление поля ввода учетной записи пользователя.
        /*$this->addElement('ValidationTextBox','login',
            array('label'=>'Пользователь',
                  'required'=>true,
                  'trim'=>true,
                  'regExp'=>'[a-z0-9]{3,}',
                  'invalidMessage' =>'В имени разрешено использовать только латиницу и цифры.'
            )
        );*/
        // Добавление поля ввода пароля пользователя.
        /*$this->addElement('PasswordTextBox','password',
            array('label'=>'Пароль',
                  'required'=>true,
                  'trim'=>true,
                  'regExp'=>'[a-z0-9]{3,}',
                  'invalidMessage' =>'В пароле разрешено использовать только латиницу и цифры.'
            )
        );*/
        $submit=new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id','submitbutton')->setAttrib('class','btn btn-primary')->setLabel('Войти');
        $this->addElements(array($login,$password,$submit));
    }
}

