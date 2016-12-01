<?php

class OutletsController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
        
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
        
        $adress = 'Ukraine+'.Application_Model_Decoder::translit($device_data['city']).'+'
                .Application_Model_Decoder::translit($device_data['adress']);
        
        $position = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=$adress&key=AIzaSyAoQKZO_RC6V2FUsSISFhE72Sx5A6YQoCI");
        //$position = header( "Location: https://maps.googleapis.com/maps/api/geocode/json?address=$adress&key=AIzaSyAoQKZO_RC6V2FUsSISFhE72Sx5A6YQoCI" );
        //var_dump($position);die;
        $position = json_decode($position, true);
        $position = array ('lat' => "{$position['results'][0] ["geometry"]["location"]['lat']}",
                           'lng' => "{$position['results'][0] ["geometry"]["location"]['lng']}",
            );
        echo json_encode($position);
        die;
    }
    
    public function saveAction(){
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

}
