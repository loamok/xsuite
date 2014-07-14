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
//    public $dsn="DRIVER=Client Acess ODBC Driver(32-bit);UID=EU65535;PWD=CCS65535;SYSTEM=10.105.80.32;DBQ=CVXCDTA";
    public $dsn = "DRIVER={MySQL};Server=127.0.0.1;Database=CVXCDTA";
    public $odbc_conn = null;

    public function init() {
        $this->odbc_conn = odbc_connect($this->dsn, "root", "geek");
        if (!$this->odbc_conn) {
            echo "pas d'accès à la base de données";
        }
        $this->view->messages = $this->_helper->flashMessenger->getMessages();
    }

    public function indexAction() {
        // action body
    }

    public function numwpAction() {

        $form = new Application_Form_Numwp();
        if ($this->getRequest()->isPost()) {

            if ($form->isValid($this->getRequest()->getPost())) {
                $query = "select COUNT(OOLINE.OBORNO) as nbNumwp FROM OOLINE WHERE OOLINE.OBORNO = '{$_POST['num_offre_worplace']}';";
                $results = odbc_exec($this->odbc_conn, $query);
                $r = odbc_fetch_object($results);
                if ($r->nbNumwp > 1) {
                    $this->_helper->redirector->gotoSimple('create', 'xprice', null, array('numwp' => $_POST['num_offre_worplace']));
                } else {
                    $flashMessenger = $this->_helper->getHelper('FlashMessenger');
                    $message = "ce numéro d'offre n'a pas de concordance dans la base MOVEX";
                    $flashMessenger->addMessage($message);
                    $form->populate($this->getRequest()->getPost());
                }
                /* on vérifie que le numéro existe bien dans la base movex
                 * $dsn="DRIVER=Client Acess ODBC Driver(32-bit);UID=EU65535;PWD=CCS65535;SYSTEM=10.105.80.32;DBQ=CVXCDTA";
                 * $mmcono = "100";
                 * $division = "FR0;
                 * $facility = "I01";
                 * $type = "3";
                 * $warehouse = "I02";
                 * $supplier = "I990001";
                 * $agreement1 = "I000001";
                 * $agreement2 = "I000002";
                 * $agreement3 = "I000003";
                 *
                 * $conn = odbc_connect($dsn,"","");
                 * if(!$conn2){
                 * echo "pas d'accès à la base de données";
                 * }
                 * $query = "select COUNT(*) FROM EIT.CVXCDTA.OOLINE OOLINE WHERE OOLINE.OBORNO ='$_POST['num_offre_workplace']' AND OOLINE.OBDIVI='FR0' and OOLINE.OBCONO='100'";
                 * $results=odbc_Exec($conn,$query);
                 * if($results ==null){$this->_helper->redirector->gotoSimple('create', 'xprice', null, array('numwp' => $_POST['num_offre_worplace']));}
                 * else{
                 * $flashMessenger = $this->_helper->Flashmessenger;
                 * $message = "ce numéro d'offre n'a pas de concordance dans la base MOVEX";
                 * $flashMessenger->addMessage($message);
                 * $form->populate($this->getRequest()->getPost());}
                 */
            } else {
                $form->populate($this->getRequest()->getPost());
            }
        }

        //$form->submit->setLabel('Entrer');
        $this->view->form = $form;
    }

    public function createAction() {

        $form = new Application_Form_CreationXprice();
        // $form->submit->setLabel('Creer');
        $this->view->form = $form;

        $numwp = $this->getRequest()->getParam('numwp', null);
        $this->view->numwp = $numwp;
        if (!is_null($numwp)) {
            //si le numero workplace est valide alors on fait la requête pour movex
            /*
             * $dsn2="DRIVER=Client Acess ODBC Driver(32-bit);UID=EU65535;PWD=CCS65535;SYSTEM=10.105.80.32;DBQ=CVXCDTA";
             * $mmcono = "100";
             * $division = "FR0;
             * $facility = "I01";
             * $type = "3";
             * $warehouse = "I02";
             * $supplier = "I990001";
             * $agreement1 = "I000001";
             * $agreement2 = "I000002";
             * $agreement3 = "I000003";
             *
             * $conn2 = odbc_connect($dsn2,"","");
             * if(!$conn2){
             * echo "pas d'accès à la base de données";
             * }
             * $query = "SELECT
             * OOLINE.OBORNO,
             * OOLINE.OBCUNO,
             * OOLINE.OBITNO,
             * OOLINE.OBITDS,
             * OOLINE.OBORQT,
             * OOLINE.OBLNA2,
             * OOLINE.OBLNAM,
             * OOLINE.OBNEPR,
             * OOLINE.OBSAPR,
             * OOLINE.OBELNO,
             * OOLINE.OBRGTD,
             * OOLINE.OBLMDT,
             * OOLINE.OBSMCD
             * FROM EIT.CVXCDTA.OOLINE OOLINE WHERE OOLINE.OBORNO ='$numwp' AND OOLINE.OBDIVI='FR0' and OOLINE.OBCONO='100'";
             * $resultats=odbc_Exec($conn2,$query);
             * while($resultat=odbc_fetch_object($resultats){
             *
             * }
             */
        }

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

