<?php

class Application_Form_ReportLevels extends Zend_Form
{
    public function __construct($options=null){
         parent::__construct($options);
         $this->setName('reportlevels');
         // Использование стилевого класса bootstrap.
         $this->setAttrib('class','well');
         // Идентификатор уровня отчёта.
         $id = new Zend_Form_Element_Hidden('id');
         $id->addFilter('Int');
         // Идентификатор отчёта.
         $reportid = new Zend_Form_Element_Hidden('reportid');
         $reportid->addFilter('Int');
         // Номер уровня (1..255).
         $number=new Zend_Form_Element_Hidden('number');
         $number->addFilter('Int');
         // Название уровня.
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
         $this->addElements(array($name,$submit,$cancel,$id,$reportid,$number));
    }
    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
    }


}

