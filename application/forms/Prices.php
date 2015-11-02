<?php

class Application_Form_Prices extends Zend_Form {
    
    public function init() {
    
        $name = new Zend_Form_Element_Text('name', array('class'=>'form-control', 'width'=>'50px'));
        $name->setLabel('�������� ������')
               ->setRequired(true)
               ->setAttrib('placeholder','������� �������� ������')
               ->addFilter('StripTags')
               ->addFilter('StringTrim')
               ->addValidator('Db_NoRecordExists',true, array('table'=>'name','field'=>'name',  'messages' => '������ ��� ����������!'))
               ->addValidator('regex',true, array("/^[A-Za-z�-��-�1-90 \.\,\-\;]{0,300}$/i", 'messages' => '�������� ����������� ���� ������ ������������ ��������'))
               ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $settings['messages']['empty'])))
               ->setDecorators(array('ViewHelper', 'Errors',
                    array(array('data' => 'HtmlTag'), array('class'  => 'test')),
                    array('Label', array('tag' => 'div', 'class' => 'form-control-static')),
                    array('Errors', array('tag' => 'div', 'class' => 'form-control-static')), 
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group')), //��������� ��� ����� ���
               ));
        $price = new Zend_Form_Element_Text('price', array('class'=>'form-control', 'width'=>'50px'));
        $price->setLabel('���� ������ (���)')
               ->setRequired(true)
               ->setAttrib('placeholder','������� ���� ������ (���)')
               ->addFilter('StripTags')
               ->addFilter('StringTrim')
               ->addValidator('Db_NoRecordExists',true, array('table'=>'name','field'=>'name',  'messages' => '������ ��� ����������!'))
               ->addValidator('regex',true, array("/^[1-90\.]{0,10}$/i", 'messages' => '�������� ����������� ���� ������ ��������� �������� A-Z !'))
               ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $settings['messages']['empty'])))
               ->setDecorators(array('ViewHelper', 'Errors',
                    array(array('data' => 'HtmlTag'), array('class'  => 'test')),
                    array('Label', array('tag' => 'div', 'class' => 'form-control-static')),
                    array('Errors', array('tag' => 'div', 'class' => 'form-control-static')), 
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group')), //��������� ��� ����� ���
               ));

        // ������ ������� hidden c ������ = id
        $id = new Zend_Form_Element_Hidden('id');
        // ���������, ��� ������ � ���� �������� ����������� ��� ����� int
        $id->addFilter('Int')
           ->removeDecorator('label')
           ->removeDecorator('element');
        // ������ ������� ����� Submit c ������ = submit
        $submit = new Zend_Form_Element_Submit('submit',array('class'=>'btn btn-lg btn-success btn-block'));
        
        $this->addElements(array($id, $name, $price, $submit));
       
    }
}