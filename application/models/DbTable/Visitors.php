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
        // ��������� ������ ����������� ��������
        $data_array = array(
              'visits' => $visits
        );
        // ���������� ����� insert ��� ������� ������ � ����
        $this->update($data_array, 'id=' .(int) $id);
    }
}

