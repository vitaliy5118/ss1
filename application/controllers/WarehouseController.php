<?php

class WarehouseController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
        $warehouse = new Application_Model_DbTable_Warehouse();
        
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
             $data_array = $warehouse->fetchAll($warehouse->searchWarehouse($search)); 
         } else {
             $data_array = $warehouse->fetchAll();
         }
         
        //����� ���������
        $this->_helper->navigation->initnav($sort, $select_limit, $page, $data_array);
         
        $sort = $this->_helper->navigation->sortby();
        $select_limit = $this->_helper->navigation->selectlimit();
        $page = $this->_helper->navigation->page();
        $count = $select_limit;
        $offset = $page*$select_limit-$select_limit;
        
        //��������� ������
        if($search != ''){
            $select = $warehouse->searchWarehouse($search)->order($sort)->limit($count, $offset);
            $this->view->search_param = "/search_catalog/$search";
        } else {
            $select = $warehouse->select()->order($sort)->limit($count, $offset);    
        }
        //������� ���������
        $this->view->warehouse = $warehouse->fetchAll($select);
        
        $this->view->previos_page = $this->_helper->navigation->previospage(); 
        $this->view->next_page = $this->_helper->navigation->nextpage(); 
        $this->view->last_page = $this->_helper->navigation->lastpage(); 
        $this->view->button_parameters = $this->_helper->navigation->buttonparameters();
        $this->view->page_first = $this->_helper->navigation->pagefirst();
        $this->view->page_last = $this->_helper->navigation->pagelast();
        $this->view->count = $this->_helper->navigation->count();
        $this->view->search = $search;
        
    }

    public function addAction()
    {
        // ������ �����
        $form = new Application_Form_Warehouse();

        // ��������� ����� ��� submit
        $form->submit->setLabel('��������');

        // ������� ����� � view
        $this->view->form = $form;

        // ���� � ��� ��� Post ������
        if ($this->getRequest()->isPost()) {
            // ��������� ���
            $formData = $this->getRequest()->getPost();

            // ���� ����� ��������� �����
            if ($form->isValid($formData)) {
                // ��������� ������
                $serial = $form->getValue('serial');
                $name = $form->getValue('name');
                $type = $form->getValue('type');
                $remain = $form->getValue('remain');
                $lastadding = $form->getValue('lastadding');

                // ������ ������ ������
                $warehouse = new Application_Model_DbTable_Warehouse();

                // �������� ����� ������ addMovie ��� ������� ����� ������
                $warehouse->addWarehouse($serial, $name, $type, $remain, $lastadding);
                
               // ���������� ������������ helper ��� ��������� �� action = index
                $this->_helper->redirector('index');
            } else {
                // ���� ����� ��������� �������,
                // ���������� ����� populate ��� ���������� ���� �����
                // ��� �����������, ������� ��� ������������
                $form->populate($formData);
            }
        }
    }
    
    public function editAction()
    {
        // ������ �����
        $form = new Application_Form_Warehouse();

        // ��������� ����� ��� submit
        $form->submit->setLabel('�������������');

        // ������� ����� � view
        $this->view->form = $form;

        // ���� � ��� ��� Post ������
        if ($this->getRequest()->isPost()) {
            // ��������� ���
            $formData = $this->getRequest()->getPost();

            // ���� ����� ��������� �����
            if ($form->isValid($formData)) {
                // ��������� ������
                $id = (int) $form->getValue('id');
                $serial = $form->getValue('serial');
                $name = $form->getValue('name');
                $type = $form->getValue('type');
                $remain = $form->getValue('remain');
                $lastadding = $form->getValue('lastadding');

                // ������ ������ ������
                $warehouse = new Application_Model_DbTable_Warehouse();

                // �������� ����� ������ addMovie ��� ������� ����� ������
                $warehouse->editWarehouse($id, $serial, $name, $type, $remain, $lastadding);

                // ���������� ������������ helper ��� ��������� �� action = index
                $this->_helper->redirector('index');
            } else {
                // ���� ����� ��������� �������,
                // ���������� ����� populate ��� ���������� ���� �����
                // ��� �����������, ������� ��� ������������
                $form->populate($formData);
            }
        } else {
            // ���� �� ������� �����, �� �������� id ������, ������� ����� ��������
            $id = $this->_getParam('id', 0);

            if ($id > 0) {
                // ������ ������ ������
                $warehouse = new Application_Model_DbTable_Warehouse();

                // ��������� ����� ����������� ��� ������ ������ populate
                $form->populate($warehouse->getWarehouse($id));
            }
        }
    }
    
       public function deleteAction() {
        // ���� � ��� ��� Post ������
        if ($this->getRequest()->isPost()) {
            // ��������� ��������
            $del = $this->getRequest()->getPost('del');

            // ���� ������������ ���������� ��� ������� ������� ������
            if ($del == '��') {
                // ��������� id ������, ������� ����� �������
                $id = $this->getRequest()->getPost('id');
                // ������ ������ ������
                $warehouse = new Application_Model_DbTable_Warehouse();

                // �������� ����� ������ deleteMovie ��� �������� ������
                $warehouse->deleteWarehouse($id);
            }

            // ���������� ������������ helper ��� ��������� �� action = index
            $this->_helper->redirector('index');
        } else {
            // ���� ������ �� Post, ������� ��������� ��� �������������
            // �������� id ������, ������� ����� �������
            $id = $this->_getParam('id');

            // ������ ������ ������
            $warehouse = new Application_Model_DbTable_Warehouse();

            // ������ ������ � ������� � view
            $this->view->warehouse = $warehouse->getWarehouse($id);
        }
    }


}


    


