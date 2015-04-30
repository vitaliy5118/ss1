<?php

class AccessController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function loginAction() {
        $form = new Application_Form_Access();
        $form->submit->setLabel('����');

        if ($this->getRequest()->isPost()) {
            // ���� ��������� ������ �������
            if ($form->isValid($this->getRequest()->getPost())) {
 
                // �������� ������� ����������� � ���� ������
                $authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Db_Table::getDefaultAdapter());
                $authAdapter->setTableName('access')
                        ->setIdentityColumn('username')
                        ->setCredentialColumn('password');

                $username = $this->getRequest()->getPost('username');
                $password = $this->getRequest()->getPost('password');

                // ����������� ���������� ������ �� �����
                $authAdapter->setIdentity($username)
                        ->setCredential($password);

                // �������� ��������� Zend_Auth
                $auth = Zend_Auth::getInstance();

                // ������ ������� �������������� ������������
                $result = $auth->authenticate($authAdapter);

                // ���� ����������� ������ �������
                if ($result->isValid()) {
                    // ���������� ������� ��� ���������� ���������� ������ � ������������
                    $identity = $authAdapter->getResultRowObject();

                    // �������� ������ � ��������� ������ Zend
                    $authStorage = $auth->getStorage();

                    // �������� ���� ���������� � ������������,
                    // ����� ����� � ��� ������ ��� ���������������� Acl
                    $authStorage->write($identity);

                    //�������� �� �������� ��������
                    $this->_helper->redirector('index','index');
                } else {
                    $this->view->message = '�� ������ ������ �����������! ��������� �������!';
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

