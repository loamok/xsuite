<?php

class LoginController extends Zend_Controller_Action {

    public function aclfailAction() {
        
    }
    
    public function indexAction() {
        $loginForm = new Application_Form_Login();
        $request = $this->getRequest();

        if ($request->isPost()) {
            if ($loginForm->isValid($request->getPost())) {
                if ($this->_process($loginForm->getValues())) {
                    $this->_helper->redirector('index', 'index');
                } // else message d'erreur de login mot de passe
            }
        }

        $this->view->form = $loginForm;
    }
     

    protected function _process($values) {
        $adapter = $this->_getAuthAdapter();
        $adapter->setIdentity($values['nom_user']);
        $adapter->setCredential($values['password_user']);

        $auth = Zend_Auth::getInstance();
        $result = $auth->authenticate($adapter);
        if ($result->isValid()) {
            $user = $adapter->getResultRowObject();
            $auth->getStorage()->write($user);
            return true;
        }
        return false;
    }

    protected function _getAuthAdapter() {

        $dbAdapter = Zend_Db_Table::getDefaultAdapter();
        $authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);

        $authAdapter->setTableName('users')
                ->setIdentityColumn('nom_user')
                ->setCredentialColumn('password_user');

        return $authAdapter;
    }

   
public function logoutAction()
    {
        Zend_Auth::getInstance()->clearIdentity();
        $this->_helper->redirector('index'); // back to login page
    }
}

