<?php

class Application_Form_Status extends Zend_Form {
    
    public function init() {
        
        $status = new Zend_Form_Element_Text('status', array('class'=>'form-control', 'width'=>'50px'));
        $status->setLabel('������')
               ->setRequired(true)
               ->setAttrib('placeholder','������� ��������������')
               ->addFilter('StripTags')
               ->addFilter('StringTrim')
               ->addValidator('db_norecordexists',true, array('table'=>'status','field'=>'status',  'messages' => '������ ��� ����������!'))
               ->addValidator('regex',true, array("/^[A-Za-z�-��-�1-90 \.\-]{3,20}$/i", 'messages' => $settings['messages']['error']))
               ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => $settings['messages']['empty'])))
               ->setDecorators(array('ViewHelper', 'Errors',
                    array(array('data' => 'HtmlTag'), array('class'  => 'test')),
                    array('Label', array('tag' => 'div', 'class' => 'form-control-static')),
                    array('Errors', array('tag' => 'div', 'class' => 'form-control-static')), 
                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group')), //��������� ��� ����� ���
               ));

        
        // ������ ������� ����� Submit c ������ = submit
        $submit = new Zend_Form_Element_Submit('submit',array('class'=>'btn btn-lg btn-success btn-block'));
        
        $this->addElements(array($status, $submit));
       
    }
}