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

    public function editSales($id, $number, $name, $buyer, $status) {
// ��������� ������ ����������� ��������
        $data = array(
            'number' => $number,
            'name' => $name,
            'buyer' => $buyer,
            'status' => $status
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
    
    public function searchSales($search) {
        // ��������� ������ ����������� ��������
        $select = $this->select()->where('date LIKE ?', "%$search%")
                ->orwhere('name LIKE ?', "%$search%")
                ->orwhere('number LIKE ?', "%$search%")
                ->orwhere('status LIKE ?', "%$search%")
                ->orwhere('buyer LIKE ?', "%$search%");
        // ���������� select
        return $select;
     }

}

