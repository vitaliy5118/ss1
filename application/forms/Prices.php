<?php

class Application_Form_Prices extends Zend_Form {
    
    public function init() {
    
        $name = new Zend_Form_Element_Text('name', array('class'=>'form-control', 'width'=>'50px'));
        $name->setLabel('Название услуги')
               ->setRequired(true)
               ->setAttrib('placeholder','Введите название услуги')
               ->addFilter('StripTags')
               ->addFilter('StringTrim')
               ->addValidator('Db_NoRecordExists',true, array('table'=>'name','field'=>'name',  'messages' => 'Запись уже существует!'))
               ->addValidator('regex',true, array("/^[A-Za-zА-Яа-я1-90 \.\,\-\;]{0,300}$/i", 'messages' => 'Внимание допускается ввод только определенных символов'))
               ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $settings['messages']['empty'])))
               ->setDecorators(array('ViewHelper', 'Errors',
                    array(array('data' => 'HtmlTag'), array('class'  => 'test')),
                    array('Label', array('tag' => 'div', 'class' => 'form-control-static')),
                    array('Errors', array('tag' => 'div', 'class' => 'form-control-static')), 
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group')), //завернуть все тегом див
               ));
        $price = new Zend_Form_Element_Text('price', array('class'=>'form-control', 'width'=>'50px'));
        $price->setLabel('Цена услуги (грн)')
               ->setRequired(true)
               ->setAttrib('placeholder','Введите цену услуги (грн)')
               ->addFilter('StripTags')
               ->addFilter('StringTrim')
               ->addValidator('Db_NoRecordExists',true, array('table'=>'name','field'=>'name',  'messages' => 'Запись уже существует!'))
               ->addValidator('regex',true, array("/^[1-90\.]{0,10}$/i", 'messages' => 'Внимание допускается ввод только латинских символов A-Z !'))
               ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $settings['messages']['empty'])))
               ->setDecorators(array('ViewHelper', 'Errors',
                    array(array('data' => 'HtmlTag'), array('class'  => 'test')),
                    array('Label', array('tag' => 'div', 'class' => 'form-control-static')),
                    array('Errors', array('tag' => 'div', 'class' => 'form-control-static')), 
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group')), //завернуть все тегом див
               ));

        // Создаём элемент hidden c именем = id
        $id = new Zend_Form_Element_Hidden('id');
        // Указываем, что данные в этом элементе фильтруются как число int
        $id->addFilter('Int')
           ->removeDecorator('label')
           ->removeDecorator('element');
        // Создаём элемент формы Submit c именем = submit
        $submit = new Zend_Form_Element_Submit('submit',array('class'=>'btn btn-lg btn-success btn-block'));
        
        $this->addElements(array($id, $name, $price, $submit));
       
    }
}