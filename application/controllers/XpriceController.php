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
                
            } else {
                $form->populate($this->getRequest()->getPost());
            }
        }
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
            $infos_offre= odbc_fetch_object(odbc_exec($this->odbc_conn, $query));
            echo '<pre>',  var_export($infos_offre->date_offre,false),'</pre>';
            $this->view->infos_offre=$infos_offre;
            $user = $this->_auth->getStorage()->read();
            $zoneT = new Application_Model_DbTable_Zones();
            $zone = $zoneT->fetchRow(array('id_zone' => $user->id_zone));
            $Xprices = new Application_Model_DbTable_Xprices();
            $trackingNumber = Application_Model_DbTable_Xprices::makeTrackingNumber($zone->nom_zone, $Xprices->lastId(true));
           // $this->view->trackingNumber = Application_Model_DbTable_Xprices::makeTrackingNumber($zone->nom_zone, $Xprices->lastId(true));
            $this->view->trackingNumber=$trackingNumber;
// requetes pour remplir le phtml :
            //requete 1 pour remplir  les données du commercial à partir du numwp
            $query1 = "SELECT OOLINE.OBSMCD  as userwp "
                    . "FROM OOLINE "
                    . "WHERE OOLINE.OBORNO='{$numwp}' "
                    . "GROUP BY OOLINE.OBORNO;";
                    $numwp_user= odbc_fetch_array(odbc_exec($this->odbc_conn,$query1));
                    $usertest = new Application_Model_DbTable_Users();
                    $user_info = $usertest->getMovexUser($numwp_user['userwp']);
                    $this->view->user_info=$user_info;
                    $id_holon = $user_info['id_holon'];
                    $holonuser = new Application_Model_DbTable_Holons();
                    $holonuser1 = $holonuser->getHolon($id_holon);
                    $nom_holon = $holonuser1['nom_holon'];
                    $this->view->holon=$nom_holon;
                    /*
                     * on va chercher les informations concernant les articles dans la table ooline à partir du numwp
                     * pour pouvoir ensuite les afficher dans la vue à l'aide d'un foreach
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
                $resultats=odbc_exec($this->odbc_conn, $query2) ;
              
        while ( $resultat[]=odbc_fetch_array($resultats)){
        $this->view->resultat=$resultat;
       
        }
        /*
         * à partir du code client de la table ooline on va chercher dans la table ocusma
         * les informations concernant le client pour pouvoir les afficher dans la vue phtml
         */
          $query1bis="select * "
                . "from OCUSMA "
                . "where OCUSMA.OKCUNO = '{$resultat[0]['OBCUNO']}'";
        $infos_client = odbc_fetch_array(odbc_exec($this->odbc_conn, $query1bis));
        $this->view->infos_client=$infos_client;
        /*
         * information concernant  le projet industry auquel appartient le client
         *    donc à partir du code movex industry on va chercher dans la base xsuite
         *  le nom de l'industrie auquel le client appartient pour ensuite l'afficher dans la vue 
         */
        $industry = new Application_Model_DbTable_Industry();
        $info_industry = $industry->getMovexIndustry($infos_client['OKCUCL']);
        $this->view->info_industry=$info_industry;
        }

        // franck là :
        $form = new Application_Form_CreationDemande();
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                echo '<pre>', var_export($formData,false),'<pre>';
                //alors si le client n'existe pas ' on insert d'abord dans la table client 
                // et ensuite  on insert dans la table demande_xprices
                //si le client existe  alors on insert immédiatement dans la table demande_xprices
                $demandes_xprice = new Application_Model_DbTable_Xprices();
                $demande_xprice = $demandes_xprice ->createXprice(
                $numwp,
                $trackingNumber,
                $formData['commentaire_demande_article'],
                $infos_offre->date_offre,
                $formData['mini_demande_article'],
                $user_info['id_user'],
                        null,
                $infos_client['OKCUNO']);
                echo '<pre>',  var_export($demande_xprice, false),'</pre>';
                /*
                 * ici insertion dans les tables articles et demande_article_xprices 
                 * à partir d'un foreach sur $resultat
                 * 
                 * donc pour chaque ligne du tableau $resultat  on insert d'abord dans la table articles
                 *  puis dans la table demande_article_xprices
                 */
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

