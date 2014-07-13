<?php

class XpriceController extends Zend_Controller_Action {

//    public function init() {
//        /* Initialize action controller here */
//        /* @todo commentaire franck
//         * Exemple d'authentification forcée pour toutes les actions de ce controlleur
//         */
//        $auth = Zend_Auth::getInstance();
//        $user = $auth->getIdentity();
//        if (is_null($user)) {
//            $this->_helper->redirector('index', 'login');
//        } else {
//            /* @todo commentaire franck
//             * Et donc, ici, on peut faire de l'acl de manière plus fine
//             */
//        }
//    }

    public function indexAction() {
        // action body
    }

    public function numwpAction() {

        $form = new Application_Form_Numwp();
        //$form->submit->setLabel('Entrer');
        $this->view->form = $form;
    }

    public function createAction() {
        if (preg_match("#^00[6][0][9][0-9]#", $_POST["num_offre_worplace"])) {
            $form = new Application_Form_CreationXprice();
            // $form->submit->setLabel('Creer');
            $this->view->form = $form;

            if ($this->getRequest()->isPost()) {
                $formData = $this->getRequest()->getPost();
                if ($form->isValid($formData)) {
                    //alors on insert dans la base mysql
                    //dans un premier temps
                    //la requete suivante : INSERT INTO `demande_xprices`
                    //(`id_demande_xprice`, `num_workplace_demande_xprice`, `tracking_number_demande_xprice`,
                    // `commentaire_demande_xprice`, `date_demande_xprice`,
                    //  `id_demande_article`, `id_user`, `id_client`, `id_validation`)
                    // VALUES ('',value2,value3,value4,value5,value6,value7,value8,value9)
                    //$demandes_xprice = new Application_Model_DbTable_Xprices();
                    //$demande_xprice = $demandes_xprice ->createXprice(
                    //$formData['num_offre_worplace'],
                    //$formData['tracking_number'],
                    //$formData['commentaire'],
                    //$formData['date_demande_xprice'],
                    //null,
                    //$formData['id_user'],
                    //$formData['id_client'],
                    //null);
                    // et ensuite on envoi un mail à fobfr avec un lien qyui renvoi vers l'action prixfobfr avec le tracking number pour qu'il mette les prix fob et les prix cif ,
                    // et un mail au chef de vente  avec un lien vers l'action validation chef de vente avec le tracking number .
                } else {
                    $form->populate($formData);
                }
            }
        } else {
            $this->_helper->redirector('numwp');
        }
    }

    public function prixfobfrAction() {
        // action body
    }

    public function deleteAction() {
        // action body
    }

    public function validateAction() {
        // action body
    }

    public function updateAction() {
        // action body
    }

    public function listAction() {
        // action body
    }

    public function confirmcreateAction() {
        // au moment de la confirmation d'insertion dans la base de la demande Xprice
        //  envoyer un mail à chef de vente pour validation
        //  et à prixfob pour qu'il remplisse  les prix
        $form = new Application_Form_Confirmcreate();
        // $form->submit->setLabel('Creer');
        $this->view->form = $form;
    }

    public function confirmvalidAction() {
        // action body
    }

    public function confirmupdateAction() {
        // action body
    }

}

