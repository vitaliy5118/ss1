<?php

class Application_Form_Documentation extends Zend_Form {

    public function init() {

        // Задаём имя форме
        $this->setName('documentation');
        
        $description = new Zend_Form_Element_Text('description', array('class'=>'form-control'));
        $description->setRequired(true)
                ->setAttrib('placeholder', 'Введите название:')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty')
                ->addValidator('regex', true, array("/^[A-Za-zА-Яа-я1-90 \.\-\А\П\Н]{3,20}$/i", 'messages' => $settings['messages']['error']))
                ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $settings['messages']['empty'])))
                ->removeDecorator('label')
                ->removeDecorator('element')
                ->removeDecorator('errors')
        ;

        $validator = new Zend_Validate_File_Upload();
        $validator->setMessages(array('fileUploadErrorNoFile' => 'Файл не выбран!'));
        
        $file = new Zend_Form_Element_File('file');
        $file->setDestination(DATA_PATH . '/public/data/')
                ->setRequired(true)
                ->addValidator($validator)
        ;



        // Создаём элемент формы Submit c именем = submit
        $submit = new Zend_Form_Element_Submit('submit', array('class' => 'btn btn-default', 'id' => 'data_submit'));

        $this->addElements(array($description, $submit,$file));
    }

}