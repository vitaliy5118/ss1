<?php
class Application_Plugin_AccessCheck extends Zend_Controller_Plugin_Abstract
{
    public function preDispatch(Zend_Controller_Request_Abstract $request) {

        // получаем имя текущего ресурса
        $resource = $request->getControllerName();
        
        // получаем имя action
        $action = $request->getActionName();
        
        $auth = Zend_Auth::getInstance();
        
        $username = Zend_Auth::getInstance()->getIdentity()->username;
        // Создаём объект Zend_Acl
        $acl = new Zend_Acl();
        // Добавляем ресурсы сайта
        $acl->addResource('index')
                ->addResource('access')
                ->addResource('catalog')
                ->addResource('history')
                ->addResource('warehouse')
                ->addResource('repairs')
                ->addResource('sales')
                ->addResource('service')
                ->addResource('statistic')
                ->addResource('documentation')
                ->addResource('reports')
                ->addResource('setup')
                ->addResource('error');

        // далее переходим к созданию ролей, которых у нас 3:
        $acl->addRole('guest');  // гость (неавторизированный пользователь)
        $acl->addRole('client'); // гость (авторизированный пользователь)
        $acl->addRole('admin');  // гость (авторизированный пользователь)

        // разрешаем гостю просматривать ресурс index
        $acl->allow('guest', 'access', array('login'))
            ->allow('guest', 'error');

        $allow = new Application_Model_DbTable_Allow();
        $allow_data = $allow->fetchAll($allow->select()->where("username = '$username'"))->toArray();
        
        $allow_array = array(
            'documentation' => array ($allow_data[0]['doc_index']
                                     ,$allow_data[0]['doc_file']
                                     ,$allow_data[0]['doc_delete'])
           ,'catalog'       => array ($allow_data[0]['cat_index']
                                     ,$allow_data[0]['cat_add']
                                     ,$allow_data[0]['cat_edit']
                                     ,$allow_data[0]['cat_delete']
                                     ,$allow_data[0]['cat_exl'])
           ,'history'       => array ($allow_data[0]['his_index'])
           ,'reports'       => array ($allow_data[0]['rep_index'])
           ,'statistic'     => array ($allow_data[0]['stat_index'])
           ,'sales'         => array ($allow_data[0]['sal_index']
                                     ,$allow_data[0]['sal_add']
                                     ,$allow_data[0]['sal_edit']
                                     ,$allow_data[0]['sal_delete']
                                     ,$allow_data[0]['sal_toexcel'])
           ,'repairs'       => array ($allow_data[0]['rps_index']
                                     ,$allow_data[0]['rps_add']
                                     ,$allow_data[0]['rps_edit']
                                     ,$allow_data[0]['rps_delete']
                                     ,$allow_data[0]['rps_toexcel']
                                     ,$allow_data[0]['rps_toexcelmounth']
                                     ,$allow_data[0]['rps_statistic'])
           ,'warehouse'     => array ($allow_data[0]['war_index']
                                     ,$allow_data[0]['war_add']
                                     ,$allow_data[0]['war_edit']
                                     ,$allow_data[0]['war_delete']
                                     ,$allow_data[0]['war_toexcel']
                                     ,$allow_data[0]['war_history']
                                     ,$allow_data[0]['war_load']
                                     ,$allow_data[0]['war_unload'])
           ,'service'       => array ($allow_data[0]['ser_index']
                                     ,$allow_data[0]['ser_add']
                                     ,$allow_data[0]['ser_edit']
                                     ,$allow_data[0]['ser_delete']
                                     ,$allow_data[0]['ser_toexcel']
                                     ,$allow_data[0]['ser_invoice'])
           ,'setup'         => array ($allow_data[0]['set_index']
                                     ,$allow_data[0]['set_names']
                                     ,$allow_data[0]['set_addname']
                                     ,$allow_data[0]['set_editname']
                                     ,$allow_data[0]['set_deletename']
                                     ,$allow_data[0]['set_types']
                                     ,$allow_data[0]['set_addtype']
                                     ,$allow_data[0]['set_edittype']
                                     ,$allow_data[0]['set_deletetype']
                                     ,$allow_data[0]['set_owners']
                                     ,$allow_data[0]['set_addowner']
                                     ,$allow_data[0]['set_editowner']
                                     ,$allow_data[0]['set_deleteowner']
                                     ,$allow_data[0]['set_users']
                                     ,$allow_data[0]['set_adduser']
                                     ,$allow_data[0]['set_edituser']
                                     ,$allow_data[0]['set_deleteuser']
                                     ,$allow_data[0]['set_status']
                                     ,$allow_data[0]['set_addstatus']
                                     ,$allow_data[0]['set_editstatus']
                                     ,$allow_data[0]['set_deletestatus']
                                     ,$allow_data[0]['set_prices']
                                     ,$allow_data[0]['set_addprices']
                                     ,$allow_data[0]['set_editprices']
                                     ,$allow_data[0]['set_deleteprices']
                                     ,$allow_data[0]['set_access']
                                     ,$allow_data[0]['set_addaccess']
                                     ,$allow_data[0]['set_editaccess']
                                     ,$allow_data[0]['set_deleteaccess'])

                                     
        );
        
        $acl->allow('client', 'error')
            ->allow('client', 'access', array('logout'))
            ->allow('client', 'index',  array('index'))
            ->allow('client', 'history',        $allow_array['history'])
            ->allow('client', 'documentation',  $allow_array['documentation'])
            ->allow('client', 'reports',        $allow_array['reports'])
            ->allow('client', 'statistic',      $allow_array['statistic'])
            ->allow('client', 'catalog',        $allow_array['catalog'])
            ->allow('client', 'sales',          $allow_array['sales'])
            ->allow('client', 'repairs',        $allow_array['repairs'])
            ->allow('client', 'warehouse',      $allow_array['warehouse'])
            ->allow('client', 'service',        $allow_array['service'])
            ->allow('client', 'setup',          $allow_array['setup']);
                
                
        $acl->allow('admin', 'error')
            ->allow('admin', 'access',    array('logout'))
            ->allow('admin', 'index',     array('index'))
            ->allow('admin', 'history',   array('index'))
            ->allow('admin', 'documentation', array('index','file','delete'))
            ->allow('admin', 'reports',   array('index'))
            ->allow('admin', 'statistic', array('index'))
            ->allow('admin', 'catalog',   array('index','toexcel','add', 'edit', 'delete'))
            ->allow('admin', 'sales',     array('index','toexcel','add', 'edit', 'delete'))
            ->allow('admin', 'service',   array('index','toexcel', 'invoice','add', 'edit', 'delete'))
            ->allow('admin', 'repairs',   array('index','statistic','toexcel','toexcelmonth','add', 'edit', 'delete'))
            ->allow('admin', 'warehouse', array('index','add', 'edit', 'delete','unload','load','history','toexcel'))
            ->allow('admin', 'setup',     array('index','names', 'addname', 'editname','deletename'
                                               ,'types', 'addtype', 'edittype','deletetype'
                                               ,'owners','addowner','editowner','deleteowner'
                                               ,'users','adduser','edituser','deleteuser'
                                               ,'status','addstatus','editstatus','deletestatus'
                                               ,'access', 'addaccess', 'editaccess','deleteaccess'
                                               ,'prices', 'addprices', 'editprices','deleteprices'));
    
        
                // получаем доступ к хранилищу данных Zend,
        // и достаём роль пользователя
        $identity = $auth->getStorage()->read();
        
        // если в хранилище ничего нет, то значит мы имеем дело с гостем
        if(empty($identity->role)){
            $identity->role = 'guest';
        }
        
        // если пользователь не допущен до данного ресурса или не зарегистрирован
        // то отсылаем его на страницу авторизации 
        if (Zend_Auth::getInstance()->hasIdentity()) {
            if (!$acl->isAllowed($identity->role, $resource, $action)) {
                $request->setControllerName('error')->setActionName('noaccess');
            }
        } else {
           $request->setControllerName('access')->setActionName('login'); 
        }
    }
    
    
    
    
}
