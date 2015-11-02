<?php

class Application_Form_DeleteSales extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        $submit = new Zend_Form_Element_Submit('submit',array('class'=>'btn btn-default'));
        $cancel = new Zend_Form_Element_Submit('cancel',array('class'=>'btn btn-default'));

        $id = new Zend_Form_Element_Hidden('id');
        // ���������, ��� ������ � ���� �������� ����������� ��� ����� int
        $id->addFilter('Int')
           ->removeDecorator('label')
           ->removeDecorator('element');
                // ��������� ��� ��������� �������� � �����.
        $this->addElements(array($id, $submit, $cancel));
    }


}

