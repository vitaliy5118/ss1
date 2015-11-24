<?php

class WarehouseController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
        
                // создаем модель
        $warehouse = new Application_Model_DbTable_Warehouse();
        
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
            $data_array = $warehouse->fetchAll($warehouse->searchWarehouse($search));
        } else {
            $data_array = $warehouse->fetchAll(); 
        }

        //вывод навигации
        $this->_helper->navigation->initNav($sort, $select_limit, $select_type, $page, $data_array);
        $this->_helper->navigation->setView($this->view);

        
        $count = $select_limit;
        $offset = $page * $select_limit - $select_limit;

        //формируем запрос
        //запрос поиска
        if ($search != '') {
            $select = $warehouse->searchWarehouse($search)->order($sort)->limit($count, $offset);
            $this->view->search_param = "/search_catalog/$search";
        //запрос конкретного типа 
        } else {
            $select = $warehouse->select()->order($sort)->limit($count, $offset);
        }
        
   
        $this->view->warehouse = $warehouse->fetchAll($select);
        $this->view->search = $search;
   }

   public function addAction() {
        // Создаём форму
        $form = new Application_Form_Warehouse();

        // Указываем текст для submit
        $form->submit->setLabel('Добавить');

        // Передаём форму в view
        $this->view->form = $form;

        // Если к нам идёт Post запрос
        if ($this->getRequest()->isPost()) {
            // Принимаем его
            $formData = $this->getRequest()->getPost();

            // Если форма заполнена верно
            if ($form->isValid($formData)) {
                // Извлекаем данные
                
                $image = $form->getValue('image'); // загрузить картинку
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
                
                // Создаём объект модели
                $warehouse = new Application_Model_DbTable_Warehouse();
                $warehouse->addWarehouse($serial, $name, $type, $remain, $price, $filename); 
                
                $warehistory = new Application_Model_DbTable_Warehistory();
                $warehistory->addWarehistory($serial, $name, 'новая запись', '0', $remain, $remain); 

                // Используем библиотечный helper для редиректа на action = index
                $this->_helper->redirector('index');
            } else {
                // Если форма заполнена неверно,
                // используем метод populate для заполнения всех полей
                // той информацией, которую ввёл пользователь
                $form->populate($formData);
            }
        }
    }

    public function loadAction() {
        
        $warehouse = new Application_Model_DbTable_Warehouse();
        $this->view->warehouse = $warehouse->fetchAll(); //загружаем данные склада
        
        // Если к нам идёт Post запрос
        if ($this->getRequest()->isPost()) {

            // Принимаем его
            $formData = $this->getRequest()->getPost(); //принимаем данные
            
            //проверяем все данные на совпадение правилам
            foreach ($formData as $name => $value ) {
                
                //проверка регулярных выражений
                if (!preg_match("/^[0-9\.]{0,10}$/i", $value)) { 
                    $error["$name"]='error'; //записываем несовпадение правилам
                }
                 //ищем переменные check
                if (preg_match("/check/", $name)) {
                    $checked["$value"] = "checked"; //запись в массив нажатых галочек
                }
                //записываем данные формы
                if ($value) {
                    $check_data["$name"] = $value; 
                }
                
            }
            //если нет ошибкок
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
                                                //Вызываем метод модели addMovie для вставки новой записи
                        $warehouse->loadWarehouse($id, $remain, $price);
                        
                        $warehistory->addWarehistory($serial, $name, "загрузка запчастей",$row['remain'],$check_data["remain_$id"], $remain);
                    
                        }
                        } 
                }
                
                $this->_helper->redirector->gotoUrl("warehouse/index/");
               
            //если есть ошибки
            } else {
              $this->view->error = $error;
              $this->view->check_data = $check_data;
              $this->view->checked = $checked;
            }
        }
    }

    public function unloadAction() {
        
        $warehouse = new Application_Model_DbTable_Warehouse();
        $this->view->warehouse = $warehouse->fetchAll(); //загружаем данные склада
        
        // Если к нам идёт Post запрос
        if ($this->getRequest()->isPost()) {

            // Принимаем его
            $formData = $this->getRequest()->getPost(); //принимаем данные

            //создаем массив данных из пунктов где нажаты галочки
            foreach ($formData as $data => $value ){
                if (preg_match("/check/", $data)) { //ищем переменные check
                    $check_data[$value] = $formData["$value"]; //создаем массив данных
                    $checked["$value"] = "checked"; //запись в массив нажатых галочек
                   
                    if (!preg_match("/^[0-9]{0,10}$/i",$formData["$value"])) { //проверка регулярных выражений
                     
                       $error["$value"]='error'; //записываем несовпадение правилам
                    }
                    
                    //проверка на количество запчастей в базе
                    $spare_count= $warehouse->getWarehouse($value);   //подгружаем данные
                    if(($spare_count['remain']-$formData["$value"])<0){   //делаем проверку
                        $error["$value"]='error'; //записываем несовпадение правилам
                    } 
                }
            }
            //принимаем данные
            $recipient = $this->getRequest()->getPost('recipient');
            $comments = $this->getRequest()->getPost('comments');

            //проверка регулярных выражений
            if (!preg_match("/^[А-Яа-я \.\,]{3,30}$/i",$recipient)){$error['recipient']='error';}
            if (!preg_match("/^[А-Яа-я \.\,]{0,30}$/i",$comments)){$error['comments']='error';}
            
            //если нет ошибкок
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
                        
                        $description= "Выдача || $recipient" ;
                        if ($comments) {
                            $description.=" || $comments";
                        }
                        // Вызываем метод модели addMovie для вставки новой записи
                        $warehouse->editWarehouse($id, $serial, $name, $type, $remain, 'unload', $path);
                        $warehistory->addWarehistory($serial, $name, $description,$row['remain'],$value, $remain);
                    }
                }
                
                $this->_helper->redirector->gotoUrl("warehouse/index/");
                
                
            //если есть ошибки
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
        // Создаём форму
        $form = new Application_Form_Warehouse();

        // Указываем текст для submit
        $form->submit->setLabel('Редактировать');

        // Передаём форму в view
        $this->view->form = $form;
        
        $warehouse = new Application_Model_DbTable_Warehouse();
        
        $id = $this->_getParam('id', 0);
        $data = $warehouse->getWarehouse($id);

        // Если к нам идёт Post запрос
        if ($this->getRequest()->isPost()) {
            // Принимаем его
            $formData = $this->getRequest()->getPost();

            // Если форма заполнена верно
            if ($form->isValid($formData)) {
                // Извлекаем данные
                $id = (int) $form->getValue('id');
                $serial = $form->getValue('serial');
                $name = $form->getValue('name');
                $type = $form->getValue('type');
                $remain = $form->getValue('remain');
                $price = $form->getValue('price');
                
                $image = $form->getValue('image'); // загрузить картинку
                
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
                // Вызываем метод модели addMovie для вставки новой записи
                $warehouse->editWarehouse($id, $serial, $name, $type, $remain, $price, $filename);
                
                $warehistory = new Application_Model_DbTable_Warehistory();
                $warehistory->addWarehistory($serial, $name, "редактирование", $data['remain'], $remain-$data['remain'], $remain); 

                // Используем библиотечный helper для редиректа на action = index
                $this->_helper->redirector('index');
            } else {
                // Если форма заполнена неверно,
                // используем метод populate для заполнения всех полей
                // той информацией, которую ввёл пользователь
                $form->populate($formData);
            }
        } else {

            if ($id > 0) {
                // Создаём объект модели
                $form->populate($data);
                $this->view->path = $data['path'];
            }
        }
    }

    public function deleteAction() {
        // Если к нам идёт Post запрос
        if ($this->getRequest()->isPost()) {
            // Принимаем значение
            $del = $this->getRequest()->getPost('del');

            // Если пользователь подтвердил своё желание удалить запись
            if ($del == 'Да') {
                // Принимаем id записи, которую хотим удалить
                $id = $this->getRequest()->getPost('id');
                // Создаём объект модели
                $warehouse = new Application_Model_DbTable_Warehouse();

                // Вызываем метод модели deleteMovie для удаления записи
                $warehouse->deleteWarehouse($id);
            }

            // Используем библиотечный helper для редиректа на action = index
            $this->_helper->redirector('index');
        } else {
            // Если запрос не Post, выводим сообщение для подтверждения
            // Получаем id записи, которую хотим удалить
            $id = $this->_getParam('id');

            // Создаём объект модели
            $warehouse = new Application_Model_DbTable_Warehouse();

            // Достаём запись и передаём в view
            $this->view->warehouse = $warehouse->getWarehouse($id);
        }
    }
    
    public function historyAction() {
    
        $warehistory = new Application_Model_DbTable_Warehistory();

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
        
        $data_array = $warehistory->fetchAll(); 
        
        //вывод навигации
        $this->_helper->navigation->initNav($sort, $select_limit, $select_type, $page, $data_array);
        $this->_helper->navigation->setView($this->view);

        
        $count = $select_limit;
        $offset = $page * $select_limit - $select_limit;
 
        $select = $warehistory->select()->order('data DESC')->limit($count, $offset);
        
        $this->view->warehistory = $warehistory->fetchAll($select);
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
        $sheet->setTitle('Warehouse CoffeeService');
        $sheet->setCellValue("A1", '№');
        $sheet->setCellValue("B1", 'Серийный номер');
        $sheet->setCellValue("C1", 'Название');
        $sheet->setCellValue("D1", 'Тип');
        $sheet->setCellValue("E1", 'Остаток');
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

// Выводим содержимое файла
        $objWriter = new PHPExcel_Writer_Excel5($xls);
        $objWriter->save('warehouse.xls');
        // открываем файл в бинарном режиме
        header("Location: http://{$settings['excel']['site']}/warehouse.xls");
    }
  

}
