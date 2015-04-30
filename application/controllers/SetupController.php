<?php

class SetupController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
        // action body
    }

    public function preDispatch() {
        $this->_helper->setup->setView($this->view);
    }
    
//******************************************************************************
    
    public function namesAction() {
        $this->_helper->setup->showData();
    }

    public function addnameAction() {
        $this->_helper->setup->addData();
    }

    public function editnameAction() {
        $this->_helper->setup->editData();
    }

    public function deletenameAction() {
        $this->_helper->setup->deleteData();
    }
//******************************************************************************
    
    public function ownersAction() {
        $this->_helper->setup->showData();
    }

    public function addownerAction() {
        $this->_helper->setup->addData();
    }

    public function editownerAction() {
        $this->_helper->setup->editData();
    }

    public function deleteownerAction() {
        $this->_helper->setup->deleteData();
    }
//******************************************************************************
 
    public function usersAction() {
        $this->_helper->setup->showData();
    }

    public function adduserAction() {
        $this->_helper->setup->addData();
    }

    public function edituserAction() {
        $this->_helper->setup->editData();
    }

    public function deleteuserAction() {
        $this->_helper->setup->deleteData();
    }
//******************************************************************************
 
    public function statusAction() {
        $this->_helper->setup->showData();
    }

    public function addstatusAction() {
        $this->_helper->setup->addData();
    }

    public function editstatusAction() {
        $this->_helper->setup->editData();
    }

    public function deletestatusAction() {
        $this->_helper->setup->deleteData();
    }
//******************************************************************************


}
