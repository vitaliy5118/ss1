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
        $sql = (" SELECT repairs.*, devices.name
                  FROM `repairs` JOIN `devices` ON repairs.number=devices.number
                  WHERE `date` LIKE '%$date%'
                  ORDER BY date DESC
                      
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

    public function getCountRepairsbyNumber($number) {

        //���������� ������
        $sql = (" SELECT count(repairs.id) as count
                  FROM `repairs` 
                  WHERE `number` = '$number'
                ");

        return $this->getAdapter()->query($sql)->fetchAll();
    }

    // ����� ��� ���������� ����� ������
    public function addRepaire(Application_Model_Repaire $repaire) {
        // ���������� ����� insert ��� ������� ������ � ����
        $this->insert($repaire->makearray());
    }

    public function editRepaire(Application_Model_Repaire $repaire) {
        // ����������� ������
        $this->update($repaire->makearray(), 'id=' . (int) $repaire->id);
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
    
        //���������� ���������� ��������� �� �����
    public function getChart() {

        //��������� ������
        $sql = (" SELECT date, count(id) as count
                  FROM `repairs` 
                  GROUP BY MONTH(date)
                ");

        return $this->getAdapter()->query($sql)->fetchAll();
    }

}
