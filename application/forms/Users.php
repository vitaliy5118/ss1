<?php

class Application_Form_Users extends Zend_Form {
    
    public function init() {
        
        $user = new Zend_Form_Element_Text('user', array('class'=>'form-control', 'width'=>'50px'));
        $user->setLabel('Территория')
               ->setRequired(true)
               ->setAttrib('placeholder','Введите территорию использования')
               ->addFilter('StripTags')
               ->addFilter('StringTrim')
               ->addValidator('Db_NoRecordExists',true, array('table'=>'user','field'=>'user',  'messages' => 'Запись уже существует!'))
               ->addValidator('regex',true, array("/^[A-Za-zА-Яа-я1-90 \.\-]{3,20}$/i", 'messages' => $settings['messages']['error']))
               ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $settings['messages']['empty'])))
               ->setDecorators(array('ViewHelper', 'Errors',
                    array(array('data' => 'HtmlTag'), array('class'  => 'test')),
                    array('Label', array('tag' => 'div', 'class' => 'form-control-static')),
                    array('Errors', array('tag' => 'div', 'class' => 'form-control-static')), 
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group')), //завернуть все тегом див
               ));

        
        // Создаём элемент формы Submit c именем = submit
        $submit = new Zend_Form_Element_Submit('submit',array('class'=>'btn btn-lg btn-success btn-block'));
        
        $this->addElements(array($user, $submit));
       
    }
}