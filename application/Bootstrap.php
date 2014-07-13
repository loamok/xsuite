<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    protected function _initDoctype() {
        $this->_executeResource('view');
        $view = $this->getResource('view');
        $view->doctype('XHTML1_STRICT');
        
    }
    
    protected function _initAcl() {
//        $front =  $this->getResource('FrontController');
        $front = Zend_Controller_Front::getInstance();
        $acl = new Xsuite_Controller_Plugin_AuthAcl(APPLICATION_PATH.'/configs/autorisation.ini');
        Zend_Registry::set('AuthAcl', $acl);
        $plugin = new Xsuite_Controller_Plugin_Auth($acl);
        $front->registerPlugin($plugin);
        $sAuth = new Zend_Session_Namespace('sAuth');
        $sAuth->setExpirationSeconds(3600);
    }
    
}

