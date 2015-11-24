<?php

class Application_Model_DbTable_Visitors extends Zend_Db_Table_Abstract {

    protected $_name = 'visitors';

    public function addips($user_ip) {

        $data_array = array(
              'ips' => $user_ip
            , 'visits' => 1
        );
 
        $this->insert($data_array);
    }
    
    public function editips($id, $visits) {
        // Формируем массив вставляемых значений
        $data_array = array(
              'visits' => $visits
        );
        // Используем метод insert для вставки записи в базу
        $this->update($data_array, 'id=' .(int) $id);
    }
}

