<?php

class Application_Form_Names extends Zend_Form {
    
    public function init() {
    
        $name = new Zend_Form_Element_Text('name', array('class'=>'form-control', 'width'=>'50px'));
        $name->setLabel('��������')
               ->setRequired(true)
               ->setAttrib('placeholder','������� �������� ��������')
               ->addFilter('StripTags')
               ->addFilter('StringTrim')
               ->addValidator('Db_NoRecordExists',true, array('table'=>'name','field'=>'name',  'messages' => '������ ��� ����������!'))
               ->addValidator('regex',true, array("/^[A-Za-z1-90 \.]{3,30}$/i", 'messages' => '�������� ����������� ���� ������ ��������� �������� A-Z !'))
               ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $settings['messages']['empty'])))
               ->setDecorators(array('ViewHelper', 'Errors',
                    array(array('data' => 'HtmlTag'), array('class'  => 'test')),
                    array('Label', array('tag' => 'div', 'class' => 'form-control-static')),
                    array('Errors', array('tag' => 'div', 'class' => 'form-control-static')), 
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group')), //��������� ��� ����� ���
               ));

        
        // ������ ������� ����� Submit c ������ = submit
        $submit = new Zend_Form_Element_Submit('submit',array('class'=>'btn btn-lg btn-success btn-block'));
        
        $this->addElements(array($name, $submit));
       
    }
}