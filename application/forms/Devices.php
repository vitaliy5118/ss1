<?php

class Application_Form_Devices extends Zend_Form {
    
    public function init() {
        
        global $settings;
        
        $names  = new Application_Model_DbTable_Setup();
        $names->setTableName('name');
        $names_values = $names->getValues();

        $types  = new Application_Model_DbTable_Setup();
        $types->setTableName('type');
        $types_values = $types->getValues();
        
        $status  = new Application_Model_DbTable_Setup();
        $status->setTableName('status');
        $status_values = $status->getValues();
        
        $users  = new Application_Model_DbTable_Setup();
        $users->setTableName('user');
        $users_values = $users->getValues();
        
        $owners  = new Application_Model_DbTable_Setup();
        $owners->setTableName('owner');
        $owners_values = $owners->getValues();
        
        // Задаём имя форме
       
        $this->setName('devices');
        
        // Создаём элемент формы – text c именем = number        
        $number = new Zend_Form_Element_Text('number', array('class'=>'form-control'));
        $number->setLabel('Номер')
               ->setRequired(true)
               ->setAttrib('placeholder','S/N:')
               ->addFilter('StripTags')
               ->addFilter('StringTrim')
               ->addValidator('regex',true, array("/^[A-Za-z1-90 \.\-]{3,20}$/i", 'messages' => $settings['messages']['error']))
               ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $settings['messages']['empty'])))
               ->setDecorators(array('ViewHelper', 'Errors',
                    array(array('data' => 'HtmlTag'), array('class'  => 'test')),
                    array('Label', array('tag' => 'div', 'class' => 'form-control-static')),
                    array('Errors', array('tag' => 'div', 'class' => 'form-control-static')), 
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group')), //завернуть все тегом див
               ));
        //проверка редактирования или добавления елемента
        if($_SESSION['edit'] == false){
            $number ->addValidator('Db_NoRecordExists',true, array('table'=>'devices','field'=>'number',  'messages' => $settings['messages']['db']));   
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
 
        $type = new Zend_Form_Element_Select('type', array('class'=>'form-control', "multiOptions" => $types_values));
        $type->setLabel('Группа аппаратов')
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

        $owner = new Zend_Form_Element_Select('owner', array('class'=>'form-control',"multiOptions" => $owners_values));
        $owner->setLabel('Принадлежность')
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

        $user = new Zend_Form_Element_Select('user', array('class'=>'form-control',"multiOptions" => $users_values));
        $user->setLabel('Территория')
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
        
// Создаём элемент формы – ТОРГОВАЯ ТОЧКА       
        $city = new Zend_Form_Element_Text('city', array('class'=>'form-control'));
        $city->setLabel('Город')
               ->setRequired(true)
               ->setAttrib('placeholder','пример: Киев')
               ->addFilter('StripTags')
               ->addFilter('StringTrim')
               ->addValidator('regex',true, array("/^[А-Яа-яA-Za-z1-90 \.\-\,\"\№]{3,200}$/i", 'messages' => $settings['messages']['error']))
               ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $settings['messages']['empty'])))
               ->setDecorators(array('ViewHelper', 'Errors',
                    array(array('data' => 'HtmlTag'), array('class'  => 'test')),
                    array('Label', array('tag' => 'div', 'class' => 'form-control-static')),
                    array('Errors', array('tag' => 'div', 'class' => 'form-control-static')), 
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group')), //завернуть все тегом див
               ));
 
        $tt_name = new Zend_Form_Element_Text('tt_name', array('class'=>'form-control'));
        $tt_name->setLabel('Название "Торговой точки"')
               ->setRequired(true)
               ->setAttrib('placeholder','пример: магазин Novus')
               ->addFilter('StripTags')
               ->addFilter('StringTrim')
               ->addValidator('regex',true, array("/^[А-Яа-яA-Za-z1-90 \.\-\,\"\№\)\(]{3,200}$/i", 'messages' => $settings['messages']['error']))
               ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $settings['messages']['empty'])))
               ->setDecorators(array('ViewHelper', 'Errors',
                    array(array('data' => 'HtmlTag'), array('class'  => 'test')),
                    array('Label', array('tag' => 'div', 'class' => 'form-control-static')),
                    array('Errors', array('tag' => 'div', 'class' => 'form-control-static')), 
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group')), //завернуть все тегом див
               ));
 
        $tt_user = new Zend_Form_Element_Text('tt_user', array('class'=>'form-control'));
        $tt_user->setLabel('Контактные данные')
               ->setRequired(true)
               ->setAttrib('placeholder','пример: директор Олег Олегович')
               ->addFilter('StripTags')
               ->addFilter('StringTrim')
               ->addValidator('regex',true, array("/^[А-Яа-яA-Za-z1-90 \.\-\,\"\№\)\(]{3,200}$/i", 'messages' => $settings['messages']['error']))
               ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $settings['messages']['empty'])))
               ->setDecorators(array('ViewHelper', 'Errors',
                    array(array('data' => 'HtmlTag'), array('class'  => 'test')),
                    array('Label', array('tag' => 'div', 'class' => 'form-control-static')),
                    array('Errors', array('tag' => 'div', 'class' => 'form-control-static')), 
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group')), //завернуть все тегом див
               ));
 
        $tt_phone = new Zend_Form_Element_Text('tt_phone', array('class'=>'form-control'));
        $tt_phone->setLabel('Номер договора')
               ->setRequired(true)
               ->setAttrib('placeholder','пример: 1234567')
               ->addFilter('StripTags')
               ->addFilter('StringTrim')
               ->addValidator('regex',true, array("/^[А-Яа-яA-Za-z1-90 \.\-\,\"\№\+\(\)]{3,200}$/i", 'messages' => $settings['messages']['error']))
               ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $settings['messages']['empty'])))
               ->setDecorators(array('ViewHelper', 'Errors',
                    array(array('data' => 'HtmlTag'), array('class'  => 'test')),
                    array('Label', array('tag' => 'div', 'class' => 'form-control-static')),
                    array('Errors', array('tag' => 'div', 'class' => 'form-control-static')), 
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group')), //завернуть все тегом див
               ));

        $adress = new Zend_Form_Element_Text('adress', array('class'=>'form-control'));
        $adress->setLabel('Адрес "Торговой точки"')
               ->setRequired(true)
               ->setAttrib('placeholder','привер: ул.Зоологическая 23')
               ->addFilter('StripTags')
               ->addFilter('StringTrim')
               ->addValidator('regex',true, array("/^[А-Яа-яA-Za-z1-90 \.\-\,\"\№\)\(]{3,200}$/i", 'messages' => $settings['messages']['error']))
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
        $this->addElements(array($id, $number, $name, $type, $owner, $user, $status, $city, $adress, $tt_name, $tt_user, $tt_phone, $submit));
    }

}

