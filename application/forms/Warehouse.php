<?php

class Application_Form_Warehouse extends Zend_Form {

    public function init() {
        
        // Задаём имя форме
        $this->setName('warehouse');
        // Создаём переменную, которая будет хранить сообщение валидации
        $isEmptyMessage = 'Значение является обязательным и не может быть пустым';
        // Создаём элемент формы – text c именем = number 
        $image = new Zend_Form_Element_File('image');
        $image->setLabel('Загрузить фотографию')
              ->setDestination(DATA_PATH . '/public/img/')
              //->setRequired(true)
              //->addValidator('IsImage')
              ->addValidator('Size',false,'204800')
              ->addValidator('Extension',true,'png,jpg,gif')
              ->addValidator('ImageSize',false,array(
                    'minwidth' => 50
                    ,'minheight' => 50
                    ,'maxwidth' => 5000
                    ,'maxheight' => 5000
                ));
        
        $serial = new Zend_Form_Element_Text('serial', array('class'=>'form-control'));
        $serial->setLabel('Серийный номер')
                ->setAttrib('placeholder', 'S\N:')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $isEmptyMessage)))
                ->addValidator('regex',true, array("/^[а-яА-Яa-zA-Z0-9 \. \-]{3,200}$/i", 'messages' => 'Не верный формат ввода'))
                ->setDecorators(array('ViewHelper', 'Errors',
                    array(array('data' => 'HtmlTag'), array('class'  => 'test')),
                    array('Label', array('tag' => 'div', 'class' => 'form-control-static')),
                    array('Errors', array('tag' => 'div', 'class' => 'form-control-static')),
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group')), //завернуть все тегом див
             ));         
        
        $name = new Zend_Form_Element_Text('name', array('class'=>'form-control'));
        $name->setLabel('Название')
             ->setRequired(true) //поле обязательное для ввода
             ->addFilter('StripTags') //фильтр убирает теги HTML
             ->addValidator('NotEmpty', true, array('messages' => 'Йо на, не может быть пустым'))
             ->addValidator('regex',true, array("/^[а-яА-Яa-zA-Z0-9 \. \- \s ]{3,50}$/i", 'messages' => 'Не верный формат ввода'))
             ->setDecorators(array('ViewHelper', 'Errors',
                    array(array('data' => 'HtmlTag'), array('class'  => 'test')),
                    array('Label', array('tag' => 'div', 'class' => 'form-control-static')),
                    array('Errors', array('tag' => 'div', 'class' => 'form-control-static')), 
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group')), //завернуть все тегом див
               ));
               
        $type = new Zend_Form_Element_Text('type', array('class'=>'form-control'));
        $type->setLabel('Тип')
             ->setAttrib('placeholder', 'Viena,Royal,All')
             ->setRequired(true) //поле обязательное для ввода
             ->addFilter('StripTags') //фильтр убирает теги HTML
             ->addValidator('NotEmpty', true, array('messages' => 'Йо на, не может быть пустым'))
             ->addValidator('regex',true, array("/^[a-zA-Z0-9 \- \s]{3,20}$/i", 'messages' => 'Не верный формат ввода'))
             ->setDecorators(array('ViewHelper', 'Errors',
                    array(array('data' => 'HtmlTag'), array('class'  => 'test')),
                    array('Label', array('tag' => 'div', 'class' => 'form-control-static')),
                    array('Errors', array('tag' => 'div', 'class' => 'form-control-static')), 
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group')), //завернуть все тегом див
               ));
        
        $remain = new Zend_Form_Element_Text('remain', array('class'=>'form-control'));
        $remain->setLabel('Остаток')
             ->setAttrib('placeholder', 'кол-во')
             ->setRequired(true) //поле обязательное для ввода
             ->addFilter('StripTags') //фильтр убирает теги HTML
             ->addValidator('NotEmpty', true, array('messages' => 'Йо на, не может быть пустым'))
             ->addValidator('regex',true, array("/^[0-9 \-]{1,20}$/i", 'messages' => 'Не верный формат ввода'))
             ->setDecorators(array('ViewHelper', 'Errors',
                    array(array('data' => 'HtmlTag'), array('class'  => 'test')),
                    array('Label', array('tag' => 'div', 'class' => 'form-control-static')),
                    array('Errors', array('tag' => 'div', 'class' => 'form-control-static')), 
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group')), //завернуть все тегом див
               ));
 
        $price = new Zend_Form_Element_Text('price', array('class'=>'form-control'));
        $price->setLabel('Цена')
             ->setAttrib('placeholder', 'введите цену в грн')
             ->setRequired(true) //поле обязательное для ввода
             ->addFilter('StripTags') //фильтр убирает теги HTML
             ->addValidator('NotEmpty', true, array('messages' => 'Йо на, не может быть пустым'))
             ->addValidator('regex',true, array("/^[0-9]{0,20}$/i", 'messages' => 'Не верный формат ввода, для ввода доступны только цифры'))
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
        
                // Создаём элемент hidden c именем = id
        $path = new Zend_Form_Element_Hidden('path');
        // Указываем, что данные в этом элементе фильтруются как число int
        $path ->removeDecorator('label')
           ->removeDecorator('element');

        // Создаём элемент формы Submit c именем = submit
        $submit = new Zend_Form_Element_Submit('submit',array('class'=>'btn btn-default'));

        // Добавляем все созданные элементы к форме.
        $this->addElements(array($id, $serial, $name, $type, $remain, $price, $image, $submit, $path));
    }

}

