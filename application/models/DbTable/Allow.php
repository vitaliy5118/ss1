<?php

class Application_Model_DbTable_Allow extends Zend_Db_Table_Abstract {

    protected $_name = 'allow';

    public function addallow($check_data, $username) {

        $data_array['username']=$username;
        
        if ($check_data) {
            foreach ($check_data as $data => $value) {

               $data_array[$data]=$value;
            }
        }

        $this->insert($data_array);
    }
    
    public function editallow($check_data, $username) {
        
        $data_array['username']=$username;
        
        if ($check_data) {
            foreach ($check_data as $data => $value) {

               $data_array[$data]=$value;
            }
        }

        $this->delete("username = '$username'");
        $this->insert($data_array);
    }
}
