<?php

class Application_Form_Repair extends Zend_Form {
 
    public function init() {

        global $settings;
        // Задаём имя форме
        $this->setName('repair');
       
        $claim = new Zend_Form_Element_Text('claim', array('class'=>'form-control'));
        $claim->setLabel('Жалоба')
             ->setRequired(true) //поле обязательное для ввода
             ->addFilter('StripTags') //фильтр убирает теги HTML
            // ->addValidator('between', false, array('min'=> 10, 'max'=>100, 'messages'=>'Шакальство'))
             ->addValidator('NotEmpty', true, array('messages' => $settings['messages']['empty']))
             ->addValidator('regex',true, array("/^[А-Яа-я \.\,]{3,30}$/i", 'messages' => $settings['messages']['error']))
             ->setDecorators(array('ViewHelper', 'Errors',
                    array(array('data' => 'HtmlTag'), array('class'  => 'test')),
                    array('Label', array('tag' => 'div', 'class' => 'form-control-static')),
                    array('Errors', array('tag' => 'div', 'class' => 'form-control-static')),
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group')), //завернуть все тегом див
               ));
        
        $diagnos = new Zend_Form_Element_Text('diagnos', array('class'=>'form-control'));
        $diagnos->setLabel('Диагноз')
             ->setRequired(true) //поле обязательное для ввода
             ->addFilter('StripTags') //фильтр убирает теги HTML
            // ->addValidator('between', false, array('min'=> 10, 'max'=>100, 'messages'=>'Шакальство'))
             ->addValidator('NotEmpty', true, array('messages' => $settings['messages']['empty']))
             ->addValidator('regex',true, array("/^[А-Яа-я \.\,]{3,20}$/i", 'messages' => $settings['messages']['error']))
             ->setDecorators(array('ViewHelper', 'Errors',
                    array(array('data' => 'HtmlTag'), array('class'  => 'test')),
                    array('Label', array('tag' => 'div', 'class' => 'form-control-static')),
                    array('Errors', array('tag' => 'div', 'class' => 'form-control-static')),
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group')), //завернуть все тегом див
               ));
                      
        $work = new Zend_Form_Element_Text('work', array('class'=>'form-control'));
        $work->setLabel('Работы')
             ->setRequired(true) //поле обязательное для ввода
             ->addFilter('StripTags') //фильтр убирает теги HTML
            // ->addValidator('between', false, array('min'=> 10, 'max'=>100, 'messages'=>'Шакальство'))
             ->addValidator('NotEmpty', true, array('messages' => $settings['messages']['empty']))
             ->addValidator('regex',true, array("/^[А-Яа-я \.\,]{3,20}$/i", 'messages' => $settings['messages']['error']))
             ->setDecorators(array('ViewHelper', 'Errors',
                    array(array('data' => 'HtmlTag'), array('class'  => 'test')),
                    array('Label', array('tag' => 'div', 'class' => 'form-control-static')),
                    array('Errors', array('tag' => 'div', 'class' => 'form-control-static')),
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group')), //завернуть все тегом див
               ));
                      
        $spares = new Zend_Form_Element_Text('spares', array('class'=>'form-control'));
        $spares->setLabel('Запчасти')
             ->setRequired(true) //поле обязательное для ввода
             ->addFilter('StripTags') //фильтр убирает теги HTML
             ->addValidator('NotEmpty', true, array('messages' => $settings['messages']['empty']))
             ->addValidator('regex',true, array("/^[А-Яа-я \.\,]{3,20}$/i", 'messages' => $settings['messages']['error']))
             ->setDecorators(array('ViewHelper', 'Errors',
                    array(array('data' => 'HtmlTag'), array('class'  => 'test')),
                    array('Label', array('tag' => 'div', 'class' => 'form-control-static')),
                    array('Errors', array('tag' => 'div', 'class' => 'form-control-static')),
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group')), //завернуть все тегом див
               ));   

        $comments = new Zend_Form_Element_Text('comments', array('class'=>'form-control'));
        $comments->setLabel('Комментарии')
             ->setRequired(true) //поле обязательное для ввода
             ->addFilter('StripTags') //фильтр убирает теги HTML
            // ->addValidator('between', false, array('min'=> 10, 'max'=>100, 'messages'=>'Шакальство'))
             ->addValidator('NotEmpty', true, array('messages' => $settings['messages']['empty']))
             ->addValidator('regex',true, array("/^[А-Яа-я \.\,]{3,20}$/i", 'messages' => $settings['messages']['error']))
             ->setDecorators(array('ViewHelper', 'Errors',
                    array(array('data' => 'HtmlTag'), array('class'  => 'test')),
                    array('Label', array('tag' => 'div', 'class' => 'form-control-static')),
                    array('Errors', array('tag' => 'div', 'class' => 'form-control-static')),
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group')), //завернуть все тегом див
               ));
  
        // Создаём элемент hidden c именем = id
        $number = new Zend_Form_Element_Hidden('number');
        // Указываем, что данные в этом элементе фильтруются как число int
        $number->removeDecorator('label')
               ->removeDecorator('element');

        // Создаём элемент формы Submit c именем = submit
        $submit = new Zend_Form_Element_Submit('submit',array('class'=>'btn btn-default'));
        // Создаём атрибут id = submitbutton
        //$submit->setAttrib('id', 'submitbutton');
        
                // Создаём элемент hidden c именем = id
        $id = new Zend_Form_Element_Hidden('id');
        // Указываем, что данные в этом элементе фильтруются как число int
        $id->addFilter('Int')
           ->removeDecorator('label')
           ->removeDecorator('element');

        // Добавляем все созданные элементы к форме.
        $this->addElements(array($id, $number, $claim,$diagnos, $spares, $work, $comments, $submit));
    }

}

