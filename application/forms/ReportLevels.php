<?php

class Application_Form_ReportLevels extends Zend_Form
{
    public function __construct($options=null){
         parent::__construct($options);
         $this->setName('reportlevels');
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
             ->addValidator('NotEmpty');
         $submit=new Zend_Form_Element_Submit('submit');
         $submit->setAttrib('id','submitbutton')->setLabel('Добавить');
         $this->addElements(array($id,$reportid,$number,$name,$submit));
    }
    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
    }


}

