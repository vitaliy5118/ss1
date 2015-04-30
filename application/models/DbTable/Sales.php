<?php

class Application_Model_DbTable_Sales extends Zend_Db_Table_Abstract {

    protected $_name = 'sales';
    
    // ����� ��� ���������� ����� ������
    public function addSales($number, $name, $buyer, $status) {
    // ��������� ������ ����������� ��������
        $data = array(
            'number' => $number,
            'name' => $name,
            'buyer' => $buyer,
            'status' => $status
        );

    // ���������� ����� insert ��� ������� ������ � ����
        $this->insert($data);
    }

    public function editSales($id, $date, $number, $name, $comments) {
// ��������� ������ ����������� ��������
        $data = array(
            'date' => $date,
            'number' => $number,
            'name' => $name,
            'comments' => $comments
        );

// ���������� ����� insert ��� ������� ������ � ����
        $this->update($data, 'id=' . (int) $id);
    }

    public function getSales($id) {
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

    public function deleteSales($id) {
        $this->delete('id=' . (int) $id);
    }

}

