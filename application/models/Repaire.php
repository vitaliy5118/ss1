<?php

class Application_Model_Repaire {

    //put your code here
    public $id;
    public $number;
    public $claim;
    public $diagnos;
    public $spares;
    public $work;
    public $comments;
    public $counter;
    public $status;
    public $check_data = array();
    public $checked = array();

    public function __construct($formData) {

        //извлекаем параметры ремонта
        if ($formData['id'] != NULL) {
            $this->id = $formData['id'];
        }
        $this->claim = $formData['claim'];
        $this->diagnos = $formData['diagnos'];
        $this->spares = $formData['spares'];
        $this->work = $formData['work'];
        $this->comments = $formData['comments'];
        $this->counter = $formData['counter'];
        $this->status = $formData['status'];

        //создаем массив данных использованных запчастей
        foreach ($formData as $name => $value) {
            if (preg_match("/check/", $name)) { //ищем переменные check
                $this->check_data[$value] = $formData["$value"]; //создаем массив данных
                $this->checked["$value"] = "checked"; //запись в массив нажатых галочек
            }
        }
    }

    //метод для проверки регулярных выражений в форме
    public function checkForm() {

        //проверка регулярных выражений
        if (!preg_match("/^[А-Яа-яA-Za-z0-9 \.\,\-\ \!\;\:]{3,300}$/i", $this->claim)) {
            $error['claim'] = 'error';
        }
        if (!preg_match("/^[А-Яа-яA-Za-z0-9 \.\,\-\ \!\;\:]{3,300}$/i", $this->diagnos)) {
            $error['diagnos'] = 'error';
        }
        if (!preg_match("/^[А-Яа-яA-Za-z0-9 \.\,\-\ \!\;\:]{3,300}$/i", $this->spares)) {
            $error['spares'] = 'error';
        }
        if (!preg_match("/^[А-Яа-яA-Za-z0-9 \.\,\-\ \!\;\:]{3,300}$/i", $this->work)) {
            $error['work'] = 'error';
        }
        if (!preg_match("/^[А-Яа-яA-Za-z0-9 \.\,\-\ \!\;\:]{3,300}$/i", $this->comments)) {
            $error['comments'] = 'error';
        }
        if (!preg_match("/^[А-Яа-яA-Za-z0-9 \.\,\-\ \!\;\:]{0,300}$/i", $this->counter)) {
            $error['counter'] = 'error';
        }
        if (!preg_match("/^[А-Яа-яA-Za-z0-9 \.\,\-\ \!\;\:]{3,300}$/i", $this->status)) {
            $error['status'] = 'error';
        }

        //проверяем массив данных на наличие ошибок 
        $warehouse = new Application_Model_DbTable_Warehouse();

        foreach ($this->check_data as $name => $value) {
            if (!preg_match("/^[0-9]{0,10}$/i", $this->check_data["$name"])) { //проверка регулярных выражений
                $error["$name"] = 'error'; //записываем несовпадение правилам
            } else {
                //проверка на наличие запчастей в базе
                $spare_count = $warehouse->getWarehouse($name);   //подгружаем данные
                if (($spare_count['remain'] - $this->check_data["$name"]) < 0) { //делаем проверку
                    $error["$name"] = 'error'; //записываем несовпадение правилам
                }
            }
        }
        return $error;
    }

    public function makearray() {
        // формируем массив данных для сохранения
        $data = array(
            'number' => $this->number,
            'claim' => $this->claim,
            'diagnos' => $this->diagnos,
            'work' => $this->work,
            'spares' => $this->spares,
            'comments' => $this->comments,
            'counter' => $this->counter,
            'serialize_data' => serialize($this->check_data),
            'serialize_checked' => serialize($this->checked)
        );
        return $data;
    }

    public function saveRepair($number, $device) {
        // вносим изменения в склад запчастей
        $this->number = $number;

        if ($this->spares != '') {
            $this->spares.=' ';
        }
        if ($this->check_data) {

            $warehouse = new Application_Model_DbTable_Warehouse();
            $warehistory = new Application_Model_DbTable_Warehistory();

            foreach ($this->check_data as $id => $value) {
                $spare_data = $warehouse->getWarehouse($id);

                $this->spares.= "|| {$spare_data['serial']}-{$spare_data['name']}-{$value}шт ";
                $serial = $spare_data['serial'];
                $name = $spare_data['name'];
                $type = $spare_data['type'];
                $remain = $spare_data['remain'] - $value;
                $price = 'unload';
                $path = $spare_data['path']; //имя картинки для запчасти
                // Вызываем метод модели addMovie для вставки новой записи
                $warehouse->editWarehouse($id, $serial, $name, $type, $remain, $price, $path);

                $warehistory->addWarehistory($serial, $name, "{$this->number}-{$device['name']}", $spare_data['remain'], $value, $remain);
            }
        }

        //сохраняем ремонт
        $save_data = new Application_Model_DbTable_Repairs();
        $save_data->addRepaire($this);
        //сохраняем статус ремонта
        $devices = new Application_Model_DbTable_Devices();
        $devices->editDeviceStatus($this);
        //сохраняем историю
        $history = new Application_Model_DbTable_History();
        $history->addRepairHistory($this);
    }

    public function editRepair($id, $number, $device) {
        // вносим изменения в склад запчастей
        $this->id = $id;
        $this->number = $number;

        if ($this->spares != '') {
            $this->spares.=' ';
        }
        if ($this->check_data) {

            $warehouse = new Application_Model_DbTable_Warehouse();
            $warehouse_data = $warehouse->fetchAll();
            $warehistory = new Application_Model_DbTable_Warehistory();

            foreach ($this->check_data as $id => $value) {

                $spare_data = $warehouse->getWarehouse($id);
                $this->spares.= "|| {$spare_data['serial']}-{$spare_data['name']}-{$value}шт ";

                $row = $warehouse->getWarehouse($id);

                foreach ($warehouse_data as $rows) {
                    if ($rows['id'] == $id) {
                        $this->remain = $rows['remain'];
                    }
                }
                $this->remain -= $value;
                $this->serial = $row['serial'];
                $this->name = $row['name'];
                $this->type = $row['type'];
                $this->price = 'unload';
                $this->path = $row['path']; //имя картинки для запчасти
                // Вызываем метод модели addMovie для вставки новой записи
                $warehouse->editWarehouse($id, $this->serial, $this->name, $this->type, $this->remain, $this->price, $this->path);

                $warehistory->addWarehistory($this->serial, $this->name, "редактирование ремонта -{$this->number}-{$device['name']}", $row['remain'], $value, $this->remain);
            }
        }
        
        //редактируем запись о ремонте
        $editRepaire = new Application_Model_DbTable_Repairs();
        $editRepaire->editRepaire($this);
        //сохраняем статус ремонта
        $devices = new Application_Model_DbTable_Devices();
        $devices->editDeviceStatus($this);
        //сохраняем историю
        $history = new Application_Model_DbTable_History();
        $history->addRepairHistory($this);
    }

}
