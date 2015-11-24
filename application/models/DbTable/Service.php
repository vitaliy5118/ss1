<?php

class Application_Model_DbTable_Service extends Zend_Db_Table_Abstract {

    protected $_name = 'service';

    //����� ������� ��������
    public function showService($date) {

        //���������� ������
        $sql = (" SELECT *
                  FROM `service` 
                  WHERE `date` LIKE '%$date%'
                  ORDER BY date DESC
                ");

        return $this->getAdapter()->query($sql)->fetchAll();
    }

    // ����� ��� ���������� ����� ������
    public function addService($client, $number, $claim, $diagnos, $spares, $work, $status, $name, $comments, $counter, $serialize_price, $serialize_data) {
        // ��������� ������ ����������� ��������
        $data = array(
            'client' => $client,
            'number' => $number,
            'claim' => $claim,
            'diagnos' => $diagnos,
            'spares' => $spares,
            'work' => $work,
            'status' => $status,
            'name' => $name,
            'comments' => $comments,
            'counter' => $counter,
            'serialize_price' => $serialize_price,
            'serialize_data' => $serialize_data,
        );

        // ���������� ����� insert ��� ������� ������ � ����
        $this->insert($data);
    }
    // ����� ��� ���������� ����� ������
    public function editService($id, $client, $number, $claim, $diagnos, $spares, $work, $status, $name, $comments, $counter, $serialize_price, $serialize_data) {
        // ��������� ������ ����������� ��������
        $data = array(
            'client' => $client,
            'number' => $number,
            'claim' => $claim,
            'diagnos' => $diagnos,
            'spares' => $spares,
            'work' => $work,
            'status' => $status,
            'name' => $name,
            'comments' => $comments,
            'counter' => $counter,
            'serialize_price' => $serialize_price,
            'serialize_data' => $serialize_data,
        );

    // ���������� ����� insert ��� ������� ������ � ����
        $this->update($data, 'id=' . (int) $id);
    }

    public function getService($id) {
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

    public function searchService($search) {
        // ��������� ������ ����������� ��������
        $select = $this->select()->where('claim LIKE ?', "%$search%")
                ->orwhere('name LIKE ?', "%$search%")
                ->orwhere('number LIKE ?', "%$search%")
                ->orwhere('status LIKE ?', "%$search%")
                ->orwhere('client LIKE ?', "%$search%")
                ->orwhere('diagnos LIKE ?', "%$search%")
                ->orwhere('work LIKE ?', "%$search%")
                ->orwhere('spares LIKE ?', "%$search%")
                ->orwhere('comments LIKE ?', "%$search%")
                ->orwhere('counter LIKE ?', "%$search%");

        // ���������� select
        return $this->fetchAll($select);
    }

    public function getcountService($date = null) {

        //���������� ������
        $sql = (" SELECT count(id) as count
                  FROM `service`
                  WHERE `date` LIKE '%$date%'
                ");
        // 

        return $this->getAdapter()->query($sql)->fetchAll();
    }
    public function getcountsearchService($search) {

        //���������� ������
        $sql = (" SELECT count(id) as count
                  FROM `service`
                  WHERE `name` LIKE '%$search%'
                     OR `number` LIKE '%$search%'
                     OR `status` LIKE '%$search%'
                     OR `client` LIKE '%$search%'
                     OR `diagnos` LIKE '%$search%'
                     OR `work` LIKE '%$search%'
                     OR `spares` LIKE '%$search%'
                     OR `comments` LIKE '%$search%'
                     OR `counter` LIKE '%$search%'
                ");
        // 

        return $this->getAdapter()->query($sql)->fetchAll();
    }

}
