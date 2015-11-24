<?php

class Application_Model_DbTable_Warehouse extends Zend_Db_Table_Abstract {

    protected $_name = 'warehouse';
    
    // ����� ��� ������ ��������
    public function searchWarehouse($search) {
        // ��������� ������ ����������� ��������
        $select = $this->select()->where('serial LIKE ?', "%$search%")
                ->orwhere('name LIKE ?', "%$search%")
                ->orwhere('remain LIKE ?', "%$search%")
               // ->orwhere('lastadding = ?', $search)
                ->orwhere('type LIKE ?', "%$search%");
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
    public function addWarehouse($serial, $name, $type, $remain, $price, $path){
        // ��������� ������ ����������� ��������
        $data = array(
                'serial' => $serial,
                'name' => $name,
                'type' => $type,
                'remain' => $remain,
                'price' => $price,
                'path' => $path
        );

// ���������� ����� insert ��� ������� ������ � ����
        $this->insert($data);
    }
    
    public function editWarehouse($id, $serial, $name, $type, $remain, $price, $path) {

    // ��������� ������ ����������� ��������
        $data = array(
                'serial' => $serial,
                'name' => $name,
                'type' => $type,
                'remain' => $remain,
                'path' => $path
        );
        
        if ($price!='unload'){
            $data['price'] = $price;
            
        }
        
    // ���������� ����� insert ��� ������� ������ � ����
        $this->update($data, 'id=' . (int) $id);
                //���������� ������
        $sql = (" UPDATE warehouse
                  SET lastadding = CURRENT_TIMESTAMP()
                  WHERE id = $id
                ");

        $this->getAdapter()->query($sql);
    }
    
    public function loadWarehouse($id, $remain, $price) {
        
       //���������� ������
        $sql = (" UPDATE warehouse
                  SET lastadding = CURRENT_TIMESTAMP(), 
                      remain = $remain,
                      price = $price
                  WHERE id = $id
                ");

        $this->getAdapter()->query($sql);
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

