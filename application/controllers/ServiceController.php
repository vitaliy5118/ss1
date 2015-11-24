<?php

/* ���������� ��� ���������� �������� "������ ����������" */

class ServiceController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
         // action body
        $service = new Application_Model_DbTable_Service();

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
        
        //��������� ���������� ������
        if ($this->getRequest()->isPost('search_catalog')) {
            $search = $this->getRequest()->getPost('search_catalog');
        } elseif ($this->getRequest()->getParam('search_catalog')) {
            $search = $this->getRequest()->getParam('search_catalog');
        }
        
        //c �������
        if ($search != '') {
           $this->view->service = $service->searchService($search);
           $dd = $service->getcountsearchService($search);
           $this->view->countshow = $dd[0]['count'];
        //��� ������ 
        } else {
            $this->view->service = $service->showService($date);
            $dd = $service->getcountService($date);
            $this->view->countshow = $dd[0]['count'];
        }

        $dd = $service->getcountService($date);
        $this->view->count = $dd[0]['count'];
        $dd = $service->getcountService();
        $this->view->allcount = $dd[0]['count'];

        $this->view->search = $search;
    }
    
    public function addAction() {

        $warehouse = new Application_Model_DbTable_Warehouse();
        $this->view->warehouse = $warehouse->fetchAll();
        $prices_data = new Application_Model_DbTable_Prices();
        $this->view->prices = $prices_data->fetchAll();

        // ���� � ��� ��� Post ������
        if ($this->getRequest()->isPost()) {

            // ��������� ������
            $check = $this->getRequest()->getPost('check');
            $data = $this->getRequest()->getPost('data');
            $prices = $this->getRequest()->getPost('prices');

            //������� ������ ������ �� ������� ��� ������ �������
            //���� ���� ������� ������� � ����������, ��������� ������ ������ "id-����������"
            if ($check) {
                foreach ($check as $check_id => $value) {

                    $check_data[$value] = $data[$value]; //������� ������ ������
                    //��� �������� �� view
                    $checked["$value"] = "checked"; //������ � ������ ������� �������

                    if (!preg_match("/^[0-9]{0,10}$/i", $check_data[$value])) { //�������� ���������� ���������
                        $error["$value"] = 'error'; //���������� ������������ ��������
                    }

                    //�������� �� ���������� ��������� � ����
                    $spare_count = $warehouse->getWarehouse($value);   //���������� ������
                    if (($spare_count['remain'] - $check_data[$value]) < 0) {   //������ ��������
                        $error["$value"] = 'error'; //���������� ������������ ��������
                    }
                }
            }

            //������� ������ ������ �� ������� ��� ������ �������
            //���� ���� ������� ������� � ����������, ��������� ������ ������ "id-����������"
            if ($prices) {
                foreach ($prices as $id => $price) {

                    $checked_price["$id"] = "checked"; //������ � ������ ������� �������

                    if (!preg_match("/^[0-9]{0,10}$/i", $prices[$id])) { //�������� ���������� ���������
                        $error_price["$id"] = 'error'; //���������� ������������ ��������
                    }
                }
            }

            //��������� ������ � input
            $client = $this->getRequest()->getPost('client');
            $number = $this->getRequest()->getPost('number');
            $claim = $this->getRequest()->getPost('claim');
            $diagnos = $this->getRequest()->getPost('diagnos');
            $work = $this->getRequest()->getPost('work');
            $status = $this->getRequest()->getPost('status');
            $name = $this->getRequest()->getPost('name');
            $spares = $this->getRequest()->getPost('spares');
            $comments = $this->getRequest()->getPost('comments');
            $counter = $this->getRequest()->getPost('counter');

            //�������� ���������� ���������
            if (!preg_match("/^[�-��-�A-Za-z0-9 \.\,\-\ \!\;\:]{3,300}$/i", $client)) {
                $error['client'] = 'error';
            }
            if (!preg_match("/^[�-��-�A-Za-z0-9 \.\,\-\ \!\;\:]{3,300}$/i", $number)) {
                $error['number'] = 'error';
            }
            if (!preg_match("/^[�-��-�A-Za-z0-9 \.\,\-\ \!\;\:]{3,300}$/i", $claim)) {
                $error['claim'] = 'error';
            }
            if (!preg_match("/^[�-��-�A-Za-z0-9 \.\,\-\ \!\;\:]{3,300}$/i", $status)) {
                $error['status'] = 'error';
            }
            if (!preg_match("/^[�-��-�A-Za-z0-9 \.\,\-\ \!\;\:]{3,300}$/i", $name)) {
                $error['name'] = 'error';
            }
            if (!preg_match("/^[�-��-�A-Za-z0-9 \.\,\-\ \!\;\:]{3,300}$/i", $diagnos)) {
                $error['diagnos'] = 'error';
            }
            if (!preg_match("/^[�-��-�A-Za-z0-9 \.\,\-\ \!\;\:]{3,300}$/i", $spares)) {
                $error['spares'] = 'error';
            }
            if (!preg_match("/^[�-��-�A-Za-z0-9 \.\,\-\ \!\;\:]{3,300}$/i", $work)) {
                $error['work'] = 'error';
            }
            if (!preg_match("/^[�-��-�A-Za-z0-9 \.\,\-\ \!\;\:]{3,300}$/i", $comments)) {
                $error['comments'] = 'error';
            }
            if (!preg_match("/^[�-��-�A-Za-z0-9 \.\,\-\ \!\;\:]{0,300}$/i", $counter)) {
                $error['counter'] = 'error';
            }

            //���� ��� �������
            if (!$error) {

                //��������� ������ ��� ������������ �������������� (���� �����)
                $serialize_price = serialize($checked_price);
                $serialize_data = serialize($check_data);

                //���������� ����������� ������ � ���� �� ���
                if ($prices) {
                    foreach ($prices as $id => $value) {

                        //�������� �� ���� �������� ������ �� "id"
                        $description = $prices_data->getPrice($id);

                        //���������� ��������� ������ �� ����� ����������� ��������
                        $work .= " || {$description['name']} - $value ���";
                    }
                }

                $warehistory = new Application_Model_DbTable_Warehistory();

                //���������� ������������� �������� � ���� �� ���
                if ($check_data) {
                    foreach ($check_data as $id => $value) {

                        //�������� �� ���� �������� ������ �� "id"
                        $spare_data = $warehouse->getWarehouse($id);

                        //���������� ��������� ������ �� ����� ����������� ��������
                        $spares.= "|| {$spare_data['serial']}-{$spare_data['name']}-{$value}�� ";

                        //������ ��������� �� ������� ��������� � �����
                        $wh_remain = $spare_data['remain'] - $value;
                        $wh_serial = $spare_data['serial'];
                        $wh_name = $spare_data['name'];
                        $wh_type = $spare_data['type'];
                        $wh_path = $spare_data['path']; //��� �������� ��� ��������
                        // �������� ����� ������ ��� ������������� ������
                        $warehouse->editWarehouse($id, $wh_serial, $wh_name, $wh_type, $wh_remain, 'unload', $wh_path);

                        //��������� ������ ��� ������� ������� ��������� �� ���������� ��������
                        $warehistory->addWarehistory($wh_serial, $wh_name, "$number-$name || ������� ������", $spare_data['remain'], $value, $wh_remain);
                    }
                }

                //��������� ������ � ����
                $service = new Application_Model_DbTable_Service();
                $service->addService($client, $number, $claim, $diagnos, $spares, $work, $status, $name, $comments, $counter, $serialize_price, $serialize_data);

                $this->_helper->redirector->gotoUrl("service/index");

                //���� ���� ������
            } else {
                $this->view->client = $client;
                $this->view->number = $number;
                $this->view->claim = $claim;
                $this->view->diagnos = $diagnos;
                $this->view->status = $status;
                $this->view->name = $name;
                $this->view->spares = $spares;
                $this->view->work = $work;
                $this->view->comments = $comments;
                $this->view->counter = $counter;

                $this->view->error_message = 'error_message';
                $this->view->error = $error;
                $this->view->checked_price = $checked_price;
                $this->view->check_data = $check_data;
                $this->view->checked = $checked;
            }
        }
    }

    public function editAction() {
        
        $id = $this->getRequest()->getParam('id');
        
        $service = new Application_Model_DbTable_Service();
        $service_data = $service->getService($id);
        
        //�������� ������ ���������� � ���������
        $service_data['spares'] = substr($service_data['spares'],0,strpos($service_data['spares'],'||'));
        //�������� ������ ���������� � �������
        $service_data['work'] = substr($service_data['work'],0,strpos($service_data['work'],'||'));
        
        //��������� ������� ��������� ������ ���������
        $warehouse = new Application_Model_DbTable_Warehouse();
        $warehouse_data = $warehouse->fetchAll();
 
        //��������� � �������� ��������� �������� ������� �������������
        if ($service_data['serialize_data']!='N;') { //��������� �� �������� �� ������ ������
            foreach (unserialize($service_data['serialize_data']) as $spares_id => $spares_value) {
                foreach ($warehouse_data as $rows) {
                    if ($rows['id'] == $spares_id) {
                        $rows['remain']+=$spares_value;
                        //������� ������ ������� �������
                        $checked["$spares_id"]='checked';
                    }
                }
            }
        }
        
        //��������� ������ �����
        $prices_obj = new Application_Model_DbTable_Prices();
        $prices_data = $prices_obj->fetchAll();
        
        
        // ���� � ��� ��� Post ������
        if ($this->getRequest()->isPost()) {

            // ��������� ������
            $check = $this->getRequest()->getPost('check');
            $data = $this->getRequest()->getPost('data');
            $prices = $this->getRequest()->getPost('prices');
            
            //������� ������ ������ �� ������� ��� ������ �������
            //���� ���� ������� ������� � ����������, ��������� ������ ������ "id-����������"
            if ($check) {
                foreach ($check as $check_id => $value) {

                    $check_data[$value] = $data[$value]; //������� ������ ������
                    //��� �������� �� view
                    $checked["$value"] = "checked"; //������ � ������ ������� �������

                    if (!preg_match("/^[0-9]{0,10}$/i", $check_data[$value])) { //�������� ���������� ���������
                        $error["$value"] = 'error'; //���������� ������������ ��������
                    }
                    
                    //�������� �� ���������� ��������� � ����
                    foreach ($warehouse_data as $rows) {
                        if ($rows['id'] == $value) {
                            $spare_count = $rows['remain']; //���������� ���������� ��������� � ������ ��������������
                        }
                    }
                    if(($spare_count-$check_data["$value"])<0){   //������ ��������
                        $error["$value"]='error'; //���������� ������������ ��������
                    } 
                  }
            }

            //������� ������ ������ �� ������� ��� ������ �������
            //���� ���� ������� ������� � ����������, ��������� ������ ������ "id-����������"
            if ($prices) {
                foreach ($prices as $id => $price) {

                    $checked_price["$id"] = "checked"; //������ � ������ ������� �������

                    if (!preg_match("/^[0-9]{0,10}$/i", $prices[$id])) { //�������� ���������� ���������
                        $error_price["$id"] = 'error'; //���������� ������������ ��������
                    }
                }
            }

            //��������� ������ � input
            $client = $this->getRequest()->getPost('client');
            $number = $this->getRequest()->getPost('number');
            $claim = $this->getRequest()->getPost('claim');
            $diagnos = $this->getRequest()->getPost('diagnos');
            $work = $this->getRequest()->getPost('work');
            $status = $this->getRequest()->getPost('status');
            $name = $this->getRequest()->getPost('name');
            $spares = $this->getRequest()->getPost('spares');
            $comments = $this->getRequest()->getPost('comments');
            $counter = $this->getRequest()->getPost('counter');

            //�������� ���������� ���������
            if (!preg_match("/^[�-��-�A-Za-z0-9 \.\,\-\ \!\;\:]{3,300}$/i", $client)) {
                $error['client'] = 'error';
            }
            if (!preg_match("/^[�-��-�A-Za-z0-9 \.\,\-\ \!\;\:]{3,300}$/i", $number)) {
                $error['number'] = 'error';
            }
            if (!preg_match("/^[�-��-�A-Za-z0-9 \.\,\-\ \!\;\:]{3,300}$/i", $claim)) {
                $error['claim'] = 'error';
            }
            if (!preg_match("/^[�-��-�A-Za-z0-9 \.\,\-\ \!\;\:]{3,300}$/i", $status)) {
                $error['status'] = 'error';
            }
            if (!preg_match("/^[�-��-�A-Za-z0-9 \.\,\-\ \!\;\:]{3,300}$/i", $name)) {
                $error['name'] = 'error';
            }
            if (!preg_match("/^[�-��-�A-Za-z0-9 \.\,\-\ \!\;\:]{3,300}$/i", $diagnos)) {
                $error['diagnos'] = 'error';
            }
            if (!preg_match("/^[�-��-�A-Za-z0-9 \.\,\-\ \!\;\:]{3,300}$/i", $spares)) {
                $error['spares'] = 'error';
            }
            if (!preg_match("/^[�-��-�A-Za-z0-9 \.\,\-\ \!\;\:]{3,300}$/i", $work)) {
                $error['work'] = 'error';
            }
            if (!preg_match("/^[�-��-�A-Za-z0-9 \.\,\-\ \!\;\:]{3,300}$/i", $comments)) {
                $error['comments'] = 'error';
            }
            if (!preg_match("/^[�-��-�A-Za-z0-9 \.\,\-\ \!\;\:]{0,300}$/i", $counter)) {
                $error['counter'] = 'error';
            }

            //���� ��� �������
            if (!$error) {  

                //��������� ������ ��� ������������ �������������� (���� �����)
                $serialize_price = serialize($checked_price);
                $serialize_data = serialize($check_data);

                //���������� ����������� ������ � ���� �� ���
                if ($prices) {
                    foreach ($prices as $id => $value) {

                        //�������� �� ���� �������� ������ �� "id"
                        $description = $prices_obj->getPrice($id);

                        //���������� ��������� ������ �� ����� ����������� ��������
                        $work .= " || {$description['name']} - $value ���";
                    }
                }

                $warehistory = new Application_Model_DbTable_Warehistory();

                //���������� ������������� �������� � ���� �� ���
                if ($check_data) {
                    
                    foreach ($check_data as $idw => $value) {

                        //�������� �� ���� �������� ������ �� "id"
                        $spare_data = $warehouse->getWarehouse($idw);

                        //���������� ��������� ������ �� ����� ����������� ��������
                        $spares.= "|| {$spare_data['serial']}-{$spare_data['name']}-{$value}�� ";

                        //������ ��������� �� ������� ��������� � �����
                        foreach ($warehouse_data as $rows) {
                            if ($rows['id'] == $idw) {
                                $remain = $rows['remain'];
                            }
                        }
                        $wh_remain = $remain - $value;
                        $wh_serial = $spare_data['serial'];
                        $wh_name = $spare_data['name'];
                        $wh_type = $spare_data['type'];
                        $wh_path = $spare_data['path']; //��� �������� ��� ��������
                        // �������� ����� ������ ��� ������������� ������
                        $warehouse->editWarehouse($idw, $wh_serial, $wh_name, $wh_type, $wh_remain, 'unload', $wh_path);

                        //��������� ������ ��� ������� ������� ��������� �� ���������� ��������
                        $warehistory->addWarehistory($wh_serial, $wh_name, "$number-$name || ������� ������ ��������������", $spare_data['remain'], $value, $wh_remain);
                    }
                }

                //��������� ������ � ����
                $service = new Application_Model_DbTable_Service();
                $id = $this->getRequest()->getParam('id');
                $service->editService($id, $client, $number, $claim, $diagnos, $spares, $work, $status, $name, $comments, $counter, $serialize_price, $serialize_data);

                $this->_helper->redirector->gotoUrl("service/index");

                //���� ���� ������
            } else {
                
                $this->view->client = $client;
                $this->view->number = $number;
                $this->view->claim = $claim;
                $this->view->diagnos = $diagnos;
                $this->view->status = $status;
                $this->view->name = $name;
                $this->view->spares = $spares;
                $this->view->work = $work;
                $this->view->comments = $comments;
                $this->view->counter = $counter;
                
                $this->view->warehouse = $warehouse_data;
                $this->view->prices = $prices_data;
                $this->view->error_message = 'error_message';
                $this->view->error = $error;
                $this->view->checked_price = $checked_price;
                $this->view->check_data = $check_data;
                $this->view->checked = $checked;
            }
//            
//        //���� ������� ���� ����, ��������� ���� �������������� �� ����
        } else {

            $this->view->client = $service_data['client'];
            $this->view->number = $service_data['number'];
            $this->view->claim = $service_data['claim'];
            $this->view->diagnos = $service_data['diagnos'];
            $this->view->status = $service_data['status'];
            $this->view->name = $service_data['name'];
            $this->view->spares = $service_data['spares'];
            $this->view->work = $service_data['work'];
            $this->view->comments = $service_data['comments'];
            $this->view->counter = $service_data['counter'];

            $this->view->warehouse = $warehouse_data;
            $this->view->prices = $prices_data;
            $this->view->checked_price = unserialize($service_data['serialize_price']);
            $this->view->check_data = unserialize($service_data['serialize_data']);
            $this->view->checked = $checked;
        }
    }

    public function deleteAction() {
        // action body
        $form = new Application_Form_DeleteService();
        $form->submit->setLabel('�������');
        $form->cancel->setLabel('������');
        $this->view->form = $form;
        $id = $this->getParam('id');
        //���� ���� ������ POST
        if ($this->getRequest()->isPost()) {
            //���� ������������ ��������
            if ($this->getRequest()->getPost('submit')) {
                //Application_Model_DbTable_Devices::deleteDevice($id);
                $service = new Application_Model_DbTable_Service();
                $service->deleteSales($id);
                $this->_helper->redirector('index');
            } else {
                //���� �������� ��������
                if ($this->getRequest()->getPost('cancel')) {
                    $this->_helper->redirector('index');
                }
            }
        } else {
            //������� �������������� ������

            $service = new Application_Model_DbTable_Service();

            $this->view->service = $service->getService($id);
        }
    }

    public function toexcelAction() {

        global $settings;
        // ���������� ����� ��� ������ � excel
        require_once('PHPExcel.php');
        // ���������� ����� ��� ������ ������ � ������� excel
        require_once('PHPExcel/Writer/Excel5.php');
        // ������� ������ ������ PHPExcel
        $xls = new PHPExcel();
        // ������������� ������ ��������� �����
        $xls->setActiveSheetIndex(0);
        // �������� �������� ����
        $sheet = $xls->getActiveSheet();
        // ����������� ����
        $sheet->setTitle('Service CoffeeService');
        $sheet->setCellValue("A1", '�');
        $sheet->setCellValue("B1", '����');
        $sheet->setCellValue("C1", '������');
        $sheet->setCellValue("D1", '�����');
        $sheet->setCellValue("E1", '��������');
        $sheet->setCellValue("F1", '������');
        $sheet->setCellValue("G1", '�������');
        $sheet->setCellValue("H1", '�������');
        $sheet->setCellValue("I1", '������');
        $sheet->setCellValue("J1", '��������');

//������ ���� ���������      
        $sheet->getStyle('A1')->getFill()->setFillType(
                PHPExcel_Style_Fill::FILL_SOLID);
        $sheet->getStyle('A1')->getFill()->getStartColor()->setRGB('EEEEEE');
        $sheet->getStyle('B1')->getFill()->setFillType(
                PHPExcel_Style_Fill::FILL_SOLID);
        $sheet->getStyle('B1')->getFill()->getStartColor()->setRGB('EEEEEE');
        $sheet->getStyle('C1')->getFill()->setFillType(
                PHPExcel_Style_Fill::FILL_SOLID);
        $sheet->getStyle('C1')->getFill()->getStartColor()->setRGB('EEEEEE');
        $sheet->getStyle('D1')->getFill()->setFillType(
                PHPExcel_Style_Fill::FILL_SOLID);
        $sheet->getStyle('D1')->getFill()->getStartColor()->setRGB('EEEEEE');
        $sheet->getStyle('E1')->getFill()->setFillType(
                PHPExcel_Style_Fill::FILL_SOLID);
        $sheet->getStyle('E1')->getFill()->getStartColor()->setRGB('EEEEEE');
        $sheet->getStyle('F1')->getFill()->setFillType(
                PHPExcel_Style_Fill::FILL_SOLID);
        $sheet->getStyle('F1')->getFill()->getStartColor()->setRGB('EEEEEE');
        $sheet->getStyle('G1')->getFill()->setFillType(
                PHPExcel_Style_Fill::FILL_SOLID);
        $sheet->getStyle('G1')->getFill()->getStartColor()->setRGB('EEEEEE');
        $sheet->getStyle('H1')->getFill()->setFillType(
                PHPExcel_Style_Fill::FILL_SOLID);
        $sheet->getStyle('H1')->getFill()->getStartColor()->setRGB('EEEEEE');
        $sheet->getStyle('I1')->getFill()->setFillType(
                PHPExcel_Style_Fill::FILL_SOLID);
        $sheet->getStyle('I1')->getFill()->getStartColor()->setRGB('EEEEEE');
        $sheet->getStyle('J1')->getFill()->setFillType(
                PHPExcel_Style_Fill::FILL_SOLID);
        $sheet->getStyle('J1')->getFill()->getStartColor()->setRGB('EEEEEE');

        $sheet->getStyle('A')->getAlignment()->setHorizontal(
                PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

// �������������� ������ ������
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getColumnDimension('J')->setAutoSize(true);
// ������ ������ ���������
        $sheet->getStyle('A')->getNumberFormat()
                ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $sheet->getStyle('B')->getNumberFormat()
                ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $sheet->getStyle('C')->getNumberFormat()
                ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $sheet->getStyle('D')->getNumberFormat()
                ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $sheet->getStyle('E')->getNumberFormat()
                ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $sheet->getStyle('F')->getNumberFormat()
                ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $sheet->getStyle('G')->getNumberFormat()
                ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $sheet->getStyle('H')->getNumberFormat()
                ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $sheet->getStyle('I')->getNumberFormat()
                ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $sheet->getStyle('J')->getNumberFormat()
                ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);

        $service = new Application_Model_DbTable_Service();
        $data = $service->fetchAll()->toArray();

        $i = 2;

        foreach ($data as $rows) {
            $number = $i - 1;
            $sheet->setCellValue("A$i", "$number");
            $sheet->setCellValue("B$i", "$rows[date]");
            $sheet->setCellValue("C$i", "$rows[client]");
            $sheet->setCellValue("D$i", "$rows[number]");
            $sheet->setCellValue("E$i", "$rows[name]");
            $sheet->setCellValue("F$i", "$rows[claim]");
            $sheet->setCellValue("G$i", "$rows[diagnos]");
            $sheet->setCellValue("H$i", "$rows[counter]");
            $sheet->setCellValue("I$i", "$rows[work]");
            $sheet->setCellValue("J$i", "$rows[spares]");
            $i++;
        }

// ������� ���������� �����
        $objWriter = new PHPExcel_Writer_Excel5($xls);
        $objWriter->save('service.xls');
        
        // ��������� ���� � �������� ������
        header("Location: http://{$settings['excel']['site']}/service.xls");
    }
   
    public function invoiceAction() {

        // ���������� ����� ��� ������ � excel
        require_once('PHPExcel.php');
        // ���������� ����� ��� ������ ������ � ������� excel
        require_once('PHPExcel/Writer/Excel5.php');

        global $settings;
        
        //�������� ��� ������ �� ������� �� ���� ������
        $service = new Application_Model_DbTable_Service();
        $id = $this->getRequest()->getParam('id');
        $num = $this->getRequest()->getParam('num');
        
        //������ ������ � �������
        $data = $service->getService($id);
        
        //��������� ������� �������
        // ������� ������ ������ PHPExcel
        $xls = new PHPExcel();
        // ������������� ������ ��������� �����
        $xls->setActiveSheetIndex(0);
        // �������� �������� ����
        $sheet = $xls->getActiveSheet();
        // ����������� ����
        $sheet->setTitle('Service CoffeeService');
        
        // ���������� ������ � ������
        $date = date('d-m-Y');
        
        //����������� �����
        $sheet->mergeCells('A1:F1')->setCellValue("A1", "��������� �$num �� $date ����");
        
        // ������������ ������
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(
        PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        
        //������ ������ � ��� ������
        $sheet->getStyle('A1')->getFont()->setName('Arial')->setSize(14); 
        $sheet->getStyle('B3')->getFont()->setName('Arial')->setSize(12); 
        $sheet->getStyle('B4')->getFont()->setName('Arial')->setSize(12); 
        $sheet->getStyle('B6')->getFont()->setName('Arial')->setSize(11); 
        
        //����������� �����
        $sheet->mergeCells('B2:F2');
        $sheet->mergeCells('B3:F3')->setCellValue("B3", $settings['excel']['name']);
        $sheet->mergeCells('B4:F4')->setCellValue("B4", "������: {$data['client']}");
        $sheet->mergeCells('B6:F6')->setCellValue("B6", "�����������: {$data['name']}   �������� �����: {$data['number']}");

        //������ ������ ������
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(48);
        
        //������������ ������ � ������
        $sheet->getStyle('A')->getAlignment()->setHorizontal(
            PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B8')->getAlignment()->setHorizontal(
            PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('C8')->getAlignment()->setHorizontal(
            PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D8')->getAlignment()->setHorizontal(
            PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E8')->getAlignment()->setHorizontal(
            PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('F8')->getAlignment()->setHorizontal(
            PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        //��������� �������� �������� �������
        $sheet->setCellValue("A8", '�');
        $sheet->setCellValue("B8", '������/��������');
        $sheet->setCellValue("C8", '�-��');
        $sheet->setCellValue("D8", '��.');
        $sheet->setCellValue("E8", '����');
        $sheet->setCellValue("F8", '�����');
        
        //������������� ������
        $spares = unserialize($data['serialize_data']);
        $prices = unserialize($data['serialize_price']);
        
        $warehouse = new Application_Model_DbTable_Warehouse();
        $prices_base = new Application_Model_DbTable_Prices();

        $i = 9;

        foreach ($spares as $id => $value) {
            $number = $i - 8;
            $spare_data = $warehouse->getWarehouse($id);
            
            $sheet->setCellValue("A$i", "$number");
            $sheet->setCellValue("B$i", "$spare_data[name]");
            $sheet->setCellValue("C$i", "$value.000");
            $sheet->setCellValue("D$i", "��.");
            $sheet->setCellValue("E$i", "$spare_data[price]");
            
            $sum = $spare_data['price']*$value;
            $sheet->setCellValue("F$i", "$sum");
            
            $final+=$sum;
            $i++;
        }
        
        foreach ($prices as $id => $value) {
            $number = $i-8;
            $prices_data = $prices_base->getPrice($id);
            
            $sheet->setCellValue("A$i", "$number");
            $sheet->setCellValue("B$i", "$prices_data[name]");
            $sheet->setCellValue("C$i", "1.000");
            $sheet->setCellValue("D$i", "��.");
            $sheet->setCellValue("E$i", "$prices_data[price]");
            $sheet->setCellValue("F$i", "$prices_data[price]");
            
            $final+=$prices_data[price];
            $i++;
        }
        
        $i+=2;

        $num = $i-11;
        $sheet->setCellValue("F$i", "����� $final ���");
        $sheet->setCellValue("A$i", "����� ������������ $num, �� ����� $final ���");
        $sheet->getStyle("F$i")->getAlignment()->setHorizontal(
            PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle("A$i")->getAlignment()->setHorizontal(
            PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        
        // ������ ������
        $style_wrap = array(
            // �����
            'borders' => array(
                // ������� �����
                'outline' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THICK,
                    'color' => array(
                        'rgb' => '#000000'
                    )
                ),
                // ����������
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array(
                        'rgb' => '#000000'
                    )
                )
            )
        );

        $sheet->getStyle('A8:F' . ($i-3))->applyFromArray($style_wrap);
        
        $i+=3;
        $sheet->mergeCells("B$i:F$i")->setCellValue("B$i", '�������� ___________________________      ������� __________________________________');

        // ������� ���������� �����
        $objWriter = new PHPExcel_Writer_Excel5($xls);
        $objWriter->save('invoice.xls');
        
        // ��������� ���� � �������� ������
        header("Location: http://{$settings['excel']['site']}/invoice.xls");
    }
}
