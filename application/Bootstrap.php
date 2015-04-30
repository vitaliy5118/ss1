<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    protected function _initMyActionHelpers() {
        Zend_Controller_Action_HelperBroker::addPath(
                APPLICATION_PATH . '/controllers/helpers');
    }

    protected function _initPlugins() {
        Zend_Controller_Front::getInstance()
                ->registerPlugin(new Application_Plugin_AccessCheck());
    }
    
      protected function _initViewHelpers() {
        
       
        $this->bootstrap('layout');
        $layout = $this->getResource('layout');
        $view = $layout->getView();
        
        $view->headTitle('Jacobs Mondeles Ukraine');
        $view->headTitle()->setSeparator(' - ');
        
        if(Zend_Auth::getInstance()->hasIdentity()){
            Zend_Layout::getMvcInstance()->setLayout('master');
        } else {
            Zend_Layout::getMvcInstance()->setLayout('layout');
        }
    

    }

}
    