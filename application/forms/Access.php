<?php

class Application_Form_Access extends Zend_Form {
    
    public function init() {
    
        $username = new Zend_Form_Element_Text('username', array('class'=>'form-control'));
        $username->setLabel('�����')
               ->setRequired(true)
               ->setAttrib('placeholder','username')
               ->addFilter('StripTags')
               ->addFilter('StringTrim')
               ->addValidator('regex',true, array("/^[A-Za-z1-90 \.\-\�\�\�]{3,20}$/i", 'messages' => $settings['messages']['error']))
               ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $settings['messages']['empty'])))
               ->setDecorators(array('ViewHelper', 'Errors',
                    array(array('data' => 'HtmlTag'), array('class'  => 'test')),
                    array('Label', array('tag' => 'div', 'class' => 'form-control-static')),
                    array('Errors', array('tag' => 'div', 'class' => 'form-control-static')), 
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group')), //��������� ��� ����� ���
               ));
        $password = new Zend_Form_Element_Password('password', array('class'=>'form-control'));
        $password->setLabel('������')
               ->setRequired(true)
               ->setAttrib('placeholder','password')
               ->addFilter('StripTags')
               ->addFilter('StringTrim')
               ->addValidator('regex',true, array("/^[A-Za-z1-90 \.\-\�\�\�]{3,20}$/i", 'messages' => $settings['messages']['error']))
               ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $settings['messages']['empty'])))
               ->setDecorators(array('ViewHelper', 'Errors',
                    array(array('data' => 'HtmlTag'), array('class'  => 'test')),
                    array('Label', array('tag' => 'div', 'class' => 'form-control-static')),
                    array('Errors', array('tag' => 'div', 'class' => 'form-control-static')), 
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group')), //��������� ��� ����� ���
               ));
        
        // ������ ������� ����� Submit c ������ = submit
        $submit = new Zend_Form_Element_Submit('submit',array('class'=>'btn btn-lg btn-success btn-block'));
        
        $this->addElements(array($username, $password, $submit));
       
    }
}