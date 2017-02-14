<?php

class UpdateController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
        
    }

    public function makeAction() {
        
        $make = new Application_Model_DbTable_Update();
        $make->makeBase();
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->redirector('index');
    }

    public function loadAction() {
        
        $update = new Application_Model_DbTable_Update();
        $update->loadBase();
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->redirector('index');
    }
    
    public function updateAction() {
        
        $update = new Application_Model_DbTable_Update();
        $update->updateBase();
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->redirector('index');
    }

}

