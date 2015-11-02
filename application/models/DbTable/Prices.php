<?php

class Application_Model_DbTable_Prices extends Zend_Db_Table_Abstract {

    protected $_name = 'prices';
    
    // ����� ��� ���������� ����� ������
    public function addPrice($name,$price) {
    // ��������� ������ ����������� ��������
        $data = array(
            'name' => $name,
            'price' => $price
        );

    // ���������� ����� insert ��� ������� ������ � ����
        $this->insert($data);
    }

    public function editPrice($id, $name, $price) {
// ��������� ������ ����������� ��������
        $data = array(
            'name' => $name,
            'price' => $price
        );

// ���������� ����� insert ��� ������� ������ � ����
        $this->update($data, 'id=' . (int) $id);
    }

    public function getPrice($id) {
// �������� id ��� ��������
        $id = (int) $id;

// ���������� ����� fetchRow ��� ��������� ������ �� ����.
// � ������� ��������� ������� ������� (��������� ��� ��� where)
        $row = $this->fetchRow('id = ' . $id);

// ���� ��������� ������, ���������� ����������
        if (!$row) {
            throw new Exception("��� ������ � id - $id");
        }
// ���������� ���������, ����������� � ������
        return $row->toArray();
    }

    public function deletePrice($id) {
        $this->delete('id=' . (int) $id);
    }
    

}

