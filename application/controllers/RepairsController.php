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
        $select = $repairs->select()->where("number = '$number'")->order('date DESC');

        $this->view->repairs = $repairs->fetchAll($select)->toArray();
        $this->view->number = $number;
        
        $devices = new Application_Model_DbTable_Devices();
        $device = $devices->getName($number);
        $this->view->name=$device['name']; //�������� ��������
        
        $dd = $repairs->getCountRepairsbyNumber($number);
        $this->view->count = $dd[0]['count'];
        $dd = $repairs->getcountRepairs();
        $this->view->allcount = $dd[0]['count'];
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
        $number = $this->getRequest()->getParam('number');
        $this->view->number = $number; //��������� ����� ��������
        
        
        $devices = new Application_Model_DbTable_Devices();
        $device = $devices->getName($number);
        $this->view->name=$device['name']; //�������� ��������
        
        $status  = new Application_Model_DbTable_Setup();
        $status->setTableName('status');
        $status_values = $status->getValues();
        $this->view->status = $status_values; //��������� ������ ��������
        $this->view->status_ch = $device['status']; //������� ������ ��������
 
        $warehouse = new Application_Model_DbTable_Warehouse();
        $this->view->warehouse = $warehouse->fetchAll(); //��������� ������ ������
        

        // ���� � ��� ��� Post ������
        if ($this->getRequest()->isPost()) {

            // ��������� ���
            $formData = $this->getRequest()->getPost(); //��������� ������

            //������� ������ ������ �� ������� ��� ������ �������
            foreach ($formData as $data => $value ){
                if (preg_match("/check/", $data)) { //���� ���������� check
                    $check_data[$value] = $formData["$value"]; //������� ������ ������
                    $checked["$value"] = "checked"; //������ � ������ ������� �������
                   
                    if (!preg_match("/^[0-9]{0,10}$/i",$formData["$value"])) { //�������� ���������� ���������
                     
                       $error["$value"]='error'; //���������� ������������ ��������
                    }
                    
                    //�������� �� ���������� ��������� � ����
                    $spare_count= $warehouse->getWarehouse($value);   //���������� ������
                    if(($spare_count['remain']-$formData["$value"])<0){   //������ ��������
                        $error["$value"]='error'; //���������� ������������ ��������
                    } 
                }
            }
            //��������� ������
            $claim = $this->getRequest()->getPost('claim');
            $diagnos = $this->getRequest()->getPost('diagnos');
            $spares = $this->getRequest()->getPost('spares');
            $work = $this->getRequest()->getPost('work');
            $comments = $this->getRequest()->getPost('comments');
            $counter = $this->getRequest()->getPost('counter');
            $status = $this->getRequest()->getPost('status');
            
            //�������� ���������� ���������
            if (!preg_match("/^[�-��-�A-Za-z0-9 \.\,\-\ \!\;\:]{3,300}$/i",$claim))   {$error['claim']='error';}
            if (!preg_match("/^[�-��-�A-Za-z0-9 \.\,\-\ \!\;\:]{3,300}$/i",$diagnos)) {$error['diagnos']='error';}
            if (!preg_match("/^[�-��-�A-Za-z0-9 \.\,\-\ \!\;\:]{3,300}$/i",$spares))  {$error['spares']='error';}
            if (!preg_match("/^[�-��-�A-Za-z0-9 \.\,\-\ \!\;\:]{3,300}$/i",$work))    {$error['work']='error';}
            if (!preg_match("/^[�-��-�A-Za-z0-9 \.\,\-\ \!\;\:]{3,300}$/i",$comments)){$error['comments']='error';}
            if (!preg_match("/^[�-��-�A-Za-z0-9 \.\,\-\ \!\;\:]{0,300}$/i",$counter)) {$error['counter']='error';}
            if (!preg_match("/^[�-��-�A-Za-z0-9 \.\,\-\ \!\;\:]{3,300}$/i",$counter)) {$error['status']='error';}
            

            //���� ��� �������
            if (!$error){

                if($spares!=''){
                    $spares.=' ';
                }
                if($check_data) {
                    
                    $warehouse = new Application_Model_DbTable_Warehouse();
                    $warehistory = new Application_Model_DbTable_Warehistory();
                    
                    foreach ($check_data as $id => $value){
                        $spare_data = $warehouse->getWarehouse($id);
                        $spares.= "|| {$spare_data['serial']}-{$spare_data['name']}-{$value}�� ";
                    
                        $row = $warehouse->getWarehouse($id);
                        
                        $remain = $row['remain'] - $value;
                        $serial = $row['serial'];
                        $name = $row['name'];
                        $type = $row['type'];
                        $path = $row['path']; //��� �������� ��� ��������
                        $price = 'unload';

                        // �������� ����� ������ addMovie ��� ������� ����� ������
                        $warehouse->editWarehouse($id, $serial, $name, $type, $remain, $price, $path);
                        
                        $warehistory->addWarehistory($serial, $name, "{$number}-{$device['name']}",$row['remain'],$value, $remain);
                    }
                }
                
                $serialize_data = serialize($check_data);
                $serialize_checked = serialize($checked);
               
                $repaire = new Application_Model_DbTable_Repairs();
                $repaire->addRepaire($number, $claim, $diagnos, $spares, $work, $comments, $counter, $serialize_data, $serialize_checked);
                
                $devices->editDeviceStatus($number, $status);
                
                
                $device_data['number'] = $number;
                $device_data['name'] = '������';
                $device_data['type'] = '������';
                $device_data['owner'] = '������';
                $device_data['user'] = '������';
                $device_data['status'] = $status;
                $device_data['city'] = '������';
                $device_data['adress'] = '������';
                $device_data['tt_name'] = '������';
                $device_data['tt_user'] = '������';
                $device_data['tt_phone'] = '������';
                
                $history = new Application_Model_DbTable_History();
                $history->addHistory($device_data);
        
                
                $this->_helper->redirector->gotoUrl("repairs/index/number/$number");
                
            //���� ���� ������
            } else {
              $this->view->error_message = 'error_message';
              $this->view->claim = $claim;
              $this->view->diagnos = $diagnos;
              $this->view->spares = $spares;
              $this->view->work = $work;
              $this->view->comments = $comments;
              $this->view->counter = $counter;
              $this->view->status = $status_values;
              $this->view->status_ch = $status;
              $this->view->error = $error;
              $this->view->check_data = $check_data;
              $this->view->checked = $checked;
            }
        }
    }

    public function editAction() {

        $number = $this->getRequest()->getParam('number');
        $this->view->number = $number; //��������� ����� ��������

        $id = $this->getRequest()->getParam('id');
       
        $devices = new Application_Model_DbTable_Devices();
        $device = $devices->getName($number);
        $this->view->name=$device['name']; //�������� ��������
        
        $status  = new Application_Model_DbTable_Setup();
        $status->setTableName('status');
        $status_values = $status->getValues();
        $this->view->status=$status_values; //��������� ������� ��������
        $this->view->status_ch = $device['status']; //������� ������ ��������
 
        
        $repaire = new Application_Model_DbTable_Repairs();
        $repaire_data = $repaire->getRepaire($id); //��������� ������  � �������
        
        //�������� ������ ���������� � �������
        $repaire_data['spares'] = substr($repaire_data['spares'],0,strpos($repaire_data['spares'],'||'));

        
        //��������� ������� ��������� ������ ���������
        $warehouse = new Application_Model_DbTable_Warehouse();
        $warehouse_data = $warehouse->fetchAll();
        
        //��������� � �������� ��������� �������� ������� �������������
        if ($repaire_data['serialize_data']!='N;') { //��������� �� �������� �� ������ ������
            foreach (unserialize($repaire_data['serialize_data']) as $repaire_id => $repaire_value) {
                foreach ($warehouse_data as $rows) {
                    if ($rows['id'] == $repaire_id) {
                        $rows['remain']+=$repaire_value;
                    }
                }
            }
        }

        $this->view->warehouse = $warehouse_data; 

        // ���� � ��� ��� Post ������
        if ($this->getRequest()->isPost()) {

            // ��������� ���
            $formData = $this->getRequest()->getPost(); //��������� ������

            //������� ������ ������ �� ������� ��� ������ �������
            /*
            $data - check_"id" - �������� ������� �������
            $value - "id" - id ������� �������
            */
            foreach ($formData as $data => $value ){
                if (preg_match("/check/", $data)) { //���� ���������� check
                    $check_data[$value] = $formData["$value"]; //������� ������ ������
                    $checked["$value"] = "checked"; //������ � ������ ������� �������
                   
                    if (!preg_match("/^[0-9]{0,10}$/i",$formData["$value"])) { //�������� ���������� ���������
                     
                       $error["$value"]='error'; //���������� ������������ ��������
                    }
                    
                    //�������� �� ���������� ��������� � ����
                    foreach ($warehouse_data as $rows) {
                        if ($rows['id'] == $value) {
                            $spare_count = $rows['remain'];
                        }
                    }
                    if(($spare_count-$formData["$value"])<0){   //������ ��������
                        $error["$value"]='error'; //���������� ������������ ��������
                    } 
                }
            } 
            
            //��������� ������
            $claim = $this->getRequest()->getPost('claim');
            $diagnos = $this->getRequest()->getPost('diagnos');
            $spares = $this->getRequest()->getPost('spares');
            $work = $this->getRequest()->getPost('work');
            $comments = $this->getRequest()->getPost('comments');
            $counter = $this->getRequest()->getPost('counter');
            $status = $this->getRequest()->getPost('status');
            
            //�������� ���������� ���������
            if (!preg_match("/^[�-��-�A-Za-z0-9 \.\,\-\ \!\;\:]{3,300}$/i",$claim))   {$error['claim']='error';}
            if (!preg_match("/^[�-��-�A-Za-z0-9 \.\,\-\ \!\;\:]{3,300}$/i",$diagnos)) {$error['diagnos']='error';}
            if (!preg_match("/^[�-��-�A-Za-z0-9 \.\,\-\ \!\;\:]{3,300}$/i",$spares))  {$error['spares']='error';}
            if (!preg_match("/^[�-��-�A-Za-z0-9 \.\,\-\ \!\;\:]{3,300}$/i",$work))    {$error['work']='error';}
            if (!preg_match("/^[�-��-�A-Za-z0-9 \.\,\-\ \!\;\:]{3,300}$/i",$comments)){$error['comments']='error';}
            if (!preg_match("/^[�-��-�A-Za-z0-9 \.\,\-\ \!\;\:]{0,300}$/i",$counter)) {$error['counter']='error';}
            
            //���� ��� �������
            if (!$error){

                if($spares!=''){
                    $spares.=' ';
                }
                if($check_data) {
                    
                    $warehouse = new Application_Model_DbTable_Warehouse();
                    $warehistory = new Application_Model_DbTable_Warehistory();
                    
                    foreach ($check_data as $idw => $value){

                        $spare_data = $warehouse->getWarehouse($idw);
                        $spares.= "|| {$spare_data['serial']}-{$spare_data['name']}-{$value}�� ";
                                                            
                        $row = $warehouse->getWarehouse($idw);
                        
                        foreach ($warehouse_data as $rows) {
                            if ($rows['id'] == $idw) {
                                $remain = $rows['remain'];
                            }
                        }
                        $remain -= $value;
                        $serial = $row['serial'];
                        $name = $row['name'];
                        $type = $row['type'];
                        $price = 'unload';
                        $path = $row['path']; //��� �������� ��� ��������

                        // �������� ����� ������ addMovie ��� ������� ����� ������
                        $warehouse->editWarehouse($idw, $serial, $name, $type, $remain, $price, $path);
                        
                        $warehistory->addWarehistory($serial, $name, "�������������� ������� -{$number}-{$device['name']}",$row['remain'],$value, $remain);
                    }
                }
                
                $serialize_data = serialize($check_data);
                $serialize_checked = serialize($checked);
               
                $repaire = new Application_Model_DbTable_Repairs();
                
                $repaire->editRepaire($id, $number, $claim, $diagnos, $spares, $work, $comments, $counter, $serialize_data, $serialize_checked);
                
                $devices->editDeviceStatus($number, $status);
                
                
                $device_data['number'] = $number;
                $device_data['name'] = '������';
                $device_data['type'] = '������';
                $device_data['owner'] = '������';
                $device_data['user'] = '������';
                $device_data['status'] = $status;
                $device_data['city'] = '������';
                $device_data['adress'] = '������';
                $device_data['tt_name'] = '������';
                $device_data['tt_user'] = '������';
                $device_data['tt_phone'] = '������';
                
                $history = new Application_Model_DbTable_History();
                $history->addHistory($device_data);
                
                
                $this->_helper->redirector->gotoUrl("repairs/index/number/$number");
                
            //���� ���� ������
            } else {
              $this->view->error_message = 'error_message';
              $this->view->claim = $claim;
              $this->view->diagnos = $diagnos;
              $this->view->spares = $spares;
              $this->view->work = $work;
              $this->view->comments = $comments;
              $this->view->counter = $counter;
              $this->view->error = $error;
              $this->view->check_data = $check_data;
              $this->view->checked = $checked;
            }
            
        //���� ������� ���� ����, ��������� ���� �������������� �� ����
        } else {

            $this->view->claim = $repaire_data['claim'];
            $this->view->diagnos = $repaire_data['diagnos'];
            $this->view->spares = $repaire_data['spares'];
            $this->view->work = $repaire_data['work'];
            $this->view->comments = $repaire_data['comments'];
            $this->view->counter = $repaire_data['counter'];
            $this->view->check_data = unserialize($repaire_data['serialize_data']);
            $this->view->checked = unserialize($repaire_data['serialize_checked']);
        }
    }

    public function deleteAction() {
        // action body
        $form = new Application_Form_DeleteRepaire();
        $form->submit->setLabel('�������');
        $form->cancel->setLabel('������');
        $this->view->form = $form;
        $id = $this->getParam('id');
        $number = $this->getParam('number');
        
        //���� ���� ������ POST
        if ($this->getRequest()->isPost()) {
            //���� ������������ ��������
            if ($this->getRequest()->getPost('submit')) {
                $device = new Application_Model_DbTable_Repairs();
                $device->deleteRepaire($id);
 
                $this->_helper->redirector->gotoUrl("/repairs/index/number/$number");
            } else {
                //���� �������� ��������
                if ($this->getRequest()->getPost('cancel')) {
                   $this->_helper->redirector->gotoUrl("/repairs/index/number/$number");
                }
            }
        } else {
            //������� �������������� ������

            $repaire = new Application_Model_DbTable_Repairs();

            $this->view->repaire = $repaire->getRepaire($id);
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
        $sheet->setTitle('Repairs All');
        $sheet->setCellValue("A1", '�');
        $sheet->setCellValue("B1", '����');
        $sheet->setCellValue("C1", '�����');
        $sheet->setCellValue("D1", '������');
        $sheet->setCellValue("E1", '�������');
        $sheet->setCellValue("F1", '������');
        $sheet->setCellValue("G1", '��������');
        $sheet->setCellValue("H1", '����������');

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

        $repairs = new Application_Model_DbTable_Repairs();
        $data = $repairs->fetchAll()->toArray();

        $i = 2;

        foreach ($data as $rows) {
            $number = $i - 1;
            $sheet->setCellValue("A$i", "$number");
            $sheet->setCellValue("B$i", "$rows[date]");
            $sheet->setCellValue("C$i", "$rows[number]");
            $sheet->setCellValue("D$i", "$rows[claim]");
            $sheet->setCellValue("E$i", "$rows[diagnos]");
            $sheet->setCellValue("F$i", "$rows[work]");
            $sheet->setCellValue("G$i", "$rows[spares]");
            $sheet->setCellValue("H$i", "$rows[comments]");
            $i++;
        }

// ������� ���������� �����
        $objWriter = new PHPExcel_Writer_Excel5($xls);
        $objWriter->save('repairs.xls');
        // ��������� ���� � �������� ������
        header("Location: http://{$settings['excel']['site']}/repairs.xls");
    }
    public function toexcelmonthAction() {
        
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
        $sheet->setCellValue("A1", '�');
        $sheet->setCellValue("B1", '����');
        $sheet->setCellValue("C1", '�����');
        $sheet->setCellValue("D1", '������');
        $sheet->setCellValue("E1", '�������');
        $sheet->setCellValue("F1", '������');
        $sheet->setCellValue("G1", '��������');
        $sheet->setCellValue("H1", '����������');

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
        
         if (isset($_SESSION['year']) && isset($_SESSION['month'])) {

            $year =  $_SESSION['year'];
            $month = $_SESSION['month'];

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

        $repairs = new Application_Model_DbTable_Repairs();
        $data = $repairs->statisticRepairs($date);
        
        $i = 2;
        
        $sheet->setTitle("Repairs $date");

        foreach ($data as $rows) {
            $number = $i - 1;
            $sheet->setCellValue("A$i", "$number");
            $sheet->setCellValue("B$i", "$rows[date]");
            $sheet->setCellValue("C$i", "$rows[number]");
            $sheet->setCellValue("D$i", "$rows[claim]");
            $sheet->setCellValue("E$i", "$rows[diagnos]");
            $sheet->setCellValue("F$i", "$rows[work]");
            $sheet->setCellValue("G$i", "$rows[spares]");
            $sheet->setCellValue("H$i", "$rows[comments]");
            $i++;
        }

// ������� ���������� �����
        $objWriter = new PHPExcel_Writer_Excel5($xls);
        $objWriter->save('repairs_mounth.xls');
        // ��������� ���� � �������� ������
        header("Location: http://{$settings['excel']['site']}/repairs_mounth.xls");
    }

}
