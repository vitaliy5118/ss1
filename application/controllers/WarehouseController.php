<?php

class WarehouseController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
        
                // ������� ������
        $warehouse = new Application_Model_DbTable_Warehouse();
        
        //���� ���� ������ ����������
        if ($this->getRequest()->getParam('sort')) {
            $sort = $this->getRequest()->getParam('sort');
        } else {
            $sort = $this->_helper->navigation->sortby();
        }
        
        //���� ���� ������ ���������� ������� �� ��������
        if ($this->getRequest()->isPost('select_limit')) {
            $select_limit = $this->getRequest()->getPost('select_limit');
            $page=1;
        } else {
            $select_limit = $this->_helper->navigation->selectlimit();
            
            //���� ���� ������ ��������
            if ($this->getRequest()->getParam('page')) {
                $page = $this->getRequest()->getParam('page');
            } else {
                $page = $this->_helper->navigation->page();
            }
        } 
        
        //��������� ���������� ������
        if ($this->getRequest()->isPost('search_catalog')) {
            $search = $this->getRequest()->getPost('search_catalog');
        } elseif ($this->getRequest()->getParam('search_catalog')) {
            $search = $this->getRequest()->getParam('search_catalog');
        }

        
        if ($search != '') {
            $data_array = $warehouse->fetchAll($warehouse->searchWarehouse($search));
        } else {
            $data_array = $warehouse->fetchAll(); 
        }

        //����� ���������
        $this->_helper->navigation->initNav($sort, $select_limit, $select_type, $page, $data_array);
        $this->_helper->navigation->setView($this->view);

        
        $count = $select_limit;
        $offset = $page * $select_limit - $select_limit;

        //��������� ������
        //������ ������
        if ($search != '') {
            $select = $warehouse->searchWarehouse($search)->order($sort)->limit($count, $offset);
            $this->view->search_param = "/search_catalog/$search";
        //������ ����������� ���� 
        } else {
            $select = $warehouse->select()->order($sort)->limit($count, $offset);
        }
        
   
        $this->view->warehouse = $warehouse->fetchAll($select);
        $this->view->search = $search;
   }

   public function addAction() {
        // ������ �����
        $form = new Application_Form_Warehouse();

        // ��������� ����� ��� submit
        $form->submit->setLabel('��������');

        // ������� ����� � view
        $this->view->form = $form;

        // ���� � ��� ��� Post ������
        if ($this->getRequest()->isPost()) {
            // ��������� ���
            $formData = $this->getRequest()->getPost();

            // ���� ����� ��������� �����
            if ($form->isValid($formData)) {
                // ��������� ������
                
                $image = $form->getValue('image'); // ��������� ��������
                if ($image) {
                    $imagename = $form->image->getFileName();
                
                    $needle   = '/public/img';
                    $pos      = strripos($imagename, $needle);
                
                    $filename = substr($imagename, $pos+12);
                } else {
                  $filename = 'nophoto.png'; 
                }
                
                $serial = $form->getValue('serial');
                $name = $form->getValue('name');
                $type = $form->getValue('type');
                $remain = $form->getValue('remain');
                $price = $form->getValue('price');
                
                // ������ ������ ������
                $warehouse = new Application_Model_DbTable_Warehouse();
                $warehouse->addWarehouse($serial, $name, $type, $remain, $price, $filename); 
                
                $warehistory = new Application_Model_DbTable_Warehistory();
                $warehistory->addWarehistory($serial, $name, '����� ������', '0', $remain, $remain); 

                // ���������� ������������ helper ��� ��������� �� action = index
                $this->_helper->redirector('index');
            } else {
                // ���� ����� ��������� �������,
                // ���������� ����� populate ��� ���������� ���� �����
                // ��� �����������, ������� ��� ������������
                $form->populate($formData);
            }
        }
    }

    public function loadAction() {
        
        $warehouse = new Application_Model_DbTable_Warehouse();
        $this->view->warehouse = $warehouse->fetchAll(); //��������� ������ ������
        
        // ���� � ��� ��� Post ������
        if ($this->getRequest()->isPost()) {

            // ��������� ���
            $formData = $this->getRequest()->getPost(); //��������� ������
            
            //��������� ��� ������ �� ���������� ��������
            foreach ($formData as $name => $value ) {
                
                //�������� ���������� ���������
                if (!preg_match("/^[0-9\.]{0,10}$/i", $value)) { 
                    $error["$name"]='error'; //���������� ������������ ��������
                }
                 //���� ���������� check
                if (preg_match("/check/", $name)) {
                    $checked["$value"] = "checked"; //������ � ������ ������� �������
                }
                //���������� ������ �����
                if ($value) {
                    $check_data["$name"] = $value; 
                }
                
            }
            //���� ��� �������
            if (!$error){
                
               // print_r($check_data); die;
                if($check_data) {
                    
                    $warehouse = new Application_Model_DbTable_Warehouse();
                    $warehistory = new Application_Model_DbTable_Warehistory();
                    
                    foreach ($check_data as $name => $value){
                        
                        if (preg_match("/check/", $name)) {
                        
                        $id=$value;
                        $row = $warehouse->getWarehouse($id);
                        
                        $remain = $row['remain'] + $check_data["remain_$id"];
                        $price = $check_data["price_$id"];
                        $serial = $row['serial'];
                        $name = $row['name'];
                                                                   //     print_r($check_data["remain_$id"]); die;
                                                //�������� ����� ������ addMovie ��� ������� ����� ������
                        $warehouse->loadWarehouse($id, $remain, $price);
                        
                        $warehistory->addWarehistory($serial, $name, "�������� ���������",$row['remain'],$check_data["remain_$id"], $remain);
                    
                        }
                        } 
                }
                
                $this->_helper->redirector->gotoUrl("warehouse/index/");
               
            //���� ���� ������
            } else {
              $this->view->error = $error;
              $this->view->check_data = $check_data;
              $this->view->checked = $checked;
            }
        }
    }

    public function unloadAction() {
        
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
            $recipient = $this->getRequest()->getPost('recipient');
            $comments = $this->getRequest()->getPost('comments');

            //�������� ���������� ���������
            if (!preg_match("/^[�-��-� \.\,]{3,30}$/i",$recipient)){$error['recipient']='error';}
            if (!preg_match("/^[�-��-� \.\,]{0,30}$/i",$comments)){$error['comments']='error';}
            
            //���� ��� �������
            if (!$error){

                if($check_data) {
                    
                    $warehouse = new Application_Model_DbTable_Warehouse();
                    $warehistory = new Application_Model_DbTable_Warehistory();
                    
                    foreach ($check_data as $id => $value){
                     
                        $row = $warehouse->getWarehouse($id);
                        
                        $remain = $row['remain'] - $value;
                        $serial = $row['serial'];
                        $name = $row['name'];
                        $type = $row['type'];
                        $path = $row['path'];
                        
                        $description= "������ || $recipient" ;
                        if ($comments) {
                            $description.=" || $comments";
                        }
                        // �������� ����� ������ addMovie ��� ������� ����� ������
                        $warehouse->editWarehouse($id, $serial, $name, $type, $remain, 'unload', $path);
                        $warehistory->addWarehistory($serial, $name, $description,$row['remain'],$value, $remain);
                    }
                }
                
                $this->_helper->redirector->gotoUrl("warehouse/index/");
                
                
            //���� ���� ������
            } else {
              $this->view->error_message = 'error_message';
              $this->view->comments = $comments;
              $this->view->recipient = $recipient;
              $this->view->error = $error;
              $this->view->check_data = $check_data;
              $this->view->checked = $checked;
            }
        }
    }

    public function editAction() {
        // ������ �����
        $form = new Application_Form_Warehouse();

        // ��������� ����� ��� submit
        $form->submit->setLabel('�������������');

        // ������� ����� � view
        $this->view->form = $form;
        
        $warehouse = new Application_Model_DbTable_Warehouse();
        
        $id = $this->_getParam('id', 0);
        $data = $warehouse->getWarehouse($id);

        // ���� � ��� ��� Post ������
        if ($this->getRequest()->isPost()) {
            // ��������� ���
            $formData = $this->getRequest()->getPost();

            // ���� ����� ��������� �����
            if ($form->isValid($formData)) {
                // ��������� ������
                $id = (int) $form->getValue('id');
                $serial = $form->getValue('serial');
                $name = $form->getValue('name');
                $type = $form->getValue('type');
                $remain = $form->getValue('remain');
                $price = $form->getValue('price');
                
                $image = $form->getValue('image'); // ��������� ��������
                
                //
                if ($image) {
                    $imagename = $form->image->getFileName();
                
                    $needle   = '/public/img';
                    $pos      = strripos($imagename, $needle);
                
                    $filename = substr($imagename, $pos+12);
                } else {
                    
                  $path = $form->getValue('path');

                  if($path){
                       $filename = $path;
                  } else {
                       $filename = 'nophoto.png';
                  } 
                }
                // �������� ����� ������ addMovie ��� ������� ����� ������
                $warehouse->editWarehouse($id, $serial, $name, $type, $remain, $price, $filename);
                
                $warehistory = new Application_Model_DbTable_Warehistory();
                $warehistory->addWarehistory($serial, $name, "��������������", $data['remain'], $remain-$data['remain'], $remain); 

                // ���������� ������������ helper ��� ��������� �� action = index
                $this->_helper->redirector('index');
            } else {
                // ���� ����� ��������� �������,
                // ���������� ����� populate ��� ���������� ���� �����
                // ��� �����������, ������� ��� ������������
                $form->populate($formData);
            }
        } else {

            if ($id > 0) {
                // ������ ������ ������
                $form->populate($data);
                $this->view->path = $data['path'];
            }
        }
    }

    public function deleteAction() {
        // ���� � ��� ��� Post ������
        if ($this->getRequest()->isPost()) {
            // ��������� ��������
            $del = $this->getRequest()->getPost('del');

            // ���� ������������ ���������� ��� ������� ������� ������
            if ($del == '��') {
                // ��������� id ������, ������� ����� �������
                $id = $this->getRequest()->getPost('id');
                // ������ ������ ������
                $warehouse = new Application_Model_DbTable_Warehouse();

                // �������� ����� ������ deleteMovie ��� �������� ������
                $warehouse->deleteWarehouse($id);
            }

            // ���������� ������������ helper ��� ��������� �� action = index
            $this->_helper->redirector('index');
        } else {
            // ���� ������ �� Post, ������� ��������� ��� �������������
            // �������� id ������, ������� ����� �������
            $id = $this->_getParam('id');

            // ������ ������ ������
            $warehouse = new Application_Model_DbTable_Warehouse();

            // ������ ������ � ������� � view
            $this->view->warehouse = $warehouse->getWarehouse($id);
        }
    }
    
    public function historyAction() {
    
        $warehistory = new Application_Model_DbTable_Warehistory();

        //���� ���� ������ ���������� ������� �� ��������
        if ($this->getRequest()->isPost('select_limit')) {
            $select_limit = $this->getRequest()->getPost('select_limit');
            $page=1;
        } else {
            $select_limit = $this->_helper->navigation->selectlimit();
            
            //���� ���� ������ ��������
            if ($this->getRequest()->getParam('page')) {
                $page = $this->getRequest()->getParam('page');
            } else {
                $page = $this->_helper->navigation->page();
            }
        } 
        
        $data_array = $warehistory->fetchAll(); 
        
        //����� ���������
        $this->_helper->navigation->initNav($sort, $select_limit, $select_type, $page, $data_array);
        $this->_helper->navigation->setView($this->view);

        
        $count = $select_limit;
        $offset = $page * $select_limit - $select_limit;
 
        $select = $warehistory->select()->order('data DESC')->limit($count, $offset);
        
        $this->view->warehistory = $warehistory->fetchAll($select);
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
        $sheet->setTitle('Warehouse CoffeeService');
        $sheet->setCellValue("A1", '�');
        $sheet->setCellValue("B1", '�������� �����');
        $sheet->setCellValue("C1", '��������');
        $sheet->setCellValue("D1", '���');
        $sheet->setCellValue("E1", '�������');
        $sheet->setCellValue("F1", '����');

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
        
        $sheet->getStyle('A')->getAlignment()->setHorizontal(
                PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        
// �������������� ������ ������
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
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
 
        $warehouse = new Application_Model_DbTable_Warehouse();
        $data = $warehouse->fetchAll()->toArray();

        $i = 2;
        
        foreach ($data as $rows) {
            $number=$i-1;
            $sheet->setCellValue("A$i", "$number");
            $sheet->setCellValue("B$i", "$rows[serial]");
            $sheet->setCellValue("C$i", "$rows[name]");
            $sheet->setCellValue("D$i", "$rows[type]");
            $sheet->setCellValue("E$i", "$rows[remain]");
            $sheet->setCellValue("F$i", "$rows[lastadding]");
            $i++;
        }

// ������� ���������� �����
        $objWriter = new PHPExcel_Writer_Excel5($xls);
        $objWriter->save('warehouse.xls');
        // ��������� ���� � �������� ������
        header("Location: http://{$settings['excel']['site']}/warehouse.xls");
    }
  

}
