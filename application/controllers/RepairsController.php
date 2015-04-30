<?php

class RepairsController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
        // action body
        $repairs = new Application_Model_DbTable_Repairs();
        //������� ���������
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
        // ������ �����
        $form = new Application_Form_Repair();
        $form->submit->setLabel('��������');

        $number = $this->getRequest()->getParam('number');
        $form->number->setValue($number);
        // ������� ����� � view
        $this->view->number = $number;
        $this->view->form = $form;

        // ���� � ��� ��� Post ������
        if ($this->getRequest()->isPost()) {
            // ��������� ���
            $formData = $this->getRequest()->getPost();

            // ���� ����� ��������� �����
            if ($form->isValid($formData)) {
                // ��������� ������
                $number = $form->getValue('number');
                $claim = $form->getValue('claim');
                $diagnos = $form->getValue('diagnos');
                $spares = $form->getValue('spares');
                $work = $form->getValue('work');
                $comments = $form->getValue('comments');

                // ������ ������ ������
                $repaire = new Application_Model_DbTable_Repairs();

                // �������� ����� ������ addMovie ��� ������� ����� ������
                $repaire->addRepaire($number, $claim, $diagnos, $spares, $work, $comments);

                // ���������� ������������ helper ��� ��������� �� action = index
                $this->_helper->redirector->gotoUrl("repairs/index/number/$number");
            } else {
                // ���� ����� ��������� �������,
                // ���������� ����� populate ��� ���������� ���� �����
                // ��� �����������, ������� ��� ������������
                $form->populate($formData);
            }
        }
    }

    public function editAction() {
        // ������ �����
        $form = new Application_Form_Repair();

        // ��������� ����� ��� submit
        $form->submit->setLabel('�������������');

        $id = $this->getRequest()->getParam('id');
        $form->id->setValue($id);

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
                $data = $form->getValue('data');
                $claim = $form->getValue('claim');
                $diagnos = $form->getValue('diagnos');
                $work = $form->getValue('work');
                $spares = $form->getValue('spares');
                $comments = $form->getValue('comments');
                $number = $form->getValue('number');

                // ������ ������ ������
                $repaire = new Application_Model_DbTable_Repairs();

                // �������� ����� ������ addMovie ��� ������� ����� ������
                $repaire->editRepaire($id, $number, $data, $claim, $diagnos, $spares, $work, $comments);

                // ���������� ������������ helper ��� ��������� �� action = index
                $this->_helper->redirector->gotoUrl("repairs/index/number/$number");
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
                $repaire = new Application_Model_DbTable_Repairs();

                // ��������� ����� ����������� ��� ������ ������ populate
                $form->populate($repaire->getRepaire($id));
            }
        }
    }

}
