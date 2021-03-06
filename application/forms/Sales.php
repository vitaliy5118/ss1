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
        
       // ����� ��� �����
       
        $this->setName('sales');
        
        // ������ ������� ����� � text c ������ = number        
        $number = new Zend_Form_Element_Text('number', array('class'=>'form-control'));
        $number->setLabel('�����')
               ->setRequired(true)
               ->setAttrib('placeholder','S/N:')
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
        //�������� �������������� ��� ���������� ��������
        if($_SESSION['edit'] == false){
            $number ->addValidator('Db_NoRecordExists',true, array('table'=>'sales','field'=>'number',  'messages' => $settings['messages']['db']));   
        } else {
            $_SESSION['edit'] = false;         
        }
         
        $name = new Zend_Form_Element_Select('name', array('class'=>'form-control', "multiOptions" => $names_values));
        $name->setLabel('���')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $settings['messages']['empty'])))
                ->setDecorators(array('ViewHelper', 'Errors',
                    array(array('data' => 'HtmlTag'), array('class'  => 'test')),
                    array('Label', array('tag' => 'div', 'class' => 'form-control-static')),
                    array('Errors', array('tag' => 'div', 'class' => 'form-control-static')),
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group')), //��������� ��� ����� ���
               ));
        
                // ������ ������� ����� � text c ������ = number        
        $buyer = new Zend_Form_Element_Text('buyer', array('class'=>'form-control'));
        $buyer->setLabel('����������')
               //->setRequired(true)
               ->setAttrib('placeholder','������� ����������')
               ->addFilter('StripTags')
               ->addFilter('StringTrim')
               ->addValidator('regex',true, array("/^[A-Za-z1-90 \.\-\�\�\�]{3,20}$/i", 'messages' => $settings['messages']['error']))
               //->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $settings['messages']['empty'])))
               ->setDecorators(array('ViewHelper', 'Errors',
                    array(array('data' => 'HtmlTag'), array('class'  => 'test')),
                    array('Label', array('tag' => 'div', 'class' => 'form-control-static')),
                    array('Errors', array('tag' => 'div', 'class' => 'form-control-static')), 
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group')), //��������� ��� ����� ���
               ));

       
        $status = new Zend_Form_Element_Select('status',array('class'=>'form-control',"multiOptions" => $status_values));
        $status->setLabel('������')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
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
        $submit = new Zend_Form_Element_Submit('submit',array('class'=>'btn btn-default'));
        // ��������� ��� ��������� �������� � �����.
        $this->addElements(array($id, $number, $name, $buyer, $status, $submit));
    }

}

