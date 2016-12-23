<?php

class OutletsController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
        
        //если идет запрос типа
        if ($this->getRequest()->getParam('lng') && $this->getRequest()->getParam('lng')) {
            $this->view->lng = $this->getRequest()->getParam('lng');
            $this->view->lat = $this->getRequest()->getParam('lat');
            $this->view->zoom = 17;
        } else {
            global $settings;
            $this->view->lat = $settings['poltava']['lat'];
            $this->view->lng = $settings['poltava']['lng'];
            $this->view->zoom = 8;
        } 
        
        
    }

    public function autofindAction() {

        //если идет запрос типа
        if ($this->getRequest()->getParam('id')) {
            $id = $this->getRequest()->getParam('id');
        } else {
            echo json_encode('NULL'); //передаем id клиенту
            die;
        }

        $device = new Application_Model_DbTable_Devices();
        $device_data = $device->getDevice($id);

        //проверяем наличие данных
        if ($device_data['city'] && $device_data['adress']) {
            
            //решаем проблемму с кодировкой
            $position = iconv('cp1251', 'utf-8', "Украина+{$device_data['city']}+{$device_data['adress']}");

//            $adress = 'Ukraine+' . Application_Model_Decoder::translit($device_data['city']) . '+'
//                    . Application_Model_Decoder::translit($device_data['adress']);
//            
//           $position = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=$adress&key=AIzaSyAoQKZO_RC6V2FUsSISFhE72Sx5A6YQoCI");
//           //$position = header( "Location: https://maps.googleapis.com/maps/api/geocode/json?address=$adress&key=AIzaSyAoQKZO_RC6V2FUsSISFhE72Sx5A6YQoCI" );
//
//            $position = json_decode($position, true);
//            $position = array('lat' => "{$position['results'][0] ["geometry"]["location"]['lat']}",
//                'lng' => "{$position['results'][0] ["geometry"]["location"]['lng']}",
//            );
        } else {
            $position = '';
        }

        echo json_encode($position);
        die;
    }

    public function saveAction() {
        //если идет запрос типа
        if ($this->getRequest()->getParam('coordinates')) {
            $coordinates = $this->getRequest()->getParam('coordinates');
            $save = new Application_Model_DbTable_Devices();
            $save->saveCoordinates($coordinates);
            echo ('Saving done');
            die;
        } else {
            echo ('Error Nodata');
            die;
        }
    }

    public function saveshowAction() {
        //если идет запрос типа
        if ($this->getRequest()->getParam('show')) {
            $show = $this->getRequest()->getParam('show');
            $save = new Application_Model_DbTable_Devices();
            $save->saveShow($show);
            echo ('saving done');
            die;
        } else {
            echo ('Error Nodata');
            die;
        }
    }

    public function savecolorAction() {
        //если идет запрос типа
        if ($this->getRequest()->getParam('color')) {
            $color = $this->getRequest()->getParam('color');
            $save = new Application_Model_DbTable_Devices();
            $save->saveColor($color);
            echo ('saving done');
            die;
        } else {
            echo ('Error Nodata');
            die;
        }
    }

    public function getmarkdataAction() {

        $getdata = new Application_Model_DbTable_Devices();
        $markdata = $getdata->getMarkData()->toArray();
        
        //решаем проблему с кодировкой UTF-8
        //делаем транслит
        $i = 0;
        foreach ($markdata as $rows) {
            foreach ($rows as $name => $value) {

                if ($name == 'adress' || $name == 'owner' || $name == 'user' ||
                        $name == 'status' || $name == 'city' || $name == 'adress' ||
                        $name == 'tt_name' || $name == 'tt_user' || $name == 'tt_phone') {
                    //$value = Application_Model_Decoder::translit($value);
                    $value =  iconv('cp1251', 'utf-8', $value);
                }
                $data_row["$name"] = $value;
            }

            $i++;
            $data_array["$i"] = $data_row;
        }
        //сохраняем количество записей в массиве
        $data_array['i'] = $i;
        //кодируем данные
        $markdata = json_encode($data_array);
        //var_dump($data_array); die;
        if ($markdata) {
            echo ($markdata);
            die;
        } else {
            echo ('Error Nodata');
            die;
        }
    }

}
