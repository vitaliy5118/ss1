<?php

class Application_Model_DbTable_Access extends Zend_Db_Table_Abstract {

    protected $_name = 'access';

    public function autorize($username, $password) {

        $auth = Zend_Auth::getInstance();
        $authAdapter = new Zend_Auth_Adapter_DbTable(
                Zend_Db_Table::getDefaultAdapter(), 'access'
                , 'username'
                , 'password'
                //,'sha(?)'
        );
        $authAdapter->setIdentity($username)->setCredential($password);
        $result = $auth->authenticate($authAdapter);
        if ($result->isValid()) {
            $storage = $auth->getStorage();
            $storage->write($authAdapter->getResultRowObject(null, array('password')));
            return true;
        } else {
            return false;
        }
    }

    public function addaccess($username, $password, $role) {

        $data_array = array(
              'username' => $username
            , 'password' => $password
            , 'role' => $role
        );
 
        $this->insert($data_array);
    }
    
    public function editaccess($id, $username, $password, $role) {

        // ‘ормируем массив вставл€емых значений
        $data_array = array(
              'username' => $username
            , 'password' => $password
            , 'role' => $role
        );
        // »спользуем метод insert дл€ вставки записи в базу
        $this->update($data_array, 'id=' .(int) $id);
    }
    
   public function deleteaccess($id) {
        //удал€ем данные с таблицы
        $this->delete('id=' . (int) $id);
     }
     
   public function getaccess($id) {
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

}

