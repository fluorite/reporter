<?php

class Application_Form_Report extends Zend_Form
{
    public function __construct($options=null){
        parent::__construct($options);
        $this->setName('report');
        // Использование стилевого класса bootstrap.
         $this->setAttrib('class','well');
        $id = new Zend_Form_Element_Hidden('id');
        $id->addFilter('Int');
        $name=new Zend_Form_Element_Text('name');
        $name->setLabel('Название')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty',true,
                array('messages' => array('isEmpty' => 'Значение является обязательным и не может быть пустым')));
        $submit=new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id','submitbutton')
            ->setAttrib('class','btn btn-primary')
            ->setLabel('Добавить')
            ->setDecorators(array('ViewHelper'));
        $cancel=new Zend_Form_Element_Submit('cancel');
        $cancel->setAttrib('id','cancelbutton')
            ->setAttrib('class','btn btn-warning')
            ->setLabel('Отменить')
            ->setDecorators(array('ViewHelper'));
        $this->addElements(array($name,$submit,$cancel,$id));
    }
    public function init()
    {
        
    }


}

