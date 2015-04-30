<?php

class Application_Model_DbTable_Access extends Zend_Db_Table_Abstract
{

    protected $_name = 'access';
    
    public function autorize($username, $password) {
        
        $auth = Zend_Auth::getInstance();
        $authAdapter = new Zend_Auth_Adapter_DbTable(
                Zend_Db_Table::getDefaultAdapter(),
                'access'
                ,'username'
                ,'password'
                //,'sha(?)'
        );
        $authAdapter->setIdentity($username)->setCredential($password);
        $result = $auth->authenticate($authAdapter);
        if($result->isValid()){
            $storage = $auth->getStorage();
            $storage->write($authAdapter->getResultRowObject(null, array('password')));
            return true;
        } else {
            return false;
        }

    }


}

