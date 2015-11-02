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
    
    public function typesAction() {
        $this->_helper->setup->showData();
    }

    public function addtypeAction() {
        $this->_helper->setup->addData();
    }

    public function edittypeAction() {
        $this->_helper->setup->editData();
    }

    public function deletetypeAction() {
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
     public function accessAction() {
        $users = new Application_Model_DbTable_Access();
        $this->view->users = $users->fetchAll()->toArray();
    }
     
    

    public function addaccessAction() {

        // ���� � ��� ��� Post ������
        if ($this->getRequest()->isPost()) {

            // ��������� ���
            $formData = $this->getRequest()->getPost(); //��������� ������

            if (!preg_match("/^[a-z0-9]{5,15}$/i", $formData['username'])) { //�������� ���������� ���������
                $error['username'] = 'error'; //���������� ������������ ��������
            }
            if (!preg_match("/^[a-z0-9]{5,15}$/i", $formData['password'])) { //�������� ���������� ���������
                $error['password'] = 'error'; //���������� ������������ ��������
            }
            if (!preg_match("/^[a-z]{5,6}$/i", $formData['role'])) { //�������� ���������� ���������
                $error['role'] = 'error'; //���������� ������������ ��������
            }
            
            //��������� ������� ������ � ����
            $access = new Application_Model_DbTable_Access();
            $alldata = $access->fetchAll();

            foreach ($alldata as $row) {
                foreach ($row as $name => $value) {
                    if ($name == 'username') {
                        if ($value == $formData['username']) {
                            $error['username'] = 'error';
                            $error['message'] = '������ ��� ���� � ����!';
                        }
                    }
                }
            }

            //������� ������ ������ �� ������� ��� ������ �������
            foreach ($formData as $data => $value) {
                if (preg_match("/check/", $data)) { //���� ���������� check
                    if (!$error) {
                        $check_data[substr($data, 6)] = $value;

                        if ($data == 'check_set_names' && $value == 'names') {
                            $check_data['set_addname'] = 'addname';
                            $check_data['set_editname'] = 'editname';
                            $check_data['set_deletename'] = 'deletename';
                        }
                        if ($data == 'check_set_types' && $value == 'types') {
                            $check_data['set_addtype'] = 'addtype';
                            $check_data['set_edittype'] = 'edittype';
                            $check_data['set_deletetype'] = 'deletetype';
                        }
                        if ($data == 'check_set_owners' && $value == 'owners') {
                            $check_data['set_addowner'] = 'addowner';
                            $check_data['set_editowner'] = 'editowner';
                            $check_data['set_deleteowner'] = 'deleteowner';
                        }
                        if ($data == 'check_set_users' && $value == 'users') {
                            $check_data['set_adduser'] = 'adduser';
                            $check_data['set_edituser'] = 'edituser';
                            $check_data['set_deleteuser'] = 'deleteuser';
                        }
                        if ($data == 'check_set_status' && $value == 'status') {
                            $check_data['set_addstatus'] = 'addstatus';
                            $check_data['set_editstatus'] = 'editstatus';
                            $check_data['set_deletestatus'] = 'deletestatus';
                        }
                        if ($data == 'check_set_access' && $value == 'access') {
                            $check_data['set_addaccess'] = 'addaccess';
                            $check_data['set_deleteaccess'] = 'deleteaccess';
                        }
                    } else {
                        $check_data[$data] = 'checked';
                    }
                }
            }
            //���� ��� �������
            if (!$error) {

                //��������� ������ � ����
                $access = new Application_Model_DbTable_Access();
                $access->addAccess($formData['username'], md5($formData['password']), $formData['role']);

                $allow = new Application_Model_DbTable_Allow();
                $allow->addAllow($check_data, $formData['username']);

                //��������� �� ���������� ��������
                $this->_helper->redirector('access', 'setup');

                //���� ���� ������
            } else {
                $this->view->error = $error;
                $this->view->username = $formData['username'];
                $this->view->check_data = $check_data;
            }
        }
    }
    public function editaccessAction() {

        $id = $this->getParam('id');
        $access = new Application_Model_DbTable_Access();
        $allow = new Application_Model_DbTable_Allow();
        // ���� � ��� ��� Post ������
        if ($this->getRequest()->isPost()) {

            // ��������� ���
            $formData = $this->getRequest()->getPost(); //��������� ������

            if (!preg_match("/^[a-z0-9]{5,15}$/i", $formData['username'])) { //�������� ���������� ���������
                $error['username'] = 'error'; //���������� ������������ ��������
            }
            if (!preg_match("/^[a-z0-9]{5,15}$/i", $formData['password'])) { //�������� ���������� ���������
                $error['password'] = 'error'; //���������� ������������ ��������
            }
            if (!preg_match("/^[a-z]{5,6}$/i", $formData['role'])) { //�������� ���������� ���������
                $error['role'] = 'error'; //���������� ������������ ��������
            }
            
            //������� ������ ������ �� ������� ��� ������ �������
            foreach ($formData as $data => $value) {
                if (preg_match("/check/", $data)) { //���� ���������� check
                    if (!$error) {
                        $check_data[substr($data, 6)] = $value;

                        if ($data == 'check_set_names' && $value == 'names') {
                            $check_data['set_addname'] = 'addname';
                            $check_data['set_editname'] = 'editname';
                            $check_data['set_deletename'] = 'deletename';
                        }
                        if ($data == 'check_set_types' && $value == 'types') {
                            $check_data['set_addtype'] = 'addtype';
                            $check_data['set_edittype'] = 'edittype';
                            $check_data['set_deletetype'] = 'deletetype';
                        }
                        if ($data == 'check_set_owners' && $value == 'owners') {
                            $check_data['set_addowner'] = 'addowner';
                            $check_data['set_editowner'] = 'editowner';
                            $check_data['set_deleteowner'] = 'deleteowner';
                        }
                        if ($data == 'check_set_users' && $value == 'users') {
                            $check_data['set_adduser'] = 'adduser';
                            $check_data['set_edituser'] = 'edituser';
                            $check_data['set_deleteuser'] = 'deleteuser';
                        }
                        if ($data == 'check_set_status' && $value == 'status') {
                            $check_data['set_addstatus'] = 'addstatus';
                            $check_data['set_editstatus'] = 'editstatus';
                            $check_data['set_deletestatus'] = 'deletestatus';
                        }
                        if ($data == 'check_set_access' && $value == 'access') {
                            $check_data['set_addaccess'] = 'addaccess';
                            $check_data['set_deleteaccess'] = 'deleteaccess';
                        }
                    } else {
                        $check_data[$data] = 'checked';
                    }
                }
            }
            //���� ��� �������
            if (!$error) {

                //�������� ������ � ����
                $access->editAccess($id, $formData['username'], md5($formData['password']), $formData['role']);
                $allow->editAllow($check_data, $formData['username']);

                //��������� �� ���������� ��������
                $this->_helper->redirector('access', 'setup');

                //���� ���� ������
            } else {
                $this->view->error = $error;
                $this->view->username = $formData['username'];
                $this->view->check_data = $check_data;
            }
        } else {

            $userdata = $access->getAccess($id);
            $check_data = $allow->fetchRow($allow->select()->where('username = ?', $userdata['username']))->toArray();
            
            foreach($check_data as $data => $value){
                if($value) {
                    $check_data["check_".$data] = 'checked';
                }
            }
            
            $this->view->username = $userdata['username'];
            $this->view->role = $userdata['role'];
            $this->view->check_data = $check_data;
        }
    }

    public function deleteaccessAction()
    {
        // action body
        $form = new Application_Form_DeleteDevice();
        $form->submit->setLabel('�������');
        $form->cancel->setLabel('������');
        $this->view->form = $form;
        $id = $this->getParam('id');
        
        if($id==1){ 
            echo "������������ �� ����� ���� ������!";
            die;
        }
        $access = new Application_Model_DbTable_Access();
        //���� ���� ������ POST
        if ($this->getRequest()->isPost()){
            //���� ������������ ��������
            if  ($this->getRequest()->getPost('submit')){
                $access->deleteAccess($id);
                $this->_helper->redirector('access','setup');
            } else {
            //���� �������� ��������
            if ($this->getRequest()->getPost('cancel')){
            $this->_helper->redirector('access','setup');
            }
            }
        } else {
        //������� �������������� ������

      $this->view->access = $access->getaccess($id);
        }
    }
    
    public function pricesAction() {

        $prices = new Application_Model_DbTable_Prices();
        $this->view->prices = $prices->fetchAll();
    }

    public function addpricesAction() {

        $form = new Application_Form_Prices();
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
                $name = $form->getValue('name');
                $price = $form->getValue('price');

                //��������� ������ � ����
                $prices = new Application_Model_DbTable_Prices();
                $prices->addPrice($name, $price);

                //��������� �� ���������� ��������
                $this->_helper->redirector('prices','setup');
            } else {
                //���������� ���������
                //���������� ������ � �������
                $form->populate($formData);
            }
        }
    }
    
        public function editpricesAction() {
        // action body
        $form = new Application_Form_Prices();
        $form->submit->setLabel('�������������');
        $this->view->form = $form;
        
        $prices = new Application_Model_DbTable_Prices();

        if ($this->getRequest()->isPost()) {

            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {

                $id = (int) $form->getValue('id');
                $name = $form->getValue('name');
                $price = $form->getValue('price');

                $prices->editPrice($id, $name, $price);
                //��������� �� ���������� ��������
                $this->_helper->redirector('prices', 'setup');
            } else {

                $form->populate($formData);
            }
        } else {

            $id = $this->getParam('id');
            $form->populate($prices->getPrice($id));
        }
    }
    
    public function deletepricesAction() {
        // action body
        $form = new Application_Form_DeletePrices();
        $form->submit->setLabel('�������');
        $form->cancel->setLabel('������');
        $this->view->form = $form;
        $id = $this->getParam('id');
        
        $prices = new Application_Model_DbTable_Prices();
        //���� ���� ������ POST
        if ($this->getRequest()->isPost()) {
            //���� ������������ ��������
            if ($this->getRequest()->getPost('submit')) {

                $prices->deletePrice($id);
                $this->_helper->redirector('prices', 'setup');
            } else {
                //���� �������� ��������
                if ($this->getRequest()->getPost('cancel')) {
                    $this->_helper->redirector('prices', 'setup');
                }
            }
        }
    }

}
