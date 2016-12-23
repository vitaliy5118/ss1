<?php

class Application_Model_Device {

    //put your code here
    public $id;
    public $number;
    public $name;
    public $type;
    public $owner;
    public $user;
    public $status;
    public $city;
    public $adress;
    public $tt_name;
    public $tt_user;
    public $tt_phone;
    public $lat;
    public $lan;
    public $show;
    public $color;

    public function __construct(Application_Form_Devices $form) {

        if ($form->getValue('id') != NULL) {
            $this->id = $form->getValue('id');
        }
        $this->number = $form->getValue('number');
        $this->name = $form->getValue('name');
        $this->type = $form->getValue('type');
        $this->owner = $form->getValue('owner');
        $this->user = $form->getValue('user');
        $this->status = $form->getValue('status');
        $this->city = $form->getValue('city');
        $this->adress = $form->getValue('adress');
        $this->tt_name = $form->getValue('tt_name');
        $this->tt_user = $form->getValue('tt_user');
        $this->tt_phone = $form->getValue('tt_phone');
    }

    public function makearray() {
        // формируем массив данных для сохранения
        $data = array(
            'number'   => $this->number,
            'name'     => $this->name,
            'type'     => $this->type,
            'owner'    => $this->owner,
            'user'     => $this->user,
            'status'   => $this->status,
            'city'     => $this->city,
            'adress'   => $this->adress,
            'tt_name'  => $this->tt_name,
            'tt_user'  => $this->tt_user,
            'tt_phone' => $this->tt_phone,
            'lat'      => 'нет данных',
            'lng'      => 'нет данных',
        );
        return $data;
    }

    public function save() {
        //Добавляем данные в базу
        $save_data = new Application_Model_DbTable_Devices();
        $save_data->addDevice($this);
        //Сохраняем историю
        $history = new Application_Model_DbTable_History();
        $history->addHistory($this);
    }

    public function edit() {
        //Добавляем данные в базу
        $save_data = new Application_Model_DbTable_Devices();
        $save_data->editDevice($this);
        //Сохраняем историю
        $history = new Application_Model_DbTable_History();
        $history->addHistory($this);
    }

}
