<?php

class Application_Form_Type extends Zend_Form {
    
    public function init() {
    
        $type = new Zend_Form_Element_Text('type', array('class'=>'form-control', 'width'=>'50px'));
        $type->setLabel('Тип')
               ->setRequired(true)
               ->setAttrib('placeholder','Введите тип аппарата')
               ->addFilter('StripTags')
               ->addFilter('StringTrim')
               ->addValidator('Db_NoRecordExists',true, array('table'=>'name','field'=>'name',  'messages' => 'Запись уже существует!'))
               ->addValidator('regex',true, array("/^[A-Za-z1-90 \.]{3,30}$/i", 'messages' => 'Внимание допускается ввод только латинских символов A-Z !'))
               ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $settings['messages']['empty'])))
               ->setDecorators(array('ViewHelper', 'Errors',
                    array(array('data' => 'HtmlTag'), array('class'  => 'test')),
                    array('Label', array('tag' => 'div', 'class' => 'form-control-static')),
                    array('Errors', array('tag' => 'div', 'class' => 'form-control-static')), 
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group')), //завернуть все тегом див
               ));

        
        // Создаём элемент формы Submit c именем = submit
        $submit = new Zend_Form_Element_Submit('submit',array('class'=>'btn btn-lg btn-success btn-block'));
        
        $this->addElements(array($type, $submit));
       
    }
}