<?php

class Application_Form_Warehouse extends Zend_Form {

    public function init() {
        
        // ����� ��� �����
        $this->setName('warehouse');
        // ������ ����������, ������� ����� ������� ��������� ���������
        $isEmptyMessage = '�������� �������� ������������ � �� ����� ���� ������';
        // ������ ������� ����� � text c ������ = number 
        $image = new Zend_Form_Element_File('image');
        $image->setLabel('��������� ����������')
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
        $serial->setLabel('�������� �����')
                ->setAttrib('placeholder', 'S\N:')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $isEmptyMessage)))
                ->addValidator('regex',true, array("/^[�-��-�a-zA-Z0-9 \. \-]{3,200}$/i", 'messages' => '�� ������ ������ �����'))
                ->setDecorators(array('ViewHelper', 'Errors',
                    array(array('data' => 'HtmlTag'), array('class'  => 'test')),
                    array('Label', array('tag' => 'div', 'class' => 'form-control-static')),
                    array('Errors', array('tag' => 'div', 'class' => 'form-control-static')),
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group')), //��������� ��� ����� ���
             ));         
        
        $name = new Zend_Form_Element_Text('name', array('class'=>'form-control'));
        $name->setLabel('��������')
             ->setRequired(true) //���� ������������ ��� �����
             ->addFilter('StripTags') //������ ������� ���� HTML
             ->addValidator('NotEmpty', true, array('messages' => '�� ��, �� ����� ���� ������'))
             ->addValidator('regex',true, array("/^[�-��-�a-zA-Z0-9 \. \- \s ]{3,50}$/i", 'messages' => '�� ������ ������ �����'))
             ->setDecorators(array('ViewHelper', 'Errors',
                    array(array('data' => 'HtmlTag'), array('class'  => 'test')),
                    array('Label', array('tag' => 'div', 'class' => 'form-control-static')),
                    array('Errors', array('tag' => 'div', 'class' => 'form-control-static')), 
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group')), //��������� ��� ����� ���
               ));
               
        $type = new Zend_Form_Element_Text('type', array('class'=>'form-control'));
        $type->setLabel('���')
             ->setAttrib('placeholder', 'Viena,Royal,All')
             ->setRequired(true) //���� ������������ ��� �����
             ->addFilter('StripTags') //������ ������� ���� HTML
             ->addValidator('NotEmpty', true, array('messages' => '�� ��, �� ����� ���� ������'))
             ->addValidator('regex',true, array("/^[a-zA-Z0-9 \- \s]{3,20}$/i", 'messages' => '�� ������ ������ �����'))
             ->setDecorators(array('ViewHelper', 'Errors',
                    array(array('data' => 'HtmlTag'), array('class'  => 'test')),
                    array('Label', array('tag' => 'div', 'class' => 'form-control-static')),
                    array('Errors', array('tag' => 'div', 'class' => 'form-control-static')), 
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group')), //��������� ��� ����� ���
               ));
        
        $remain = new Zend_Form_Element_Text('remain', array('class'=>'form-control'));
        $remain->setLabel('�������')
             ->setAttrib('placeholder', '���-��')
             ->setRequired(true) //���� ������������ ��� �����
             ->addFilter('StripTags') //������ ������� ���� HTML
             ->addValidator('NotEmpty', true, array('messages' => '�� ��, �� ����� ���� ������'))
             ->addValidator('regex',true, array("/^[0-9 \-]{1,20}$/i", 'messages' => '�� ������ ������ �����'))
             ->setDecorators(array('ViewHelper', 'Errors',
                    array(array('data' => 'HtmlTag'), array('class'  => 'test')),
                    array('Label', array('tag' => 'div', 'class' => 'form-control-static')),
                    array('Errors', array('tag' => 'div', 'class' => 'form-control-static')), 
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group')), //��������� ��� ����� ���
               ));
 
        $price = new Zend_Form_Element_Text('price', array('class'=>'form-control'));
        $price->setLabel('����')
             ->setAttrib('placeholder', '������� ���� � ���')
             ->setRequired(true) //���� ������������ ��� �����
             ->addFilter('StripTags') //������ ������� ���� HTML
             ->addValidator('NotEmpty', true, array('messages' => '�� ��, �� ����� ���� ������'))
             ->addValidator('regex',true, array("/^[0-9]{0,20}$/i", 'messages' => '�� ������ ������ �����, ��� ����� �������� ������ �����'))
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
        
                // ������ ������� hidden c ������ = id
        $path = new Zend_Form_Element_Hidden('path');
        // ���������, ��� ������ � ���� �������� ����������� ��� ����� int
        $path ->removeDecorator('label')
           ->removeDecorator('element');

        // ������ ������� ����� Submit c ������ = submit
        $submit = new Zend_Form_Element_Submit('submit',array('class'=>'btn btn-default'));

        // ��������� ��� ��������� �������� � �����.
        $this->addElements(array($id, $serial, $name, $type, $remain, $price, $image, $submit, $path));
    }

}

