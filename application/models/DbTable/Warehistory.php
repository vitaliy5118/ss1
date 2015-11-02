<?php

class Application_Model_DbTable_Warehistory extends Zend_Db_Table_Abstract {

    protected $_name = 'warehistory';
    
    public function addWarehistory($serial, $name, $method, $presence, $expense = 0, $remain) {

        // ‘ормируем массив вставл€емых значений
        $data = array(
            'serial' => $serial,
            'name' => $name,
            'method' => $method,
            'presence' => $presence,
            'expense' => $expense,
            'remain' => $remain
        );
        // »спользуем метод insert дл€ вставки записи в базу
        $this->insert($data);
    }
    
    public function editDevice($id, $number, $name, $type, $owner, $user, $status) {

        // ‘ормируем массив вставл€емых значений
        $data = array(
            'number' => $number,
            'name' => $name,
            'type' => $type,
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
    public function getName($number) {
        //принимаем id
        $number = (int) $number;
        //читаем данные с таблицы
        $row = $this->fetchRow('number=' . $number);
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
                ->orwhere('user LIKE ?', "%$search%");
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

