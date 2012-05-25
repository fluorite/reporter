<?php

class Application_Form_ReportValues extends Zend_Form
{
    public function __construct($options=null){
         parent::__construct($options);
         $this->setName('reportvalues');
         // Использование стилевого класса bootstrap.
         $this->setAttrib('class','well');
         // Идентификатор показателя.
         $itemid=new Zend_Form_Element_Hidden('itemid');
         $itemid->addFilter('Int')
             ->setRequired(true)
             ->addValidator('NotEmpty');
         // Значение показателя.
         $value=new Zend_Form_Element_Text('value');
         $value->setLabel('Значение')
             ->setRequired(true)
             ->addFilter('Int')
             ->addValidator('NotEmpty');
         $submit=new Zend_Form_Element_Submit('submit');
         $submit->setAttrib('id','submitbutton')->setAttrib('class','btn btn-primary')->setLabel('Добавить');
         $this->addElements(array($itemid,$value,$submit));
    }
    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
    }


}

