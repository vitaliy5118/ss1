<?php

class Application_Form_Warehouse extends Zend_Form {

    public function init() {
        
        // Задаём имя форме
        $this->setName('warehouse');
       
        // Создаём переменную, которая будет хранить сообщение валидации
        $isEmptyMessage = 'Значение является обязательным и не может быть пустым';
        // Создаём элемент формы – text c именем = number        
        $serial = new Zend_Form_Element_Text('serial');
        $serial->setLabel('Серийный номер')
                ->setAttrib('placeholder', 'S\N:')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $isEmptyMessage)))
                ->addValidator('regex',true, array("/^[а-яА-Яa-zA-Z0-9 \. \-]{3,20}$/i", 'messages' => 'Не верный формат ввода'));
               
        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('Название')
             ->setRequired(true) //поле обязательное для ввода
             ->addFilter('StripTags') //фильтр убирает теги HTML
             ->addValidator('NotEmpty', true, array('messages' => 'Йо на, не может быть пустым'))
             ->addValidator('regex',true, array("/^[а-яА-Яa-zA-Z0-9 \. \- \s ]{3,30}$/i", 'messages' => 'Не верный формат ввода'));
               
        $type = new Zend_Form_Element_Text('type');
        $type->setLabel('Тип')
             ->setAttrib('placeholder', 'Viena,Royal,All')
             ->setRequired(true) //поле обязательное для ввода
             ->addFilter('StripTags') //фильтр убирает теги HTML
             ->addValidator('NotEmpty', true, array('messages' => 'Йо на, не может быть пустым'))
             ->addValidator('regex',true, array("/^[a-zA-Z0-9 \- \s]{3,20}$/i", 'messages' => 'Не верный формат ввода'));
        
        $remain = new Zend_Form_Element_Text('remain');
        $remain->setLabel('Остаток')
             ->setAttrib('placeholder', 'кол-во')
             ->setRequired(true) //поле обязательное для ввода
             ->addFilter('StripTags') //фильтр убирает теги HTML
             ->addValidator('NotEmpty', true, array('messages' => 'Йо на, не может быть пустым'))
             ->addValidator('regex',true, array("/^[0-9 \-]{1,20}$/i", 'messages' => 'Не верный формат ввода'));
        
        $lastadding = new Zend_Form_Element_Text('lastadding');
        $lastadding->setLabel('Последнее добавление')
             ->setRequired(true) //поле обязательное для ввода
             ->addFilter('StripTags') //фильтр убирает теги HTML
             ->setAttrib('placeholder', 'xx.xx.20xx')
             ->addValidator('NotEmpty', true, array('messages' => 'Йо на, не может быть пустым'))
             ->addValidator('regex',true, array("/^[0-9 \. \-]{3,20}$/i", 'messages' => 'Не верный формат ввода'));
        
        

        // Создаём элемент hidden c именем = id
        $id = new Zend_Form_Element_Hidden('id');
        // Указываем, что данные в этом элементе фильтруются как число int
        $id->addFilter('Int')
           ->removeDecorator('label')
           ->removeDecorator('element');

        // Создаём элемент формы Submit c именем = submit
        $submit = new Zend_Form_Element_Submit('submit');
        // Создаём атрибут id = submitbutton
        //$submit->setAttrib('id', 'submitbutton');

        // Добавляем все созданные элементы к форме.
        $this->addElements(array($id, $serial, $name, $type, $remain, $lastadding, $submit));
    }

}

