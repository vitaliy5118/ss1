<?php

class RepairsController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
        // action body
        $repairs = new Application_Model_DbTable_Repairs();
        //Выводим результат
        $number = $this->getRequest()->getParam('number');
        $select = $repairs->select()->where("number = '$number'");

        $this->view->repairs = $repairs->fetchAll($select)->toArray();
        $this->view->number = $number;
    }

    public function statisticAction() {
        // action body
        $repairs = new Application_Model_DbTable_Repairs();

        if ($this->getRequest()->isPost('select_date')) {
           
            $year = $this->getRequest()->getPost('select_year');
            $month = $this->getRequest()->getPost('select_month');
            
            $_SESSION['year'] = $year;
            $_SESSION['month'] = $month;
  
        } else {
            $currend_date = new Zend_Date();
            $ss = $currend_date->toArray();

            $year = $ss['year'];

            $month = (int) $ss['month'];

            if ($month < 10) {
                $month = "0$month";
            }
            
            $_SESSION['year'] = $year;
            $_SESSION['month'] = $month;
        }


        $date = "$year-$month";

        $this->view->repairs = $repairs->statisticRepairs($date);
        $dd = $repairs->getcountRepairs($date);
        $this->view->count = $dd[0]['count'];
        $dd = $repairs->getcountRepairs();
        $this->view->allcount = $dd[0]['count'];
    }

    public function addAction() {
        // Создаём форму
        $form = new Application_Form_Repair();
        $form->submit->setLabel('Добавить');

        $number = $this->getRequest()->getParam('number');
        $form->number->setValue($number);
        // Передаём форму в view
        $this->view->number = $number;
        $this->view->form = $form;

        // Если к нам идёт Post запрос
        if ($this->getRequest()->isPost()) {
            // Принимаем его
            $formData = $this->getRequest()->getPost();

            // Если форма заполнена верно
            if ($form->isValid($formData)) {
                // Извлекаем данные
                $number = $form->getValue('number');
                $claim = $form->getValue('claim');
                $diagnos = $form->getValue('diagnos');
                $spares = $form->getValue('spares');
                $work = $form->getValue('work');
                $comments = $form->getValue('comments');

                // Создаём объект модели
                $repaire = new Application_Model_DbTable_Repairs();

                // Вызываем метод модели addMovie для вставки новой записи
                $repaire->addRepaire($number, $claim, $diagnos, $spares, $work, $comments);

                // Используем библиотечный helper для редиректа на action = index
                $this->_helper->redirector->gotoUrl("repairs/index/number/$number");
            } else {
                // Если форма заполнена неверно,
                // используем метод populate для заполнения всех полей
                // той информацией, которую ввёл пользователь
                $form->populate($formData);
            }
        }
    }

    public function editAction() {
        // Создаём форму
        $form = new Application_Form_Repair();

        // Указываем текст для submit
        $form->submit->setLabel('Редактировать');

        $id = $this->getRequest()->getParam('id');
        $form->id->setValue($id);

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
                $data = $form->getValue('data');
                $claim = $form->getValue('claim');
                $diagnos = $form->getValue('diagnos');
                $work = $form->getValue('work');
                $spares = $form->getValue('spares');
                $comments = $form->getValue('comments');
                $number = $form->getValue('number');

                // Создаём объект модели
                $repaire = new Application_Model_DbTable_Repairs();

                // Вызываем метод модели addMovie для вставки новой записи
                $repaire->editRepaire($id, $number, $data, $claim, $diagnos, $spares, $work, $comments);

                // Используем библиотечный helper для редиректа на action = index
                $this->_helper->redirector->gotoUrl("repairs/index/number/$number");
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
                $repaire = new Application_Model_DbTable_Repairs();

                // Заполняем форму информацией при помощи метода populate
                $form->populate($repaire->getRepaire($id));
            }
        }
    }

}
