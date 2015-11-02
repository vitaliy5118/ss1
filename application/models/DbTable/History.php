<?php

class Application_Model_DbTable_History extends Zend_Db_Table_Abstract {

    protected $_name = 'history';
    
    public function getHistory($number = null) {
        
        $sql = (" SELECT history.data, history.number, history.owner, history.status, 
                        history.status, history.user, devices.name
                  FROM `history` JOIN `devices` ON history.number=devices.number
                  WHERE history.number = '$number'
                  ORDER BY data DESC
                ");

        return $this->getAdapter()->query($sql)->fetchAll();
    }
    
    public function addHistory($number, $owner, $user, $status) {
        
        // Формируем массив вставляемых значений
        $data = array(
            'number' => $number,
            'owner' => $owner,
            'user' => $user,
            'status' => $status
        );
        // Используем метод insert для вставки записи в базу
        $this->insert($data);
    }
}

