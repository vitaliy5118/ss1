<?php

class Application_Model_DbTable_Devices extends Zend_Db_Table_Abstract {

    protected $_name = 'devices';
    
    public function addDevice($device) {
        
        // ‘ормируем массив вставл€емых значений
        $data = array(
            'number'   => $device['number'],
            'name'     => $device['name'],
            'type'     => $device['type'],
            'owner'    => $device['owner'],
            'user'     => $device['user'],
            'status'   => $device['status'],
            'city'     => $device['city'], 
            'adress'   => $device['adress'], 
            'tt_name'  => $device['tt_name'], 
            'tt_user'  => $device['tt_user'], 
            'tt_phone' => $device['tt_phone']
        );
        // »спользуем метод insert дл€ вставки записи в базу
        $this->insert($data);
    }
    
    public function editDevice($device) {

        // ‘ормируем массив вставл€емых значений
        $data = array(
            'number'   => $device['number'],
            'name'     => $device['name'],
            'type'     => $device['type'],
            'owner'    => $device['owner'],
            'user'     => $device['user'],
            'status'   => $device['status'],
            'city'     => $device['city'], 
            'adress'   => $device['adress'], 
            'tt_name'  => $device['tt_name'], 
            'tt_user'  => $device['tt_user'], 
            'tt_phone' => $device['tt_phone']
        );
        // »спользуем метод insert дл€ вставки записи в базу
        $this->update($data, 'id=' .(int) $device['id']);
    }
    public function editDeviceStatus($number, $status) {
         // ‘ормируем массив вставл€емых значений
        $data = array(
            'status' => $status,
        );
        // »спользуем метод insert дл€ вставки записи в базу
        $this->update($data, 'number=' ."'$number'");
    }
    
    public function getDevice($id) {
        //принимаем id
        $id = (int) $id;
        //читаем данные с таблицы
        $row = $this->fetchRow('id=' . $id);
        if (!row) {
            throw new Exeption("Ќет записи с по номеру - $id");
        }
        //возвращаем результат в виде массива
        return $row->toArray();
    }
    public function getName($number) {
        //читаем данные с таблицы
        $row = $this->fetchRow('number =' . "'$number'");
        if (!row) {
            throw new Exeption("Ќет записи с номером - $number");
        }
        //возвращаем результат в виде массива
        return $row->toArray();
    }
 
    public function deleteDevice($id) {
        //удал€ем данные с таблицы
        $this->delete('id=' . (int) $id);
     }
    public function searchDevice($search) {
        // ‘ормируем массив вставл€емых значений
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

        // ¬озвращаем select
        return $select;
     }
     
         //ћетод выборки значений
    public function getCountDevices($data = null) {
        
        if($data){
             $query_part = "WHERE `name` = '$data' \n
                             OR `type` = '$data'\n
                             OR `status` = '$data'\n
                             OR `owner` = '$data'\n
                             OR `user` = '$data'\n
                             OR `adress` = '$data'\n
                     ";
        }
        //—оставл€ем запрос
        $sql = (" SELECT count(id) as count
                  FROM `devices`
                  $query_part
                ");

        return $this->getAdapter()->query($sql)->fetchAll();
    }

}

