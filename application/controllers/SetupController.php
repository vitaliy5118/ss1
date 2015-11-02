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

        // Если к нам идёт Post запрос
        if ($this->getRequest()->isPost()) {

            // Принимаем его
            $formData = $this->getRequest()->getPost(); //принимаем данные

            if (!preg_match("/^[a-z0-9]{5,15}$/i", $formData['username'])) { //проверка регулярных выражений
                $error['username'] = 'error'; //записываем несовпадение правилам
            }
            if (!preg_match("/^[a-z0-9]{5,15}$/i", $formData['password'])) { //проверка регулярных выражений
                $error['password'] = 'error'; //записываем несовпадение правилам
            }
            if (!preg_match("/^[a-z]{5,6}$/i", $formData['role'])) { //проверка регулярных выражений
                $error['role'] = 'error'; //записываем несовпадение правилам
            }
            
            //Проверяем наличие записи в базе
            $access = new Application_Model_DbTable_Access();
            $alldata = $access->fetchAll();

            foreach ($alldata as $row) {
                foreach ($row as $name => $value) {
                    if ($name == 'username') {
                        if ($value == $formData['username']) {
                            $error['username'] = 'error';
                            $error['message'] = 'Запись уже есть в базе!';
                        }
                    }
                }
            }

            //создаем массив данных из пунктов где нажаты галочки
            foreach ($formData as $data => $value) {
                if (preg_match("/check/", $data)) { //ищем переменные check
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
            //если нет ошибкок
            if (!$error) {

                //Добавляем данные в базу
                $access = new Application_Model_DbTable_Access();
                $access->addAccess($formData['username'], md5($formData['password']), $formData['role']);

                $allow = new Application_Model_DbTable_Allow();
                $allow->addAllow($check_data, $formData['username']);

                //Переходим на предыдущую страницу
                $this->_helper->redirector('access', 'setup');

                //если есть ошибки
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
        // Если к нам идёт Post запрос
        if ($this->getRequest()->isPost()) {

            // Принимаем его
            $formData = $this->getRequest()->getPost(); //принимаем данные

            if (!preg_match("/^[a-z0-9]{5,15}$/i", $formData['username'])) { //проверка регулярных выражений
                $error['username'] = 'error'; //записываем несовпадение правилам
            }
            if (!preg_match("/^[a-z0-9]{5,15}$/i", $formData['password'])) { //проверка регулярных выражений
                $error['password'] = 'error'; //записываем несовпадение правилам
            }
            if (!preg_match("/^[a-z]{5,6}$/i", $formData['role'])) { //проверка регулярных выражений
                $error['role'] = 'error'; //записываем несовпадение правилам
            }
            
            //создаем массив данных из пунктов где нажаты галочки
            foreach ($formData as $data => $value) {
                if (preg_match("/check/", $data)) { //ищем переменные check
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
            //если нет ошибкок
            if (!$error) {

                //Изменяем данные в базе
                $access->editAccess($id, $formData['username'], md5($formData['password']), $formData['role']);
                $allow->editAllow($check_data, $formData['username']);

                //Переходим на предыдущую страницу
                $this->_helper->redirector('access', 'setup');

                //если есть ошибки
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
        $form->submit->setLabel('Удалить');
        $form->cancel->setLabel('Отмена');
        $this->view->form = $form;
        $id = $this->getParam('id');
        
        if($id==1){ 
            echo "Пользователь не может быть удален!";
            die;
        }
        $access = new Application_Model_DbTable_Access();
        //если идет запрос POST
        if ($this->getRequest()->isPost()){
            //если подтверждаем удаление
            if  ($this->getRequest()->getPost('submit')){
                $access->deleteAccess($id);
                $this->_helper->redirector('access','setup');
            } else {
            //если отменяем удаление
            if ($this->getRequest()->getPost('cancel')){
            $this->_helper->redirector('access','setup');
            }
            }
        } else {
        //выводим дополнительные данные

      $this->view->access = $access->getaccess($id);
        }
    }
    
    public function pricesAction() {

        $prices = new Application_Model_DbTable_Prices();
        $this->view->prices = $prices->fetchAll();
    }

    public function addpricesAction() {

        $form = new Application_Form_Prices();
        $form->submit->setLabel('Добавить');
        $this->view->form = $form;
        
         // Если к нам идёт Post запрос
        if ($this->getRequest()->isPost()) {
            //принимаем данные
            $formData = $this->getRequest()->getPost();

            //Проверка валидациии    
            if ($form->isValid($formData)) {
                //Успешная валидация
                //Извлекаем данные
                $name = $form->getValue('name');
                $price = $form->getValue('price');

                //Добавляем данные в базу
                $prices = new Application_Model_DbTable_Prices();
                $prices->addPrice($name, $price);

                //Переходим на предыдущую страницу
                $this->_helper->redirector('prices','setup');
            } else {
                //Неуспешная валидация
                //Возвращаем данные в таблицу
                $form->populate($formData);
            }
        }
    }
    
        public function editpricesAction() {
        // action body
        $form = new Application_Form_Prices();
        $form->submit->setLabel('Редактировать');
        $this->view->form = $form;
        
        $prices = new Application_Model_DbTable_Prices();

        if ($this->getRequest()->isPost()) {

            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {

                $id = (int) $form->getValue('id');
                $name = $form->getValue('name');
                $price = $form->getValue('price');

                $prices->editPrice($id, $name, $price);
                //Переходим на предыдущую страницу
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
        $form->submit->setLabel('Удалить');
        $form->cancel->setLabel('Отмена');
        $this->view->form = $form;
        $id = $this->getParam('id');
        
        $prices = new Application_Model_DbTable_Prices();
        //если идет запрос POST
        if ($this->getRequest()->isPost()) {
            //если подтверждаем удаление
            if ($this->getRequest()->getPost('submit')) {

                $prices->deletePrice($id);
                $this->_helper->redirector('prices', 'setup');
            } else {
                //если отменяем удаление
                if ($this->getRequest()->getPost('cancel')) {
                    $this->_helper->redirector('prices', 'setup');
                }
            }
        }
    }

}
