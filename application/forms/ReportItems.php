<?php

class Application_Form_ReportItems extends Zend_Form
{
    public function __construct($options=null){
         parent::__construct($options);
         $this->setName('reportitems');
         // Идентификатор показателя.
         $id=new Zend_Form_Element_Hidden('id');
         $id->addFilter('Int');
         // Идентификатор родительского покаателя.
         $parentid=new Zend_Form_Element_Hidden('parentid');
         $parentid->addFilter('Int');
         // Идентификатор уровня отчёта.
         $levelid=new Zend_Form_Element_Hidden('levelid');
         $levelid->addFilter('Int');
         // Название показателя.
         $name=new Zend_Form_Element_Text('name');
         $name->setLabel('Название')
             ->setRequired(true)
             ->addFilter('StripTags')
             ->addFilter('StringTrim')
             ->addValidator('NotEmpty');
         // Номер показателя по порядку.
         $number=new Zend_Form_Element_Hidden('number');
         $number->setRequired(true)
             ->addFilter('StripTags')
             ->addFilter('StringTrim')
             ->addValidator('NotEmpty');
         // Наличие значения для показателя.
         $isvalue=new Zend_Form_Element_Checkbox('isvalue');
         $isvalue->setLabel('Значение')
             ->setRequired(true);
         $submit=new Zend_Form_Element_Submit('submit');
         $submit->setAttrib('id','submitbutton')->setLabel('Добавить');
         $this->addElements(array($id,$parentid,$levelid,$number,$name,$isvalue,$submit));
    }
    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
    }


}

