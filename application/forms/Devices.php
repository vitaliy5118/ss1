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
        
        // ����� ��� �����
       
        $this->setName('devices');
        
        // ������ ������� ����� � text c ������ = number        
        $number = new Zend_Form_Element_Text('number', array('class'=>'form-control'));
        $number->setLabel('�����')
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
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group')), //��������� ��� ����� ���
               ));
        //�������� �������������� ��� ���������� ��������
        if($_SESSION['edit'] == false){
            $number ->addValidator('Db_NoRecordExists',true, array('table'=>'devices','field'=>'number',  'messages' => $settings['messages']['db']));   
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
 
        $type = new Zend_Form_Element_Select('type', array('class'=>'form-control', "multiOptions" => $types_values));
        $type->setLabel('������ ���������')
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

        $owner = new Zend_Form_Element_Select('owner', array('class'=>'form-control',"multiOptions" => $owners_values));
        $owner->setLabel('��������������')
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

        $user = new Zend_Form_Element_Select('user', array('class'=>'form-control',"multiOptions" => $users_values));
        $user->setLabel('����������')
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
        
// ������ ������� ����� � �������� �����       
        $city = new Zend_Form_Element_Text('city', array('class'=>'form-control'));
        $city->setLabel('�����')
               ->setRequired(true)
               ->setAttrib('placeholder','������: ����')
               ->addFilter('StripTags')
               ->addFilter('StringTrim')
               ->addValidator('regex',true, array("/^[�-��-�A-Za-z1-90 \.\-\,\"\�]{3,200}$/i", 'messages' => $settings['messages']['error']))
               ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $settings['messages']['empty'])))
               ->setDecorators(array('ViewHelper', 'Errors',
                    array(array('data' => 'HtmlTag'), array('class'  => 'test')),
                    array('Label', array('tag' => 'div', 'class' => 'form-control-static')),
                    array('Errors', array('tag' => 'div', 'class' => 'form-control-static')), 
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group')), //��������� ��� ����� ���
               ));
 
        $tt_name = new Zend_Form_Element_Text('tt_name', array('class'=>'form-control'));
        $tt_name->setLabel('�������� "�������� �����"')
               ->setRequired(true)
               ->setAttrib('placeholder','������: ������� Novus')
               ->addFilter('StripTags')
               ->addFilter('StringTrim')
               ->addValidator('regex',true, array("/^[�-��-�A-Za-z1-90 \.\-\,\"\�\)\(]{3,200}$/i", 'messages' => $settings['messages']['error']))
               ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $settings['messages']['empty'])))
               ->setDecorators(array('ViewHelper', 'Errors',
                    array(array('data' => 'HtmlTag'), array('class'  => 'test')),
                    array('Label', array('tag' => 'div', 'class' => 'form-control-static')),
                    array('Errors', array('tag' => 'div', 'class' => 'form-control-static')), 
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group')), //��������� ��� ����� ���
               ));
 
        $tt_user = new Zend_Form_Element_Text('tt_user', array('class'=>'form-control'));
        $tt_user->setLabel('���������� ������')
               ->setRequired(true)
               ->setAttrib('placeholder','������: �������� ���� ��������')
               ->addFilter('StripTags')
               ->addFilter('StringTrim')
               ->addValidator('regex',true, array("/^[�-��-�A-Za-z1-90 \.\-\,\"\�\)\(]{3,200}$/i", 'messages' => $settings['messages']['error']))
               ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $settings['messages']['empty'])))
               ->setDecorators(array('ViewHelper', 'Errors',
                    array(array('data' => 'HtmlTag'), array('class'  => 'test')),
                    array('Label', array('tag' => 'div', 'class' => 'form-control-static')),
                    array('Errors', array('tag' => 'div', 'class' => 'form-control-static')), 
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group')), //��������� ��� ����� ���
               ));
 
        $tt_phone = new Zend_Form_Element_Text('tt_phone', array('class'=>'form-control'));
        $tt_phone->setLabel('����� ��������')
               ->setRequired(true)
               ->setAttrib('placeholder','������: 1234567')
               ->addFilter('StripTags')
               ->addFilter('StringTrim')
               ->addValidator('regex',true, array("/^[�-��-�A-Za-z1-90 \.\-\,\"\�\+\(\)]{3,200}$/i", 'messages' => $settings['messages']['error']))
               ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $settings['messages']['empty'])))
               ->setDecorators(array('ViewHelper', 'Errors',
                    array(array('data' => 'HtmlTag'), array('class'  => 'test')),
                    array('Label', array('tag' => 'div', 'class' => 'form-control-static')),
                    array('Errors', array('tag' => 'div', 'class' => 'form-control-static')), 
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group')), //��������� ��� ����� ���
               ));

        $adress = new Zend_Form_Element_Text('adress', array('class'=>'form-control'));
        $adress->setLabel('����� "�������� �����"')
               ->setRequired(true)
               ->setAttrib('placeholder','������: ��.������������� 23')
               ->addFilter('StripTags')
               ->addFilter('StringTrim')
               ->addValidator('regex',true, array("/^[�-��-�A-Za-z1-90 \.\-\,\"\�\)\(]{3,200}$/i", 'messages' => $settings['messages']['error']))
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
        $this->addElements(array($id, $number, $name, $type, $owner, $user, $status, $city, $adress, $tt_name, $tt_user, $tt_phone, $submit));
    }

}

