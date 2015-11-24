<?php

class StatisticController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
        
        //подсчет обсчего количества аппаратов в базе
        $devices = new Application_Model_DbTable_Devices();
        $dd = $devices->getCountDevices();
        $this->view->devices = $dd[0]['count'];
       
        //подсчет обсчего количества ремонтов в базе
        $repairs = new Application_Model_DbTable_Repairs();
        $dd = $repairs->getCountRepairs();
        $this->view->repairs = $dd[0]['count'];
        
        //передача в индекс списка имен аппаратов
        $devices_list = new Application_Model_DbTable_Setup();
        $devices_list->setTableName('name');
        $devices_names = $devices_list->fetchAll()->toArray();
        $this->view->devices_list = $devices_names;
        $this->view->repairs_list = $devices_names;

        //передача в индекс списка имен аппаратов
        $types_list = new Application_Model_DbTable_Setup();
        $types_list->setTableName('type');
        $devices_types = $types_list->fetchAll()->toArray();
        $this->view->devices_types = $devices_types;
        
        //передача в индекс списка статусов аппаратов
        $status_list = new Application_Model_DbTable_Setup();
        $status_list->setTableName('status');
        $devices_status = $status_list->fetchAll()->toArray();
        $this->view->status_list = $devices_status;
        
        //передача в индекс списка принадлежности аппаратов
        $owner_list = new Application_Model_DbTable_Setup();
        $owner_list->setTableName('owner');
        $devices_owner = $owner_list->fetchAll()->toArray();
        $this->view->owner_list = $devices_owner;
        
        //передача в индекс списка пользователей аппаратов
        $user_list = new Application_Model_DbTable_Setup();
        $user_list->setTableName('user');
        $devices_user = $user_list->fetchAll()->toArray();
        $this->view->user_list = $devices_user;
       
        //проверка селектов
        if($this->getRequest()->isPost()){
           if($this->getRequest()->getPost('select_device')){
               $name = $this->getRequest()->getPost('select_device');
               $type = $this->getRequest()->getPost('select_type');
               $status = $this->getRequest()->getPost('select_status');
               $owner = $this->getRequest()->getPost('select_owner');
               $user = $this->getRequest()->getPost('select_user');
               $repair_name = $this->getRequest()->getPost('select_repair');
           } else {
                 $name = $devices_names[0]['name'];
                 $type = $devices_types[0]['type'];
                 $status = $devices_status[0]['status'];
                 $owner = $devices_owner[0]['owner'];
                 $user = $devices_user[0]['user'];
                 $repair_name = $devices_names[0]['name'];
           }

        } else {
          $name = $devices_names[0]['name'];
          $type = $devices_types[0]['type'];
          $status = $devices_status[0]['status'];
          $owner = $devices_owner[0]['owner'];
          $user = $devices_user[0]['user'];
          $repair_name = $devices_names[0]['name'];
        }
        
        //передача в индекс количестка аппаратов с именем
        $dd = $devices->getCountDevices($name);
        $this->view->count_names = $dd[0]['count'];
        $this->view->name = $name;

        //передача в индекс количестка аппаратов с именем
        $dd = $devices->getCountDevices($type);
        $this->view->count_types = $dd[0]['count'];
        $this->view->type = $type;
        
        //передача в индекс количестка аппаратов со статусом 
        $dd = $devices->getCountDevices($status);
        $this->view->count_status = $dd[0]['count'];
        $this->view->status = $status;
        
        //передача в индекс количестка аппаратов с принадлежностью
        $dd = $devices->getCountDevices($owner);
        $this->view->count_owner = $dd[0]['count'];
        $this->view->owner = $owner;
        
        //передача в индекс количестка аппаратов с пользователем
        $dd = $devices->getCountDevices($user);
        $this->view->count_user = $dd[0]['count'];
        $this->view->user = $user;

        //передача в индекс количестка аппаратов с пользователем
        $dd = $repairs->getCountRepairsbyName($repair_name);
        $this->view->count_repair_name = $dd[0]['count'];
        $this->view->repair_name = $repair_name;
        
        //принимаем данные о визитерах с базы
        $visitor = new Application_Model_DbTable_Visitors();
        $visitors = $visitor->fetchAll();
        
        $unique_users=0;
        $visits=0;

        //сравниваем текущего визитера с визитерами в базе
        foreach($visitors as $rows){
            $unique_users++;
            $visits +=$rows['visits'];
        }
        
        $this->view->unique_users = $unique_users;
        $this->view->visits = $visits;
    }

}

