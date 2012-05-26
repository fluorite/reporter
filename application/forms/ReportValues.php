<?php

class Application_Form_ReportValues extends Zend_Form
{
    public function __construct($options=null){
         parent::__construct($options);
         $this->setName('reportvalues');
         // Использование стилевого класса bootstrap.
         $this->setAttrib('class','well');
         // Название показателя.
         $title=new Zend_Form_Element_Text('title');
         $title->setDecorators(array('ViewHelper'));
         $title->helper='formNote';
         // Идентификатор показателя.
         $itemid=new Zend_Form_Element_Hidden('itemid');
         $itemid->setRequired(true)
             ->addValidator('NotEmpty');
         // Значение показателя.
         $value=new Zend_Form_Element_Text('value');
         $value->setLabel('Значение')
             ->setRequired(true)
             ->addValidator('NotEmpty');
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
         $this->addElements(array($title,$itemid,$value,$submit,$cancel));
    }
    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
    }


}

