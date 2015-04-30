<?php

class Application_Form_Documentation extends Zend_Form {

    public function init() {

        $description = new Zend_Form_Element_Text('description');
        $description->setRequired(true)
                ->setAttrib('placeholder', '������� ��������:')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty')
                ->addValidator('regex', true, array("/^[A-Za-z�-��-�1-90 \.\-\�\�\�]{3,20}$/i", 'messages' => $settings['messages']['error']))
                ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $settings['messages']['empty'])))
                ->removeDecorator('label')
                ->removeDecorator('element')
                ->setDecorators(array('ViewHelper', 'Errors',
                    array(array('data' => 'HtmlTag'), array('class' => 'test')),
                    array('Errors', array('tag' => 'div', 'class' => 'data_description')),
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'data_description')), //��������� ��� ����� ���
                        )
                )
        ;

        $file = new Zend_Form_Element_File('file');
        $file->setDestination(DATA_PATH . '/public/data/')
                ->setRequired(true)
        ;



        // ������ ������� ����� Submit c ������ = submit
        $submit = new Zend_Form_Element_Submit('submit', array('class' => 'btn btn-default', 'id' => 'data_submit'));

        $this->addElements(array($description, $file, $submit));

        $this->getElement('file')->setDecorators(
                        array('File', 'Errors',
                            array('Errors', array('tag' => 'div', 'class' => 'data_file')),
                            array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'data_file'))
                        )
                )->removeDecorator('label')
                ->removeDecorator('element');
    }

}