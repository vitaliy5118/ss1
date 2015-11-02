<?php

class HistoryController extends Zend_Controller_Action
{
    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
        $history = new Application_Model_DbTable_History();
        $number = $this->getRequest()->getParam('number');
        
        $this->view->history = $history->getHistory($number);
        
    }
}

