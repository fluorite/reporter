<?php

class Application_Form_ReportItems extends Zend_Form
{
    public function __construct($options=null){
         parent::__construct($options);
         $this->setName('reportitems');
         // Использование стилевого класса bootstrap.
         $this->setAttrib('class','well');
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
         $name=new Zend_Form_Element_Textarea('name');
         $name->setLabel('Название')
             ->setRequired(true)
             ->setAttrib('rows','6')
             ->addFilter('StripTags')
             //->addFilter('StringTrim')
             ->addValidator('NotEmpty',true,
                array('messages' => array('isEmpty' => 'Значение является обязательным и не может быть пустым')));
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
         // Начисляемый за показатель балл.
         $score=new Zend_Form_Element_Text('score');
         $score->setLabel('Балл:');
         // Документ, подтверждающий показатель.
         $certificate=new Zend_Form_Element_Textarea('certificate');
         $certificate->setLabel('Подтверждающий документ:')
             ->setAttrib('rows','6');
         // Примечание к показателю.
         $comment=new Zend_Form_Element_Textarea('comment');
         $comment->setLabel('Примечания:')
             ->setAttrib('rows','6');
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
         $this->addElements(array($name,$isvalue,$score,$certificate,$comment,$submit,$cancel,$id,$parentid,$levelid,$number));
    }
    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
    }


}

