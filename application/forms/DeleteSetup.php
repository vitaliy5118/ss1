<?php

class Application_Form_DeleteSetup extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        $submit = new Zend_Form_Element_Submit('submit',array('class'=>'btn btn-default'));
        $cancel = new Zend_Form_Element_Submit('cancel',array('class'=>'btn btn-default'));
        
        $this->addElements(array($submit, $cancel));
    }


}

