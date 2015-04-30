<?php

class Application_Model_DbTable_Devices extends Zend_Db_Table_Abstract {

    protected $_name = 'devices';
    
    public function addDevice($number, $name, $owner, $user, $status) {

        // ‘ормируем массив вставл€емых значений
        $data = array(
            'number' => $number,
            'name' => $name,
            'owner' => $owner,
            'user' => $user,
            'status' => $status
        );
        // »спользуем метод insert дл€ вставки записи в базу
        $this->insert($data);
    }
    
    public function editDevice($id, $number, $name, $owner, $user, $status) {

        // ‘ормируем массив вставл€емых значений
        $data = array(
            'number' => $number,
            'name' => $name,
            'owner' => $owner,
            'user' => $user,
            'status' => $status
        );
        // »спользуем метод insert дл€ вставки записи в базу
        $this->update($data, 'id=' .(int) $id);
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
 
    public function deleteDevice($id) {
        //удал€ем данные с таблицы
        $this->delete('id=' . (int) $id);
     }
    public function searchDevice($search) {
        // ‘ормируем массив вставл€емых значений
        $select = $this->select()->where('number = ?', $search)
                ->orwhere('name = ?', $search)
                ->orwhere('owner = ?', $search)
                ->orwhere('status = ?', $search)
                ->orwhere('user = ?', $search);
        // ¬озвращаем select
        return $select;
     }
     
         //ћетод выборки значений
    public function getCountDevices($data = null) {
        
        if($data){
             $query_part = "WHERE `name` LIKE '%$data%' \n
                             OR `status` LIKE '%$data%'\n
                             OR `owner` LIKE '%$data%'\n
                             OR `user` LIKE '%$data%'\n
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

