<?php
class Application_Plugin_AccessCheck extends Zend_Controller_Plugin_Abstract
{
    public function preDispatch(Zend_Controller_Request_Abstract $request) {

        // �������� ��� �������� �������
        $resource = $request->getControllerName();
        
        // �������� ��� action
        $action = $request->getActionName();
        
        $auth = Zend_Auth::getInstance();
        // ������ ������ Zend_Acl
        $acl = new Zend_Acl();
        // ��������� ������� �����
        $acl->addResource('index')
                ->addResource('access')
                ->addResource('catalog')
                ->addResource('warehouse')
                ->addResource('repairs')
                ->addResource('sales')
                ->addResource('statistic')
                ->addResource('documentation')
                ->addResource('setup')
                ->addResource('error');

        // ����� ��������� � �������� �����, ������� � ��� 3:
        $acl->addRole('guest') // ����� (������������������ ������������)
                ->addRole('client', 'guest') // ������, ������� ��������� ������ �� �����
                ->addRole('admin', 'client'); // ������, ������� ��������� ������ �� �����

       // $acl->deny();
        // ��������� ����� ������������� ������ index
        $acl->allow('guest', 'access', array('login'))
                ->allow('guest', 'error')
                ->allow('client', 'access', array('logout'))
                ->allow('client', 'index', array('index'))
                ->allow('client', 'catalog', array('index'))
                ->allow('client', 'repairs', array('index','statistic'))
                ->allow('client', 'warehouse', array('index'))
                ->allow('client', 'documentation', array('index','file','delete'))
                ->allow('client', 'statistic', array('index'))
                ->allow('client', 'sales', array('index'))
                ->allow('client', 'setup', array('index'))
                ->allow('admin', 'catalog', array('add', 'edit', 'delete'))
                ->allow('admin', 'sales', array('add', 'edit', 'delete'))
                ->allow('admin', 'repairs', array('add', 'edit', 'delete'))
                ->allow('admin', 'warehouse', array('add', 'edit', 'delete'))
                ->allow('admin', 'setup', array('names', 'addname', 'editname','deletename'
                                               ,'owners','addowner','editowner','deleteowner'
                                               ,'users','adduser','edituser','deleteuser'
                                               ,'status','addstatus','editstatus','deletestatus'));
    
        
                // �������� ������ � ��������� ������ Zend,
        // � ������ ���� ������������
        $identity = $auth->getStorage()->read();
        
        // ���� � ��������� ������ ���, �� ������ �� ����� ���� � ������
        if(empty($identity->role)){
            $identity->role = 'guest';
        }
        
        // ���� ������������ �� ������� �� ������� ������� ��� �� ���������������
        // �� �������� ��� �� �������� ����������� 
        if (Zend_Auth::getInstance()->hasIdentity()) {
            if (!$acl->isAllowed($identity->role, $resource, $action)) {
                $request->setControllerName('error')->setActionName('noaccess');
            }
        } else {
           $request->setControllerName('access')->setActionName('login'); 
        }
    }
    
    
    
    
}
