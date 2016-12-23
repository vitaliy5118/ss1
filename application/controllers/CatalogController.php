<?php

//namespace Catalog;

class CatalogController extends Zend_Controller_Action {
    
    public $device = null;

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
        // создаем модель
        $devices = new Application_Model_DbTable_Devices();

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
        
        
        //если идет запрос типа
        if ($this->getRequest()->isPost('select_type')) {
            $select_type = $this->getRequest()->getPost('select_type');
        } elseif ($this->getRequest()->getParam('select_type')) {
            $select_type = $this->getRequest()->getParam('select_type');
        } else {
            $select_type = $this->_helper->navigation->selecttype();
        }


        //обработка параметров поиска
        if ($this->getRequest()->isPost('search_catalog')) {
            $search = $this->getRequest()->getPost('search_catalog');
        } elseif ($this->getRequest()->getParam('search_catalog')) {
            $search = $this->getRequest()->getParam('search_catalog');
        }

        
        if ($search != '') {
            $data_array = $devices->fetchAll($devices->searchDevice($search));
        } elseif($select_type!='all') {
            $data_array = $devices->fetchAll($devices->select()->where("type = '$select_type'"));
        } else {
            $data_array = $devices->fetchAll(); 
        }

        //вывод навигации
        $this->_helper->navigation->initNav($sort, $select_limit, $select_type, $page, $data_array);
        $this->_helper->navigation->setView($this->view);

        
        $count = $select_limit;
        $offset = $page * $select_limit - $select_limit;

        //формируем запрос
        //запрос поиска
        if ($search != '') {
            $select = $devices->searchDevice($search)->order($sort)->limit($count, $offset);
            $this->view->search_param = "/search_catalog/$search";
        //запрос конкретного типа 
        } elseif ($select_type!='all') {
            $select = $devices->select()->where("type = '$select_type'")->order($sort)->limit($count, $offset);
        //запрос типа 'все' 
        } else {
            $select = $devices->select()->order($sort)->limit($count, $offset);
        }
        
        $type = new Application_Model_DbTable_Setup();
        $type->setTableName('type');
 
        $this->view->types = $type->getValues();
        $this->view->devices = $devices->fetchAll($select);
        $this->view->search = $search;
   }

    public function addAction() {

        $form = new Application_Form_Devices();
        $form->submit->setLabel('Добавить');
        $this->view->form = $form;
        
        // Если к нам идёт Post запрос
        if ($this->getRequest()->isPost()) {
            //принимаем данные
            $formData = $this->getRequest()->getPost();

            //Проверка валидациии    
            if ($form->isValid($formData)) {
                //Успешная валидация
                //Извлекаем данные в обьект
                $device = new Application_Model_Device($form);
                //Сохраняем данные в базу
                $device->save();
                //Переходим на предыдущую страницу
                $this->_helper->redirector('index');
            } else {
                //Неуспешная валидация
                //Возвращаем данные в таблицу
                $form->populate($formData);
            }
        }
    }

    public function editAction() {
        // action body
        $form = new Application_Form_Devices();
        $form->submit->setLabel('Редактировать');
        $_SESSION['edit'] = true;
        $this->view->form = $form;

        if ($this->getRequest()->isPost()) {

            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                //Успешная валидация
                //Извлекаем данные в обьект
                $device = new Application_Model_Device($form);
                //Редактируем данные в базе
                $device->edit();
                $this->_helper->redirector('index');
            } else {
                //Неуспешная валидация
                //Возвращаем данные в таблицу
                $form->populate($formData);
            }
        } else {

            $id = $this->getParam('id');
            $device = new Application_Model_DbTable_Devices();
            $form->populate($device->getDevice($id));
        }
    }

    public function deleteAction() {
        // action body
        $form = new Application_Form_DeleteDevice();
        $form->submit->setLabel('Удалить');
        $form->cancel->setLabel('Отмена');
        $this->view->form = $form;
        $id = $this->getParam('id');
        //если идет запрос POST
        if ($this->getRequest()->isPost()) {
            //если подтверждаем удаление
            if ($this->getRequest()->getPost('submit')) {
                $device = new Application_Model_DbTable_Devices();
                $device->deleteDevice($id);
                $this->_helper->redirector('index');
            } else {
                //если отменяем удаление
                if ($this->getRequest()->getPost('cancel')) {
                    $this->_helper->redirector('index');
                }
            }
        } else {
            //выводим дополнительные данные

            $device = new Application_Model_DbTable_Devices();

            $this->view->device = $device->getDevice($id);
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
        $sheet->setTitle('Catalog CoffeeService');
        $sheet->setCellValue("A1", '№');
        $sheet->setCellValue("B1", 'Номер');
        $sheet->setCellValue("C1", 'Название');
        $sheet->setCellValue("D1", 'Принадлежность');
        $sheet->setCellValue("E1", 'Пользователь');
        $sheet->setCellValue("F1", 'Статус');
        $sheet->setCellValue("G1", 'Город');
        $sheet->setCellValue("H1", 'Адрес торговой точки');
        $sheet->setCellValue("I1", 'Название торговой точки');
        $sheet->setCellValue("J1", 'Контактные данные');
        $sheet->setCellValue("K1", 'Номер договора');

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
        $sheet->getStyle('G1')->getFill()->getStartColor()->setRGB('FF6347');
        $sheet->getStyle('H1')->getFill()->setFillType(
                PHPExcel_Style_Fill::FILL_SOLID);
        $sheet->getStyle('H1')->getFill()->getStartColor()->setRGB('FF6347');
        $sheet->getStyle('I1')->getFill()->setFillType(
                PHPExcel_Style_Fill::FILL_SOLID);
        $sheet->getStyle('I1')->getFill()->getStartColor()->setRGB('FF6347');
        $sheet->getStyle('J1')->getFill()->setFillType(
                PHPExcel_Style_Fill::FILL_SOLID);
        $sheet->getStyle('J1')->getFill()->getStartColor()->setRGB('FF6347');
        $sheet->getStyle('K1')->getFill()->setFillType(
                PHPExcel_Style_Fill::FILL_SOLID);
        $sheet->getStyle('K1')->getFill()->getStartColor()->setRGB('FF6347');
        
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
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getColumnDimension('J')->setAutoSize(true);
        $sheet->getColumnDimension('K')->setAutoSize(true);
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
        $sheet->getStyle('I')->getNumberFormat()
                ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $sheet->getStyle('J')->getNumberFormat()
                ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $sheet->getStyle('K')->getNumberFormat()
                ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
 
        $devices = new Application_Model_DbTable_Devices();
        $data = $devices->fetchAll()->toArray();

        $i = 2;
        
        foreach ($data as $rows) {
            $number=$i-1;
            $sheet->setCellValue("A$i", "$number");
            $sheet->setCellValue("B$i", "$rows[number]");
            $sheet->setCellValue("C$i", "$rows[name]");
            $sheet->setCellValue("D$i", "$rows[owner]");
            $sheet->setCellValue("E$i", "$rows[user]");
            $sheet->setCellValue("F$i", "$rows[status]");
            $sheet->setCellValue("G$i", "$rows[city]");
            $sheet->setCellValue("H$i", "$rows[adress]");
            $sheet->setCellValue("I$i", "$rows[tt_name]");
            $sheet->setCellValue("J$i", "$rows[tt_user]");
            $sheet->setCellValue("K$i", "$rows[tt_phone]");
            $i++;
        }

// Выводим содержимое файла
        $objWriter = new PHPExcel_Writer_Excel5($xls);
        $objWriter->save('catalog.xls');
        // открываем файл в бинарном режиме
        header("Location: http://{$settings['excel']['site']}/catalog.xls");
    }

}

