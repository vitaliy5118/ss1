<?php

class CatalogController extends Zend_Controller_Action
{
    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
        $devices = new Application_Model_DbTable_Devices();
        
        if($this->getRequest()->getParam('sort')){
            $sort = $this->getRequest()->getParam('sort');
        }
        
        if($this->getRequest()->isPost('select_limit')){
            $select_limit = $this->getRequest()->getPost('select_limit');
        }
        
        if($this->getRequest()->getParam('page')){
            $page = $this->getRequest()->getParam('page');
        }
       
        //��������� ���������� ������
        if($this->getRequest()->isPost('search_catalog')){
            
            $search = $this->getRequest()->getPost('search_catalog');

         } elseif ($this->getRequest()->getParam('search_catalog')){
            
            $search = $this->getRequest()->getParam('search_catalog');

         }
         
         if($search != '' ){
             $data_array = $devices->fetchAll($devices->searchDevice($search)); 
         } else {
             $data_array = $devices->fetchAll();
         }
         
        //����� ���������
        $this->_helper->navigation->initNav($sort, $select_limit, $page, $data_array);
        $this->_helper->navigation->setView($this->view);
         
        $sort = $this->_helper->navigation->sortby();
        $select_limit = $this->_helper->navigation->selectlimit();
        $page = $this->_helper->navigation->page();
        $count = $select_limit;
        $offset = $page*$select_limit-$select_limit;
        
        //��������� ������
        if($search != ''){
            $select = $devices->searchDevice($search)->order($sort)->limit($count, $offset);
            $this->view->search_param = "/search_catalog/$search";
        } else {
            $select = $devices->select()->order($sort)->limit($count, $offset);    
        }
        //������� ���������
        $this->view->devices = $devices->fetchAll($select);
        $this->view->search = $search;
        
    }

    public function addAction()
    {
        
        $form = new Application_Form_Devices();
        $form->submit->setLabel('��������');
        $this->view->form = $form;
       
        // ���� � ��� ��� Post ������
        if ($this->getRequest()->isPost()) {
            //��������� ������
            $formData = $this->getRequest()->getPost();

            //�������� ����������    
            if ($form->isValid($formData)) {
                //�������� ���������
                //��������� ������
                $number = $form->getValue('number');
                $name = $form->getValue('name');
                $owner = $form->getValue('owner');
                $user = $form->getValue('user');
                $status = $form->getValue('status');

                //��������� ������ � ����
                $devices = new Application_Model_DbTable_Devices();
                $devices->addDevice($number, $name, $owner, $user, $status);

                //��������� �� ���������� ��������
                $this->_helper->redirector('index');
            } else {
                //���������� ���������
                //���������� ������ � �������
                $form->populate($formData);
            }
        }
    }

    public function editAction()
    {
        // action body
        $form = new Application_Form_Devices();
        $form->submit->setLabel('�������������');
        $_SESSION['edit'] = true;
        $this->view->form = $form;

        if ($this->getRequest()->isPost()) {

            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {

                $id = (int) $form->getValue('id');
                $number = $form->getValue('number');
                $name = $form->getValue('name');
                $owner = $form->getValue('owner');
                $user = $form->getValue('user');
                $status = $form->getValue('status');

                $device = new Application_Model_DbTable_Devices();
                $device->editDevice($id, $number, $name, $owner, $user, $status);
                $this->_helper->redirector('index');
            } else {

                $form->populate($formData);
            }
        } else {

            $id = $this->getParam('id');
            $device = new Application_Model_DbTable_Devices();
            $form->populate($device->getDevice($id));
        }
    }

    public function deleteAction()
    {
        // action body
        $form = new Application_Form_DeleteDevice();
        $form->submit->setLabel('�������');
        $form->cancel->setLabel('������');
        $this->view->form = $form;
        $id = $this->getParam('id');
        //���� ���� ������ POST
        if ($this->getRequest()->isPost()){
            //���� ������������ ��������
            if  ($this->getRequest()->getPost('submit')){
                $device = new Application_Model_DbTable_Devices();
                $device->deleteDevice($id);
                $this->_helper->redirector('index');
            } else {
            //���� �������� ��������
            if ($this->getRequest()->getPost('cancel')){
            $this->_helper->redirector('index');
            }
            }
        } else {
        //������� �������������� ������

        $device = new Application_Model_DbTable_Devices();

        $this->view->device = $device->getDevice($id);
        }
    }


}





