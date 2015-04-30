<?php

class Application_Model_DbTable_Warehouse extends Zend_Db_Table_Abstract {

    protected $_name = 'warehouse';
    
    // ����� ��� ������ ��������
    public function searchWarehouse($search) {
        // ��������� ������ ����������� ��������
        $select = $this->select()->where('serial = ?', $search)
                ->orwhere('name = ?', $search)
                ->orwhere('remain = ?', $search)
                ->orwhere('lastadding = ?', $search)
                ->orwhere('type = ?', $search);
        // ���������� select
        return $select;
    }

    //����� ������� ��������
    public function orderWarehouse($order) {
        //������ ���������� ���������
        global $settings;
        // ��������� ������ ����������� ��������
        $query_part = "";
        //���������� ����� ��� ������� �� ����
        foreach ($settings['order']["$order"] as $value) {
            if ($query_part == "") {
                $query_part.= "WHERE `name` LIKE '%$value%'\n";
            } else {
                $query_part.= "OR `name` LIKE '%$value%'\n";
            }
        }
        //���������� ������
        $sql = (" SELECT *
                  FROM `service`
                  $query_part
                ");
        // 
        $result = $this->getAdapter()->query($sql);
        return $result;
    }

    // ����� ��� ���������� ����� ������
    public function addWarehouse($serial, $name, $type, $remain, $lastadding) {
        // ��������� ������ ����������� ��������
        $data = array(
                'serial' => $serial,
                'name' => $name,
                'type' => $type,
                'remain' => $remain,
                'lastadding' => $lastadding
        );

// ���������� ����� insert ��� ������� ������ � ����
        $this->insert($data);
    }
    
    public function editWarehouse($id, $serial, $name, $type, $remain, $lastadding) {
    // ��������� ������ ����������� ��������
        $data = array(
                'serial' => $serial,
                'name' => $name,
                'type' => $type,
                'remain' => $remain,
                'lastadding' => $lastadding
        );

    // ���������� ����� insert ��� ������� ������ � ����
        $this->update($data, 'id=' . (int) $id);
    }

    public function getWarehouse($id) {
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
    
        public function deleteWarehouse($id) {
        $this->delete('id=' . (int) $id);
    }

}

