<?php

class Zend_Controller_Action_Helper_Setup extends
Zend_Controller_Action_Helper_Abstract {

    public $actionName;

    public $tableName;
    protected $url;

    /**
     * @var \Zend_View 
     */
    private $view;

    public function setView(\Zend_View $view) {
        $this->view = $view;
    }

    public function ShowData() {
        $model = $this->setModel();
        $this->view->{$this->actionName} = $model->fetchAll()->toArray();
    }

    public function addData() {

        $form = $this->setForm();
        $form->submit->setLabel('Добавить');

        if ($this->getRequest()->isPost()) {
            $formdata = $this->getRequest()->getPost();
            if ($form->isValid($formdata)) {
                $this->setTableName();
                $data = $form->getValue("$this->tableName");

                $model = $this->setModel();
                $model->addSetup($data);

                $_redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
                $_redirector->gotoUrl($this->url);
            } else {
                $form->populate($formdata);
            }
        }
        $this->view->form = $form;
    }

    public function editData() {
        
        $form = $this->setForm();
        $form->submit->setLabel('Редактировать');
        $this->view->form = $form;

        $id = $this->getRequest()->getParam('id');
        $model = $this->setModel();

        if ($this->getRequest()->isPost()) {

            if ($form->isValid($this->getRequest()->getPost())) {

                $data = $form->getValue("$this->tableName");
                $model->editSetup($id, $data);
                
                $_redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
                $_redirector->gotoUrl($this->url);
            } else {
                $form->populate($this->getRequest()->getPost());
            }
        } else {

            $form->populate($model->getSetup($id));
        }
    }
   
    public function deleteData() {
        
        // action body
        $form = new Application_Form_DeleteSetup();
        $form->submit->setLabel('Удалить');
        $form->cancel->setLabel('Отмена');
        $this->view->form = $form;
        $id = $this->getRequest()->getParam('id');
        
        $model = $this->setModel();
        //если идет запрос POST
        if ($this->getRequest()->isPost()) {
            //если подтверждаем удаление
            if ($this->getRequest()->getPost('submit')) {
                $model->deleteSetup($id);
                $_redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
                $_redirector->gotoUrl($this->url);
            } elseif ($this->getRequest()->getPost('cancel')) {
                $_redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
                $_redirector->gotoUrl($this->url);
                
            }
        } else {
            //выводим дополнительные данные

            $this->view->data = $model->getSetup($id);
        }
    }

    public function setModel() {

        $this->setTableName();
        $model = new Application_Model_DbTable_Setup();
        $model->setTableName($this->tableName);

        return $model;
    }

    public function setTableName() {

        $this->actionName = Zend_Controller_Front::getInstance()->getRequest()->getActionName();
        if ($this->actionName == 'names' || $this->actionName == 'addname' ||
                $this->actionName == 'editname' || $this->actionName == 'deletename') {
            $this->tableName = 'name';
        } elseif ($this->actionName == 'owners' || $this->actionName == 'addowner' ||
                $this->actionName == 'editowner' || $this->actionName == 'deleteowner') {
            $this->tableName = 'owner';
        }elseif ($this->actionName == 'users' || $this->actionName == 'adduser' ||
                $this->actionName == 'edituser' || $this->actionName == 'deleteuser') {
            $this->tableName = 'user';
        }elseif ($this->actionName == 'status' || $this->actionName == 'addstatus' ||
                $this->actionName == 'editstatus' || $this->actionName == 'deletestatus') {
            $this->tableName = 'status';
        }
        
        if($this->tableName == 'status'){
        $this->url = "/setup/{$this->tableName}";
        } else {
        $this->url = "/setup/{$this->tableName}s";    
        }
    }

    public function setForm() {

        $this->actionName = Zend_Controller_Front::getInstance()->getRequest()->getActionName();

        if ($this->actionName == 'addname' || $this->actionName == 'editname') {
            $form = new Application_Form_Names();
        } elseif ($this->actionName == 'addowner' || $this->actionName == 'editowner') {
            $form = new Application_Form_Owners();
        } elseif ($this->actionName == 'adduser' || $this->actionName == 'edituser') {
            $form = new Application_Form_Users();
        } elseif ($this->actionName == 'addstatus' || $this->actionName == 'editstatus') {
            $form = new Application_Form_Status();
        }


        return $form;
    }

}
