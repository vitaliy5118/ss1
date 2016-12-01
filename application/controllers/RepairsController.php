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
        $select = $repairs->select()->where("number = '$number'")->order('date DESC');

        $this->view->repairs = $repairs->fetchAll($select)->toArray();
        $this->view->number = $number;
        
        $devices = new Application_Model_DbTable_Devices();
        $device = $devices->getName($number);
        $this->view->name=$device['name']; //название аппарата
        
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
        // Создаём форму
        $number = $this->getRequest()->getParam('number');
        $this->view->number = $number; //принимаем номер аппарата
        
        $devices = new Application_Model_DbTable_Devices();
        $device = $devices->getName($number);
        $this->view->name=$device['name']; //название аппарата
        
        $status  = new Application_Model_DbTable_Setup();
        $status->setTableName('status');
        $status_values = $status->getValues();
        $this->view->status = $status_values; //доступные статус аппарата
        $this->view->status_ch = $device['status']; //текущий статус аппарата
 
        $warehouse = new Application_Model_DbTable_Warehouse();
        $this->view->warehouse = $warehouse->fetchAll(); //загружаем данные склада
        
        // Если к нам идёт Post запрос
        if ($this->getRequest()->isPost()) {

            $formData = $this->getRequest()->getPost(); //принимаем данные
            $repaire = new Application_Model_Repaire($formData);
            
            //валидация данных
            $error = $repaire->checkForm();

           //если нет ошибкок
            if (!$error){
                //сохраняем данные
                $repaire->saveRepair($number, $device);
                $this->_helper->redirector->gotoUrl("repairs/index/number/$number");
                
            //если есть ошибки
            } else {
               
              $this->view->error_message = 'error_message';
              $this->view->claim = $repaire->claim;
              $this->view->diagnos = $repaire->diagnos;
              $this->view->spares = $repaire->spares;
              $this->view->work = $repaire->work;
              $this->view->comments = $repaire->comments;
              $this->view->counter = $repaire->counter;
              $this->view->check_data = $repaire->check_data;
              $this->view->checked = $repaire->checked;
              $this->view->status = $status_values;
              $this->view->status_ch = $repaire->status;
              $this->view->error = $error;
            }
        }
    }

    public function editAction() {

        $number = $this->getRequest()->getParam('number');
        $this->view->number = $number; //принимаем номер аппарата

        $id = $this->getRequest()->getParam('id');
       
        $devices = new Application_Model_DbTable_Devices();
        $device = $devices->getName($number);
        $this->view->name=$device['name']; //название аппарата
        
        $status  = new Application_Model_DbTable_Setup();
        $status->setTableName('status');
        $status_values = $status->getValues();
        $this->view->status=$status_values; //доступные статусы аппарата
        $this->view->status_ch = $device['status']; //текущий статус аппарата
 
        
        $repaire = new Application_Model_DbTable_Repairs();
        $repaire_data = $repaire->getRepaire($id); //загружаем данные  о ремонте
        
        //вырезаем лишнюю информацию о ремонте
        $repaire_data['spares'] = substr($repaire_data['spares'],0,strpos($repaire_data['spares'],'||'));

        
        //загружаем текущее состояние склада запчастей
        $warehouse = new Application_Model_DbTable_Warehouse();
        $warehouse_data = $warehouse->fetchAll();
        
        //добавляем к текущему состоянию значения которые редактируются
        if ($repaire_data['serialize_data']!='N;') { //проверяем не является ли запись пустой
            foreach (unserialize($repaire_data['serialize_data']) as $repaire_id => $repaire_value) {
                foreach ($warehouse_data as $rows) {
                    if ($rows['id'] == $repaire_id) {
                        $rows['remain']+=$repaire_value;
                    }
                }
            }
        }

        $this->view->warehouse = $warehouse_data; 

        // Если к нам идёт Post запрос
        if ($this->getRequest()->isPost()) {

            // Принимаем его
            $formData = $this->getRequest()->getPost(); //принимаем данные
            
            $repaire = new Application_Model_Repaire($formData);
            //валидация данных
            $error = $repaire->checkForm();
            
            //если нет ошибкок
            if (!$error){
                $repaire->editRepair($id, $number, $device);
                $this->_helper->redirector->gotoUrl("repairs/index/number/$number");
                
            //если есть ошибки
            } else {
                
              $this->view->error_message = 'error_message';
              $this->view->claim = $repaire->claim;
              $this->view->diagnos = $repaire->diagnos;
              $this->view->spares = $repaire->spares;
              $this->view->work = $repaire->work;
              $this->view->comments = $repaire->comments;
              $this->view->counter = $repaire->counter;
              $this->view->check_data = $repaire->check_data;
              $this->view->checked = $repaire->checked;
              $this->view->status = $status_values;
              $this->view->status_ch = $repaire->status;
              $this->view->error = $error;
            }
            
        //если запроса ПОСТ нету, заполняем поля редактирования из базы
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
        $form->submit->setLabel('Удалить');
        $form->cancel->setLabel('Отмена');
        $this->view->form = $form;
        $id = $this->getParam('id');
        $number = $this->getParam('number');
        
        //если идет запрос POST
        if ($this->getRequest()->isPost()) {
            //если подтверждаем удаление
            if ($this->getRequest()->getPost('submit')) {
                $device = new Application_Model_DbTable_Repairs();
                $device->deleteRepaire($id);
 
                $this->_helper->redirector->gotoUrl("/repairs/index/number/$number");
            } else {
                //если отменяем удаление
                if ($this->getRequest()->getPost('cancel')) {
                   $this->_helper->redirector->gotoUrl("/repairs/index/number/$number");
                }
            }
        } else {
            //выводим дополнительные данные

            $repaire = new Application_Model_DbTable_Repairs();

            $this->view->repaire = $repaire->getRepaire($id);
        }
    }

    public function toexcelAction() {
        
        global $settings;
// Подключаем класс для работы с excel
        require_once('PHPExcel.php');
// Подключаем класс для вывода данных в формате excel
        require_once('PHPExcel/Writer/Excel5.php');

// Создаем объект класса PHPExcel
        $xls = new PHPExcel();
// Устанавливаем индекс активного листа
        $xls->setActiveSheetIndex(0);
// Получаем активный лист
        $sheet = $xls->getActiveSheet();
// Подписываем лист
        $sheet->setTitle('Repairs All');
        $sheet->setCellValue("A1", '№');
        $sheet->setCellValue("B1", 'Дата');
        $sheet->setCellValue("C1", 'Номер');
        $sheet->setCellValue("D1", 'Жалоба');
        $sheet->setCellValue("E1", 'Диагноз');
        $sheet->setCellValue("F1", 'Работа');
        $sheet->setCellValue("G1", 'Запчасти');
        $sheet->setCellValue("H1", 'Коментарии');

//меняем цвет заголовка      
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

// автоматическая ширина ячейки
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
// формат ячейки текстовый
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

// Выводим содержимое файла
        $objWriter = new PHPExcel_Writer_Excel5($xls);
        $objWriter->save('repairs.xls');
        // открываем файл в бинарном режиме
        header("Location: http://{$settings['excel']['site']}/repairs.xls");
    }
    public function toexcelmonthAction() {
        
        global $settings;
// Подключаем класс для работы с excel
        require_once('PHPExcel.php');
// Подключаем класс для вывода данных в формате excel
        require_once('PHPExcel/Writer/Excel5.php');

// Создаем объект класса PHPExcel
        $xls = new PHPExcel();
// Устанавливаем индекс активного листа
        $xls->setActiveSheetIndex(0);
// Получаем активный лист
        $sheet = $xls->getActiveSheet();
// Подписываем лист
        $sheet->setCellValue("A1", '№');
        $sheet->setCellValue("B1", 'Дата');
        $sheet->setCellValue("C1", 'Номер');
        $sheet->setCellValue("D1", 'Жалоба');
        $sheet->setCellValue("E1", 'Диагноз');
        $sheet->setCellValue("F1", 'Работа');
        $sheet->setCellValue("G1", 'Запчасти');
        $sheet->setCellValue("H1", 'Коментарии');

//меняем цвет заголовка      
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

// автоматическая ширина ячейки
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
// формат ячейки текстовый
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

// Выводим содержимое файла
        $objWriter = new PHPExcel_Writer_Excel5($xls);
        $objWriter->save('repairs_mounth.xls');
        // открываем файл в бинарном режиме
        header("Location: http://{$settings['excel']['site']}/repairs_mounth.xls");
    }

}
