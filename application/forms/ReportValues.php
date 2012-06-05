<?php

class Application_Form_ReportValues extends Zend_Form
{
    public function __construct($options=null){
         parent::__construct($options);
         $this->setName('reportvalues');
         // Использование стилевого класса bootstrap.
         $this->setAttrib('class','well');
         // Номер и название показателя.
         $title=new Zend_Form_Element_Text('title');
         $title->setLabel('Номер и название показателя:');
         $title->helper='formNote';
         // Примечание к показателю.
         $comment=new Zend_Form_Element_Text('comment');
         $comment->setLabel('Примечание:');
         $comment->helper='formNote';
         // Идентификатор показателя.
         $itemid=new Zend_Form_Element_Hidden('itemid');
         $itemid->setRequired(true)
             ->addValidator('NotEmpty');
         // Значение показателя.
         $value=new Zend_Form_Element_Text('value');
         $value->setLabel('Значение показателя:')
             ->setRequired(true)
             ->addValidator('NotEmpty',true,
                array('messages' => array('isEmpty' => 'Значение является обязательным и не может быть пустым')))
             ->addValidator('GreaterThan',true,
                array('min'=>'0','messages' => array('notGreaterThan' => 'Значение показателя должно быть положительным числом')));
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
         $this->addElements(array($title,$comment,$itemid,$value,$submit,$cancel));
    }
    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
    }


}

