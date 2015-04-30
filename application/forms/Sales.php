<?php

class Application_Form_Sales extends Zend_Form {
    
    public function init() {
        
        global $settings;
        
        $names  = new Application_Model_DbTable_Setup();
        $names->setTableName('name');
        $names_values = $names->getValues();
        
        $status  = new Application_Model_DbTable_Setup();
        $status->setTableName('status');
        $status_values = $status->getValues();
        
       // Задаём имя форме
       
        $this->setName('sales');
        
        // Создаём элемент формы – text c именем = number        
        $number = new Zend_Form_Element_Text('number', array('class'=>'form-control'));
        $number->setLabel('Номер')
               ->setRequired(true)
               ->setAttrib('placeholder','S/N:')
               ->addFilter('StripTags')
               ->addFilter('StringTrim')
               ->addValidator('regex',true, array("/^[A-Za-z1-90 \.\-\А\П\Н]{3,20}$/i", 'messages' => $settings['messages']['error']))
               ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $settings['messages']['empty'])))
               ->setDecorators(array('ViewHelper', 'Errors',
                    array(array('data' => 'HtmlTag'), array('class'  => 'test')),
                    array('Label', array('tag' => 'div', 'class' => 'form-control-static')),
                    array('Errors', array('tag' => 'div', 'class' => 'form-control-static')), 
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group')), //завернуть все тегом див
               ));
        //проверка редактирования или добавления елемента
        if($_SESSION['edit'] == false){
            $number ->addValidator('db_norecordexists',true, array('table'=>'devices','field'=>'number',  'messages' => $settings['messages']['db']));   
        } else {
            $_SESSION['edit'] = false;         
        }
         
        $name = new Zend_Form_Element_Select('name', array('class'=>'form-control', "multiOptions" => $names_values));
        $name->setLabel('Имя')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $settings['messages']['empty'])))
                ->setDecorators(array('ViewHelper', 'Errors',
                    array(array('data' => 'HtmlTag'), array('class'  => 'test')),
                    array('Label', array('tag' => 'div', 'class' => 'form-control-static')),
                    array('Errors', array('tag' => 'div', 'class' => 'form-control-static')),
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group')), //завернуть все тегом див
               ));
        
                // Создаём элемент формы – text c именем = number        
        $buyer = new Zend_Form_Element_Text('buyer', array('class'=>'form-control'));
        $buyer->setLabel('Покупатель')
               //->setRequired(true)
               ->setAttrib('placeholder','Введите покупателя')
               ->addFilter('StripTags')
               ->addFilter('StringTrim')
               ->addValidator('regex',true, array("/^[A-Za-z1-90 \.\-\А\П\Н]{3,20}$/i", 'messages' => $settings['messages']['error']))
               //->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $settings['messages']['empty'])))
               ->setDecorators(array('ViewHelper', 'Errors',
                    array(array('data' => 'HtmlTag'), array('class'  => 'test')),
                    array('Label', array('tag' => 'div', 'class' => 'form-control-static')),
                    array('Errors', array('tag' => 'div', 'class' => 'form-control-static')), 
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group')), //завернуть все тегом див
               ));

       
        $status = new Zend_Form_Element_Select('status',array('class'=>'form-control',"multiOptions" => $status_values));
        $status->setLabel('Статус')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
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
        $submit = new Zend_Form_Element_Submit('submit',array('class'=>'btn btn-default'));
        // Добавляем все созданные элементы к форме.
        $this->addElements(array($id, $number, $name, $buyer, $status, $submit));
    }

}

