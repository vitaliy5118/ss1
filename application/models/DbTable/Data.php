<?php

class Application_Model_DbTable_Data extends Zend_Db_Table_Abstract {

    protected $_name = 'data';
    
    public function addData($description, $filename) {
        

        // ��������� ������ ����������� ��������
        $data = array(
            'description' => $description,
            'path' => $filename
         );
        // ���������� ����� insert ��� ������� ������ � ����
        $this->insert($data);
    }
    
        
    public function getData($id) {
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
 
    public function deleteData($id) {
        //������� ������ � �������
        $this->delete('id=' . (int) $id);
     }
   
}

