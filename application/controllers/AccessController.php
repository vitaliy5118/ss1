<?php

class AccessController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function loginAction() {
        $form = new Application_Form_Access();
        $form->submit->setLabel('Вход');

        if ($this->getRequest()->isPost()) {
            // Если валидация прошла успешно
            if ($form->isValid($this->getRequest()->getPost())) {
 
                // Получаем адаптер подключения к базе данных
                $authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Db_Table::getDefaultAdapter());
                $authAdapter->setTableName('access')
                        ->setIdentityColumn('username')
                        ->setCredentialColumn('password');

                $username = $this->getRequest()->getPost('username');
                $password = $this->getRequest()->getPost('password');

                // подставляем полученные данные из формы
                $authAdapter->setIdentity($username)
                        ->setCredential($password);

                // получаем экземпляр Zend_Auth
                $auth = Zend_Auth::getInstance();

                // делаем попытку авторизировать пользователя
                $result = $auth->authenticate($authAdapter);

                // если авторизация прошла успешно
                if ($result->isValid()) {
                    // используем адаптер для извлечения оставшихся данных о пользователе
                    $identity = $authAdapter->getResultRowObject();

                    // получаем доступ к хранилищу данных Zend
                    $authStorage = $auth->getStorage();

                    // помещаем туда информацию о пользователе,
                    // чтобы иметь к ним доступ при конфигурировании Acl
                    $authStorage->write($identity);

                    //редирект на основную страницу
                    $this->_helper->redirector('index','index');
                } else {
                    $this->view->message = 'Не верные данные авторизации! Повторите попытку!';
                }
            }
        }
        $this->view->form = $form;
    }

    public function logoutAction() {
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();
        $this->_helper->redirector('login');
    }

}

