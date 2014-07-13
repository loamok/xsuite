<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    protected function _initDoctype() {
        $this->_executeResource('view');
        $view = $this->getResource('view');
        $view->doctype('XHTML1_STRICT');
        
    }
}

