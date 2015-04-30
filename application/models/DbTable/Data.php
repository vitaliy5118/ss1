<?php

class Application_Model_DbTable_Data extends Zend_Db_Table_Abstract {

    protected $_name = 'data';
    
    public function addData($description, $filename) {
        

        // ‘ормируем массив вставл€емых значений
        $data = array(
            'description' => $description,
            'path' => $filename
         );
        // »спользуем метод insert дл€ вставки записи в базу
        $this->insert($data);
    }
    
        
    public function getData($id) {
        //принимаем id
        $id = (int) $id;
        //читаем данные с таблицы
        $row = $this->fetchRow('id=' . $id);
        if (!row) {
            throw new Exeption("Ќет записи с по номеру - $id");
        }
        //возвращаем результат в виде массива
        return $row->toArray();
    }
 
    public function deleteData($id) {
        //удал€ем данные с таблицы
        $this->delete('id=' . (int) $id);
     }
   
}

