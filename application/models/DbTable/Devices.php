<?php

class Application_Model_DbTable_Devices extends Zend_Db_Table_Abstract {

    protected $_name = 'devices';
    
    public function addDevice($number, $name, $owner, $user, $status) {

        // ��������� ������ ����������� ��������
        $data = array(
            'number' => $number,
            'name' => $name,
            'owner' => $owner,
            'user' => $user,
            'status' => $status
        );
        // ���������� ����� insert ��� ������� ������ � ����
        $this->insert($data);
    }
    
    public function editDevice($id, $number, $name, $owner, $user, $status) {

        // ��������� ������ ����������� ��������
        $data = array(
            'number' => $number,
            'name' => $name,
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
 
    public function deleteDevice($id) {
        //������� ������ � �������
        $this->delete('id=' . (int) $id);
     }
    public function searchDevice($search) {
        // ��������� ������ ����������� ��������
        $select = $this->select()->where('number = ?', $search)
                ->orwhere('name = ?', $search)
                ->orwhere('owner = ?', $search)
                ->orwhere('status = ?', $search)
                ->orwhere('user = ?', $search);
        // ���������� select
        return $select;
     }
     
         //����� ������� ��������
    public function getCountDevices($data = null) {
        
        if($data){
             $query_part = "WHERE `name` LIKE '%$data%' \n
                             OR `status` LIKE '%$data%'\n
                             OR `owner` LIKE '%$data%'\n
                             OR `user` LIKE '%$data%'\n
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

