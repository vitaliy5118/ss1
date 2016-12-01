<?php

class Application_Model_DbTable_History extends Zend_Db_Table_Abstract {

    protected $_name = 'history';

    public function getHistory($number = null) {

        $sql = (" SELECT history.data, history.number, history.owner, history.status, 
                        history.status, history.user, history.adress,  history.city, 
                        history.tt_name, history.tt_user, history.tt_phone,devices.name
                  FROM `history` JOIN `devices` ON history.number=devices.number
                  WHERE history.number = '$number'
                  ORDER BY data DESC
                ");

        return $this->getAdapter()->query($sql)->fetchAll();
    }

    public function addHistory(Application_Model_Device $device) {

        // ��������� ������ ����������� ��������
        $data = array(
            'number' => $device->number,
            'owner' => $device->owner,
            'user' => $device->user,
            'status' => $device->status,
            'city' => $device->city,
            'adress' => $device->adress,
            'tt_name' => $device->tt_name,
            'tt_user' => $device->tt_user,
            'tt_phone' => $device->tt_phone
        );
        // ���������� ����� insert ��� ������� ������ � ����
        $this->insert($data);
    }

    public function addRepairHistory(Application_Model_Repaire $repaire) {

        // ��������� ������ ����������� ��������
        $data = array(
            'number' => $repaire->number,
            'owner' => '������',
            'user' => '������',
            'status' => $repaire->status,
            'city' => '������',
            'adress' => '������',
            'tt_name' => '������',
            'tt_user' => '������',
            'tt_phone' => '������'
        );

        // ���������� ����� insert ��� ������� ������ � ����
        $this->insert($data);
    }

}
