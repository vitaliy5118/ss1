<?php

class Application_Model_DbTable_Warehistory extends Zend_Db_Table_Abstract {

    protected $_name = 'warehistory';
    
    public function addWarehistory($serial, $name, $method, $presence, $expense = 0, $remain) {

        // ��������� ������ ����������� ��������
        $data = array(
            'serial' => $serial,
            'name' => $name,
            'method' => $method,
            'presence' => $presence,
            'expense' => $expense,
            'remain' => $remain
        );
        // ���������� ����� insert ��� ������� ������ � ����
        $this->insert($data);
    }
    
    public function editDevice($id, $number, $name, $type, $owner, $user, $status) {

        // ��������� ������ ����������� ��������
        $data = array(
            'number' => $number,
            'name' => $name,
            'type' => $type,
            'owner' => $owner,
            'user' => $user,
            'status' => $status
        );
        // ���������� ����� insert ��� ������� ������ � ����
        $this->update($data, 'id=' .(int) $id);
    }
    
    public function getDevice($id) {
        //��������� id
        $id = (int) $id;
        //������ ������ � �������
        $row = $this->fetchRow('id=' . $id);
        if (!row) {
            throw new Exeption("��� ������ � �� ������ - $id");
        }
        //���������� ��������� � ���� �������
        return $row->toArray();
    }
    public function getName($number) {
        //��������� id
        $number = (int) $number;
        //������ ������ � �������
        $row = $this->fetchRow('number=' . $number);
        if (!row) {
            throw new Exeption("��� ������ � ������� - $number");
        }
        //���������� ��������� � ���� �������
        return $row->toArray();
    }
 
    public function deleteDevice($id) {
        //������� ������ � �������
        $this->delete('id=' . (int) $id);
     }
    public function searchDevice($search) {
        // ��������� ������ ����������� ��������
        $select = $this->select()->where('number LIKE ?', "%$search%")
                ->orwhere('name LIKE ?', "%$search%")
                ->orwhere('owner LIKE ?', "%$search%")
                ->orwhere('status LIKE ?', "%$search%")
                ->orwhere('user LIKE ?', "%$search%");
        // ���������� select
        return $select;
     }
     
         //����� ������� ��������
    public function getCountDevices($data = null) {
        
        if($data){
             $query_part = "WHERE `name` = '$data' \n
                             OR `type` = '$data'\n
                             OR `status` = '$data'\n
                             OR `owner` = '$data'\n
                             OR `user` = '$data'\n
                     ";
        }
        //���������� ������
        $sql = (" SELECT count(id) as count
                  FROM `devices`
                  $query_part
                ");

        return $this->getAdapter()->query($sql)->fetchAll();
    }

}

