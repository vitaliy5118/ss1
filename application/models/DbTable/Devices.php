<?php

class Application_Model_DbTable_Devices extends Zend_Db_Table_Abstract {

    protected $_name = 'devices';

    public function addDevice(Application_Model_Device $device) {
        // сохраняем данные в таблицу
        $this->insert($device->makearray());
    }

    public function editDevice(Application_Model_Device $device) {
        // редактируем запись
        $this->update($device->makearray(), 'id=' . (int) $device->id);
    }

    public function editDeviceStatus(Application_Model_Repaire $repaire) {
        // редактирование статуса устройства
        $data = array(
            'status' => $repaire->status,
        );
        // обновляем запись
        $this->update($data, 'number=' . "'$repaire->number'");
    }

    public function getDevice($id) {
        //принимаем id
        $id = (int) $id;
        //проверка данных
        $row = $this->fetchRow('id=' . $id);
        if (!$row) {
           echo ("Ошибка!<br> Запись с идентификатором id=$id не существет!"); die; 
        }

        //возвращаем массив
        return $row->toArray();
    }
    //выгружаем данные о метках для карты
    public function getMarkData() {
        $data = $this->fetchAll("`show` = 'OK'");
        //возвращаем массив
        return $data;
    }
    
    public function saveCoordinates($data_array){
                // формируем массив данных для сохранения
        $data = array(
            'lng'   => $data_array['lng'],
            'lat'     => $data_array['lat'],
        );
        $this->update($data, 'id='."'{$data_array['id']}'");
        
    }
    
    public function saveShow($data_array){
                // формируем массив данных для сохранения
        $data = array(
            'show'   => $data_array['show'],
         );
        $this->update($data, 'id='."'{$data_array['id']}'");
        
    }
    
    public function saveColor($data_array){
                // формируем массив данных для сохранения
        $data = array(
            'color'   => $data_array['color'],
         );
        $this->update($data, 'id='."'{$data_array['id']}'");
        
    }

    public function getName($number) {
        //?????? ?????? ? ???????
        $row = $this->fetchRow('number =' . "'$number'");
        if (!row) {
            throw new Exeption("??? ?????? ? ??????? - $number");
        }
        //?????????? ????????? ? ???? ???????
        return $row->toArray();
    }

    public function deleteDevice($id) {
        //??????? ?????? ? ???????
        $this->delete('id=' . (int) $id);
    }

    public function searchDevice($search) {
        // ????????? ?????? ??????????? ????????
        $select = $this->select()->where('number LIKE ?', "%$search%")
                ->orwhere('name LIKE ?', "%$search%")
                ->orwhere('owner LIKE ?', "%$search%")
                ->orwhere('status LIKE ?', "%$search%")
                ->orwhere('user LIKE ?', "%$search%")
                ->orwhere('city LIKE ?', "%$search%")
                ->orwhere('adress LIKE ?', "%$search%")
                ->orwhere('tt_name LIKE ?', "%$search%")
                ->orwhere('tt_user LIKE ?', "%$search%")
                ->orwhere('tt_phone LIKE ?', "%$search%");

        // ?????????? select
        return $select;
    }

    //????? ??????? ????????
    public function getCountDevices($data = null) {

        if ($data) {
            $query_part = "WHERE `name` = '$data' \n
                             OR `type` = '$data'\n
                             OR `status` = '$data'\n
                             OR `owner` = '$data'\n
                             OR `user` = '$data'\n
                             OR `adress` = '$data'\n
                     ";
        }
        //?????????? ??????
        $sql = (" SELECT count(id) as count
                  FROM `devices`
                  $query_part
                ");

        return $this->getAdapter()->query($sql)->fetchAll();
    }
    //статистика количества аппаратов по имени
    public function getChart($data) {

        //формируем запрос
        $sql = (" SELECT $data, count($data) as count
                  FROM `devices`
                  GROUP BY $data
                  ORDER BY count DESC 
                ");
        
        return $this->getAdapter()->query($sql)->fetchAll();
    }

}
