<?php

class Application_Form_Repair extends Zend_Form {
 
    public function init() {

        global $settings;
        // ����� ��� �����
        $this->setName('repair');
       
        $claim = new Zend_Form_Element_Text('claim', array('class'=>'form-control'));
        $claim->setLabel('������')
             ->setRequired(true) //���� ������������ ��� �����
             ->addFilter('StripTags') //������ ������� ���� HTML
            // ->addValidator('between', false, array('min'=> 10, 'max'=>100, 'messages'=>'����������'))
             ->addValidator('NotEmpty', true, array('messages' => $settings['messages']['empty']))
             ->addValidator('regex',true, array("/^[�-��-� \.\,]{3,30}$/i", 'messages' => $settings['messages']['error']))
             ->setDecorators(array('ViewHelper', 'Errors',
                    array(array('data' => 'HtmlTag'), array('class'  => 'test')),
                    array('Label', array('tag' => 'div', 'class' => 'form-control-static')),
                    array('Errors', array('tag' => 'div', 'class' => 'form-control-static')),
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group')), //��������� ��� ����� ���
               ));
        
        $diagnos = new Zend_Form_Element_Text('diagnos', array('class'=>'form-control'));
        $diagnos->setLabel('�������')
             ->setRequired(true) //���� ������������ ��� �����
             ->addFilter('StripTags') //������ ������� ���� HTML
            // ->addValidator('between', false, array('min'=> 10, 'max'=>100, 'messages'=>'����������'))
             ->addValidator('NotEmpty', true, array('messages' => $settings['messages']['empty']))
             ->addValidator('regex',true, array("/^[�-��-� \.\,]{3,20}$/i", 'messages' => $settings['messages']['error']))
             ->setDecorators(array('ViewHelper', 'Errors',
                    array(array('data' => 'HtmlTag'), array('class'  => 'test')),
                    array('Label', array('tag' => 'div', 'class' => 'form-control-static')),
                    array('Errors', array('tag' => 'div', 'class' => 'form-control-static')),
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group')), //��������� ��� ����� ���
               ));
                      
        $work = new Zend_Form_Element_Text('work', array('class'=>'form-control'));
        $work->setLabel('������')
             ->setRequired(true) //���� ������������ ��� �����
             ->addFilter('StripTags') //������ ������� ���� HTML
            // ->addValidator('between', false, array('min'=> 10, 'max'=>100, 'messages'=>'����������'))
             ->addValidator('NotEmpty', true, array('messages' => $settings['messages']['empty']))
             ->addValidator('regex',true, array("/^[�-��-� \.\,]{3,20}$/i", 'messages' => $settings['messages']['error']))
             ->setDecorators(array('ViewHelper', 'Errors',
                    array(array('data' => 'HtmlTag'), array('class'  => 'test')),
                    array('Label', array('tag' => 'div', 'class' => 'form-control-static')),
                    array('Errors', array('tag' => 'div', 'class' => 'form-control-static')),
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group')), //��������� ��� ����� ���
               ));
                      
        $spares = new Zend_Form_Element_Text('spares', array('class'=>'form-control'));
        $spares->setLabel('��������')
             ->setRequired(true) //���� ������������ ��� �����
             ->addFilter('StripTags') //������ ������� ���� HTML
             ->addValidator('NotEmpty', true, array('messages' => $settings['messages']['empty']))
             ->addValidator('regex',true, array("/^[�-��-� \.\,]{3,20}$/i", 'messages' => $settings['messages']['error']))
             ->setDecorators(array('ViewHelper', 'Errors',
                    array(array('data' => 'HtmlTag'), array('class'  => 'test')),
                    array('Label', array('tag' => 'div', 'class' => 'form-control-static')),
                    array('Errors', array('tag' => 'div', 'class' => 'form-control-static')),
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group')), //��������� ��� ����� ���
               ));   

        $comments = new Zend_Form_Element_Text('comments', array('class'=>'form-control'));
        $comments->setLabel('�����������')
             ->setRequired(true) //���� ������������ ��� �����
             ->addFilter('StripTags') //������ ������� ���� HTML
            // ->addValidator('between', false, array('min'=> 10, 'max'=>100, 'messages'=>'����������'))
             ->addValidator('NotEmpty', true, array('messages' => $settings['messages']['empty']))
             ->addValidator('regex',true, array("/^[�-��-� \.\,]{3,20}$/i", 'messages' => $settings['messages']['error']))
             ->setDecorators(array('ViewHelper', 'Errors',
                    array(array('data' => 'HtmlTag'), array('class'  => 'test')),
                    array('Label', array('tag' => 'div', 'class' => 'form-control-static')),
                    array('Errors', array('tag' => 'div', 'class' => 'form-control-static')),
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group')), //��������� ��� ����� ���
               ));
  
        // ������ ������� hidden c ������ = id
        $number = new Zend_Form_Element_Hidden('number');
        // ���������, ��� ������ � ���� �������� ����������� ��� ����� int
        $number->removeDecorator('label')
               ->removeDecorator('element');

        // ������ ������� ����� Submit c ������ = submit
        $submit = new Zend_Form_Element_Submit('submit',array('class'=>'btn btn-default'));
        // ������ ������� id = submitbutton
        //$submit->setAttrib('id', 'submitbutton');
        
                // ������ ������� hidden c ������ = id
        $id = new Zend_Form_Element_Hidden('id');
        // ���������, ��� ������ � ���� �������� ����������� ��� ����� int
        $id->addFilter('Int')
           ->removeDecorator('label')
           ->removeDecorator('element');

        // ��������� ��� ��������� �������� � �����.
        $this->addElements(array($id, $number, $claim,$diagnos, $spares, $work, $comments, $submit));
    }

}

