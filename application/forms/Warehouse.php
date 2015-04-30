<?php

class Application_Form_Warehouse extends Zend_Form {

    public function init() {
        
        // ����� ��� �����
        $this->setName('warehouse');
       
        // ������ ����������, ������� ����� ������� ��������� ���������
        $isEmptyMessage = '�������� �������� ������������ � �� ����� ���� ������';
        // ������ ������� ����� � text c ������ = number        
        $serial = new Zend_Form_Element_Text('serial');
        $serial->setLabel('�������� �����')
                ->setAttrib('placeholder', 'S\N:')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $isEmptyMessage)))
                ->addValidator('regex',true, array("/^[�-��-�a-zA-Z0-9 \. \-]{3,20}$/i", 'messages' => '�� ������ ������ �����'));
               
        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('��������')
             ->setRequired(true) //���� ������������ ��� �����
             ->addFilter('StripTags') //������ ������� ���� HTML
             ->addValidator('NotEmpty', true, array('messages' => '�� ��, �� ����� ���� ������'))
             ->addValidator('regex',true, array("/^[�-��-�a-zA-Z0-9 \. \- \s ]{3,30}$/i", 'messages' => '�� ������ ������ �����'));
               
        $type = new Zend_Form_Element_Text('type');
        $type->setLabel('���')
             ->setAttrib('placeholder', 'Viena,Royal,All')
             ->setRequired(true) //���� ������������ ��� �����
             ->addFilter('StripTags') //������ ������� ���� HTML
             ->addValidator('NotEmpty', true, array('messages' => '�� ��, �� ����� ���� ������'))
             ->addValidator('regex',true, array("/^[a-zA-Z0-9 \- \s]{3,20}$/i", 'messages' => '�� ������ ������ �����'));
        
        $remain = new Zend_Form_Element_Text('remain');
        $remain->setLabel('�������')
             ->setAttrib('placeholder', '���-��')
             ->setRequired(true) //���� ������������ ��� �����
             ->addFilter('StripTags') //������ ������� ���� HTML
             ->addValidator('NotEmpty', true, array('messages' => '�� ��, �� ����� ���� ������'))
             ->addValidator('regex',true, array("/^[0-9 \-]{1,20}$/i", 'messages' => '�� ������ ������ �����'));
        
        $lastadding = new Zend_Form_Element_Text('lastadding');
        $lastadding->setLabel('��������� ����������')
             ->setRequired(true) //���� ������������ ��� �����
             ->addFilter('StripTags') //������ ������� ���� HTML
             ->setAttrib('placeholder', 'xx.xx.20xx')
             ->addValidator('NotEmpty', true, array('messages' => '�� ��, �� ����� ���� ������'))
             ->addValidator('regex',true, array("/^[0-9 \. \-]{3,20}$/i", 'messages' => '�� ������ ������ �����'));
        
        

        // ������ ������� hidden c ������ = id
        $id = new Zend_Form_Element_Hidden('id');
        // ���������, ��� ������ � ���� �������� ����������� ��� ����� int
        $id->addFilter('Int')
           ->removeDecorator('label')
           ->removeDecorator('element');

        // ������ ������� ����� Submit c ������ = submit
        $submit = new Zend_Form_Element_Submit('submit');
        // ������ ������� id = submitbutton
        //$submit->setAttrib('id', 'submitbutton');

        // ��������� ��� ��������� �������� � �����.
        $this->addElements(array($id, $serial, $name, $type, $remain, $lastadding, $submit));
    }

}

