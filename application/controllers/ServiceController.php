<?php

class ServiceController extends Zend_Controller_Action
{
    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
        $service = new Application_Model_DbTable_Service();
        
        //если идет запрос сортировки
        if ($this->getRequest()->getParam('sort')) {
            $sort = $this->getRequest()->getParam('sort');
        } else {
            $sort = $this->_helper->navigation->sortby();
        }
        
        //если идет запрос количества записей на странице
        if ($this->getRequest()->isPost('select_limit')) {
            $select_limit = $this->getRequest()->getPost('select_limit');
            $page=1;
        } else {
            $select_limit = $this->_helper->navigation->selectlimit();
            
            //если идет запрос страницы
            if ($this->getRequest()->getParam('page')) {
                $page = $this->getRequest()->getParam('page');
            } else {
                $page = $this->_helper->navigation->page();
            }
        } 
        
        //обработка параметров поиска
        if ($this->getRequest()->isPost('search_catalog')) {
            $search = $this->getRequest()->getPost('search_catalog');
        } elseif ($this->getRequest()->getParam('search_catalog')) {
            $search = $this->getRequest()->getParam('search_catalog');
        }

        
        if ($search != '') {
            $data_array = $service->fetchAll($service->searchSales($search));//!!!!!!!!!!!!!!1
        } else {
            $data_array = $service->fetchAll(); 
        }

        //вывод навигации
        $this->_helper->navigation->initNav($sort, $select_limit, $select_type, $page, $data_array);
        $this->_helper->navigation->setView($this->view);

        
        $count = $select_limit;
        $offset = $page * $select_limit - $select_limit;

        //формируем запрос
        //запрос поиска
        if ($search != '') {
            $select = $service->searchSales($search)->order($sort)->limit($count, $offset);
            $this->view->search_param = "/search_catalog/$search";
        //запрос конкретного типа 
        } else {
            $select = $service->select()->order($sort)->limit($count, $offset);
        }
        
   
        $this->view->service = $service->fetchAll($select);
        $this->view->search = $search;
   }
        
    public function addAction()
    {
        
        $form = new Application_Form_Sales();
        $form->submit->setLabel('Добавить');
        $this->view->form = $form;
        $warehouse = new Application_Model_DbTable_Warehouse();
        $this->view->warehouse = $warehouse->fetchAll();
        $prices = new Application_Model_DbTable_Prices();
        $this->view->prices = $prices->fetchAll();
       
        // Если к нам идёт Post запрос
        if ($this->getRequest()->isPost()) {
            //принимаем данные
            $formData = $this->getRequest()->getPost();

            //Проверка валидациии    
            if ($form->isValid($formData)) {
                //Успешная валидация
                //Извлекаем данные
                $number = $form->getValue('number');
                $name = $form->getValue('name');
                $buyer = $form->getValue('buyer');
                $status = $form->getValue('status');

                //Добавляем данные в базу
                $sales = new Application_Model_DbTable_Sales();
                $sales->addSales($number, $name, $buyer, $status);

                //Переходим на предыдущую страницу
                $this->_helper->redirector('index');
            } else {
                //Неуспешная валидация
                //Возвращаем данные в таблицу
                $form->populate($formData);
            }
        }
    }

    public function editAction()
    {
        // action body
        $form = new Application_Form_Sales();
        $form->submit->setLabel('Редактировать');
        $_SESSION['edit'] = true;
        $this->view->form = $form;

        if ($this->getRequest()->isPost()) {

            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {

                $id = (int) $form->getValue('id');
                $number = $form->getValue('number');
                $name = $form->getValue('name');
                $buyer = $form->getValue('buyer');
                $status = $form->getValue('status');

                $sales = new Application_Model_DbTable_Sales();
                $sales->editSales($id, $number, $name, $buyer, $status);
                $this->_helper->redirector('index');
            } else {

                $form->populate($formData);
            }
        } else {

            $id = $this->getParam('id');
            $sales = new Application_Model_DbTable_Sales();
            $form->populate($sales->getSales($id));
        }
    }

    public function deleteAction()
    {
        // action body
        $form = new Application_Form_DeleteSales();
        $form->submit->setLabel('Удалить');
        $form->cancel->setLabel('Отмена');
        $this->view->form = $form;
        $id = $this->getParam('id');
        //если идет запрос POST
        if ($this->getRequest()->isPost()){
            //если подтверждаем удаление
            if  ($this->getRequest()->getPost('submit')){
                //Application_Model_DbTable_Devices::deleteDevice($id);
                $sales = new Application_Model_DbTable_Sales();
                $sales->deleteSales($id);
                $this->_helper->redirector('index');
            } else {
            //если отменяем удаление
                if ($this->getRequest()->getPost('cancel')){
                $this->_helper->redirector('index');
            }
            }
        } else {
        //выводим дополнительные данные

        $sales = new Application_Model_DbTable_Sales();

        $this->view->sales = $sales->getSales($id);
        }
    }
    
    public function toexcelAction() {

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
        $sheet->setTitle('Sales CoffeeService');
        $sheet->setCellValue("A1", '№');
        $sheet->setCellValue("B1", 'Номер');
        $sheet->setCellValue("C1", 'Название');
        $sheet->setCellValue("D1", 'Покупатель');
        $sheet->setCellValue("E1", 'Статус');
        $sheet->setCellValue("F1", 'Дата');

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

        $sheet->getStyle('A')->getAlignment()->setHorizontal(
                PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

// автоматическая ширина ячейки
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
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

        $sales = new Application_Model_DbTable_Sales();
        $data = $sales->fetchAll()->toArray();

        $i = 2;

        foreach ($data as $rows) {
            $number = $i - 1;
            $sheet->setCellValue("A$i", "$number");
            $sheet->setCellValue("B$i", "$rows[number]");
            $sheet->setCellValue("C$i", "$rows[name]");
            $sheet->setCellValue("D$i", "$rows[buyer]");
            $sheet->setCellValue("E$i", "$rows[status]");
            $sheet->setCellValue("F$i", "$rows[date]");
            $i++;
        }

// Выводим содержимое файла
        $objWriter = new PHPExcel_Writer_Excel5($xls);
        $objWriter->save('sales.xls');
        // открываем файл в бинарном режиме
        header('Location: http://ss1.su/sales.xls');
    }

}





