<?php

class XpriceController extends Zend_Controller_Action {

//    public $dsn="DRIVER=Client Acess ODBC Driver(32-bit);UID=EU65535;PWD=CCS65535;SYSTEM=10.105.80.32;DBQ=CVXCDTA";
    public $dsn = "DRIVER={MySQL};Server=127.0.0.1;Database=CVXCDTA;UID=EU65535;PWD=CCS65535;";
    public $odbc_conn = null;
    protected $_auth = null;

    public function init() {
        $this->odbc_conn = odbc_connect($this->dsn, "", "");
        if (!$this->odbc_conn) {
            echo "pas d'accès à la base de données";
        }
        $this->_auth = Zend_Auth::getInstance();
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
                    $redirector = $this->_helper->getHelper('Redirector');
                    $redirector->gotoSimple('create', 'xprice', null, array('numwp' => $_POST['num_offre_worplace']));
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
        $numwp = $this->getRequest()->getParam('numwp', null);
        $this->view->numwp = $numwp;
        if (!is_null($numwp)) {
            //si le numero workplace est valide alors on fait la requête pour movex
            // requête d'informations de l'offre
            $query = "select "
                    . "OOLINE.OBORNO AS numwp, "
                    . "OOLINE.OBRGDT AS date_offre "
                    . "from OOLINE where OOLINE.OBORNO='{$numwp}' "
                    . "group by OOLINE.OBORNO;";
            $this->view->infos_offre = odbc_fetch_object(odbc_exec($this->odbc_conn, $query));
            $user = $this->_auth->getStorage()->read();
            $zoneT = new Application_Model_DbTable_Zones();
            $zone = $zoneT->fetchRow(array('id_zone' => $user->id_zone));
            $Xprices = new Application_Model_DbTable_Xprices();
//            $this->view->trackingNumber = Application_Model_DbTable_Xprices::makeTrackingNumber($zone->nom_zone, 1);
            $this->view->trackingNumber = Application_Model_DbTable_Xprices::makeTrackingNumber($zone->nom_zone, $Xprices->lastId(true));
            // mag ici :
// requetes pour remplir le phtml :
            //requete 1 pour remplir  les données du commercial
            $query1 = "SELECT OOLINE.OBSMCD  as userwp "
                    . "FROM OOLINE "
                    . "WHERE OOLINE.OBORNO='{$numwp}' "
                    . "GROUP BY OOLINE.OBORNO;";
                    $numwp_user= odbc_fetch_array(odbc_exec($this->odbc_conn,$query1));
                    echo var_dump($numwp_user);
                    $usertest = new Application_Model_DbTable_Users();
                    $user_info = $usertest->getMovexUser($numwp_user['userwp']);
                    echo '<pre>',  var_export($user_info, false),'</pre>';
                    $this->view->user_info=$user_info;
//                    $nom_user1 = $user_info['nom_user'];
//                    $prenom_user1 = $user_info['prenom_user'];
//                    $tel_user1 = $user_info['tel_user'];
//                    $email_user1 = $user_info['email_user'];
                    $id_holon = $user_info['id_holon'];
//                    $id_user1 = $user_info['id_user'];
                    $holonuser = new Application_Model_DbTable_Holons();
                    $holonuser1 = $holonuser->getHolon($id_holon);
                    $nom_holon = $holonuser1['nom_holon'];
                    $this->view->holon=$nom_holon;
                    
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
             * ccccccccccccccc,
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
            $query2="select "
                ."OOLINE.OBORNO,"
                ."OOLINE.OBCUNO,"
                ."OOLINE.OBITNO,"
                ."OOLINE.OBITDS,"
                ."OOLINE.OBORQT,"
                ."OOLINE.OBLNA2,"
                ."OOLINE.OBNEPR,"
                ."OOLINE.OBSAPR,"
                ."OOLINE.OBELNO,"
                ."OOLINE.OBRGDT,"
                ."OOLINE.OBLMDT,"
                ."OOLINE.OBSMCD "
                ."from OOLINE WHERE OOLINE.OBORNO='{$numwp}' AND OOLINE.OBDIVI LIKE 'FR0' AND OOLINE.OBCONO=100";
              //echo var_dump( $query2);
                $resultats=odbc_exec($this->odbc_conn, $query2) ;
               
              // foreach($resultats as $result){
              
        while ( $resultat[]=odbc_fetch_array($resultats)){
            //echo '<pre>', var_export($resultat,false),'</pre>';
        $this->view->resultat=$resultat;
        }
         
        
        }

        // franck là :
        $form = new Application_Form_CreationDemande();
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
        $this->view->form = $form;
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

