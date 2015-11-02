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

        // ��������� ������ ����������� ��������
        $data_array = array(
              'username' => $username
            , 'password' => $password
            , 'role' => $role
        );
        // ���������� ����� insert ��� ������� ������ � ����
        $this->update($data_array, 'id=' .(int) $id);
    }
    
   public function deleteaccess($id) {
        //������� ������ � �������
        $this->delete('id=' . (int) $id);
     }
     
   public function getaccess($id) {
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

}

