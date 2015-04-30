<?php

class Application_Model_DbTable_Setup extends Zend_Db_Table_Abstract
{
    protected $table;

    protected function _setupTableName(){
        $this->_name = $this->table;
        parent::_setupTableName();
    }
    
    public function setTableName($name){
        $this->table = $name;
        $this->_setupTableName();
    }

    
    public function getValues() {
        
        foreach ($this->fetchAll($this->select()->from("$this->table",array("$this->table")))
                ->toArray() as $key => $value) {

            foreach ($value as $ss => $sa) {
                $data_array[$sa]=$sa;
            }    
        }
        return $data_array;
    }
    
    public function addSetup($data) {
            $data = (string) $data;
        $array = array(
          "$this->_name" => $data
        );
        
        $this->insert($array);
    }
    
    public function editSetup($id, $data) {

        $array = array(
          "$this->_name" => $data  
        );
        $this->update($array, 'id=' . (int) $id);
        
    }
    
    public function deleteSetup($id) {

        $this->delete('id=' . (int) $id);
        
    }
    
    public function getSetup($id) {
        $id = (int) $id;
        $row = $this->fetchRow('id = ' . $id);
        if (!$row) {
            throw new Exception("Нет записи с id - $id");
        }
        return $row->toArray();
    }



}

