<?php

class Application_Form_Report extends Zend_Form
{
    public function __construct($options=null){
        parent::__construct($options);
        $this->setName('report');
        $id = new Zend_Form_Element_Hidden('id');
        $id->addFilter('Int');
        $name=new Zend_Form_Element_Text('name');
        $name->setLabel('Название')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty');
        $submit=new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id','submitbutton')->setLabel('Добавить');
        $this->addElements(array($id, $name,$submit));
    }
    public function init()
    {
        
    }


}

