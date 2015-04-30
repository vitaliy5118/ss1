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
       
        //обработка параметров поиска
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
         
        //вывод навигации
        $this->_helper->navigation->initnav($sort, $select_limit, $page, $data_array);
         
        $sort = $this->_helper->navigation->sortby();
        $select_limit = $this->_helper->navigation->selectlimit();
        $page = $this->_helper->navigation->page();
        $count = $select_limit;
        $offset = $page*$select_limit-$select_limit;
        
        //формируем запрос
        if($search != ''){
            $select = $warehouse->searchWarehouse($search)->order($sort)->limit($count, $offset);
            $this->view->search_param = "/search_catalog/$search";
        } else {
            $select = $warehouse->select()->order($sort)->limit($count, $offset);    
        }
        //Выводим результат
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
        // Создаём форму
        $form = new Application_Form_Warehouse();

        // Указываем текст для submit
        $form->submit->setLabel('Добавить');

        // Передаём форму в view
        $this->view->form = $form;

        // Если к нам идёт Post запрос
        if ($this->getRequest()->isPost()) {
            // Принимаем его
            $formData = $this->getRequest()->getPost();

            // Если форма заполнена верно
            if ($form->isValid($formData)) {
                // Извлекаем данные
                $serial = $form->getValue('serial');
                $name = $form->getValue('name');
                $type = $form->getValue('type');
                $remain = $form->getValue('remain');
                $lastadding = $form->getValue('lastadding');

                // Создаём объект модели
                $warehouse = new Application_Model_DbTable_Warehouse();

                // Вызываем метод модели addMovie для вставки новой записи
                $warehouse->addWarehouse($serial, $name, $type, $remain, $lastadding);
                
               // Используем библиотечный helper для редиректа на action = index
                $this->_helper->redirector('index');
            } else {
                // Если форма заполнена неверно,
                // используем метод populate для заполнения всех полей
                // той информацией, которую ввёл пользователь
                $form->populate($formData);
            }
        }
    }
    
    public function editAction()
    {
        // Создаём форму
        $form = new Application_Form_Warehouse();

        // Указываем текст для submit
        $form->submit->setLabel('Редактировать');

        // Передаём форму в view
        $this->view->form = $form;

        // Если к нам идёт Post запрос
        if ($this->getRequest()->isPost()) {
            // Принимаем его
            $formData = $this->getRequest()->getPost();

            // Если форма заполнена верно
            if ($form->isValid($formData)) {
                // Извлекаем данные
                $id = (int) $form->getValue('id');
                $serial = $form->getValue('serial');
                $name = $form->getValue('name');
                $type = $form->getValue('type');
                $remain = $form->getValue('remain');
                $lastadding = $form->getValue('lastadding');

                // Создаём объект модели
                $warehouse = new Application_Model_DbTable_Warehouse();

                // Вызываем метод модели addMovie для вставки новой записи
                $warehouse->editWarehouse($id, $serial, $name, $type, $remain, $lastadding);

                // Используем библиотечный helper для редиректа на action = index
                $this->_helper->redirector('index');
            } else {
                // Если форма заполнена неверно,
                // используем метод populate для заполнения всех полей
                // той информацией, которую ввёл пользователь
                $form->populate($formData);
            }
        } else {
            // Если мы выводим форму, то получаем id фильма, который хотим обновить
            $id = $this->_getParam('id', 0);

            if ($id > 0) {
                // Создаём объект модели
                $warehouse = new Application_Model_DbTable_Warehouse();

                // Заполняем форму информацией при помощи метода populate
                $form->populate($warehouse->getWarehouse($id));
            }
        }
    }
    
       public function deleteAction() {
        // Если к нам идёт Post запрос
        if ($this->getRequest()->isPost()) {
            // Принимаем значение
            $del = $this->getRequest()->getPost('del');

            // Если пользователь подтвердил своё желание удалить запись
            if ($del == 'Да') {
                // Принимаем id записи, которую хотим удалить
                $id = $this->getRequest()->getPost('id');
                // Создаём объект модели
                $warehouse = new Application_Model_DbTable_Warehouse();

                // Вызываем метод модели deleteMovie для удаления записи
                $warehouse->deleteWarehouse($id);
            }

            // Используем библиотечный helper для редиректа на action = index
            $this->_helper->redirector('index');
        } else {
            // Если запрос не Post, выводим сообщение для подтверждения
            // Получаем id записи, которую хотим удалить
            $id = $this->_getParam('id');

            // Создаём объект модели
            $warehouse = new Application_Model_DbTable_Warehouse();

            // Достаём запись и передаём в view
            $this->view->warehouse = $warehouse->getWarehouse($id);
        }
    }


}


    


