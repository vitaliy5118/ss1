<?php

class Application_Model_DbTable_Repairs extends Zend_Db_Table_Abstract {

    protected $_name = 'repairs';

    // ����� ��� ������ ��������
    public function searchRepairs($search, $id) {
        // ��������� ������ ����������� ��������
        $select = $this->select($id)->where('data = ?', $search)
                ->orwhere('claim = ?', $search)
                ->orwhere('diagnos = ?', $search)
                ->orwhere('work = ?', $search)
                ->orwhere('spares = ?', $search)
                ->orwhere('comments = ?', $search);
        // ���������� select
        return $select;
    }
    
    //����� ������� ��������
    public function statisticRepairs($date) {

        //���������� ������
        $sql = (" SELECT *
                  FROM `repairs`
                  WHERE `date` LIKE '%$date%'
                      
                ");

        return $this->getAdapter()->query($sql)->fetchAll();
    }
    //����� ������� ��������
    public function getcountRepairs($date = null) {

        //���������� ������
        $sql = (" SELECT count(id) as count
                  FROM `repairs`
                  WHERE `date` LIKE '%$date%'
                ");
        // 

        return $this->getAdapter()->query($sql)->fetchAll();
    }
    //����� ������� ��������
    public function getCountRepairsbyName($name) {

        //���������� ������
        $sql = (" SELECT count(repairs.id) as count
                  FROM `repairs` JOIN `devices` ON repairs.number=devices.number 
                  WHERE `name` LIKE '%$name%'
                ");

        return $this->getAdapter()->query($sql)->fetchAll();
    }
    
    // ����� ��� ���������� ����� ������
    public function addRepaire($number, $claim, $diagnos, $spares, $work, $comments) {
        // ��������� ������ ����������� ��������
        $data = array(
            'number' => $number,
            'claim' => $claim,
            'diagnos' => $diagnos,
            'work' => $work,
            'spares' => $spares,
            'comments' => $comments
        );

// ���������� ����� insert ��� ������� ������ � ����
        $this->insert($data);
    }
    
    public function editRepaire($id, $number, $data, $claim, $diagnos, $spares, $work, $comments) {
    // ��������� ������ ����������� ��������
        $data = array(
            'data' => $data,
            'claim' => $claim,
            'diagnos' => $diagnos,
            'work' => $work,
            'spares' => $spares,
            'comments' => $comments,
            'number' => $number
        );

    // ���������� ����� insert ��� ������� ������ � ����
        $this->update($data, 'id=' . (int) $id);
    }

    public function getRepaire($id) {
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
    
        public function deleteRepaire($id) {
        $this->delete('id=' . (int) $id);
    }

}

