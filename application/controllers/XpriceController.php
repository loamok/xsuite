<?php

class XpriceController extends Zend_Controller_Action {

    //public $dsn3="DRIVER=Client Acess ODBC Driver(32-bit);UID=EU65535;PWD=CCS65535;SYSTEM=10.105.80.32;DBQ=CVXCDTA";
    public $dsn = "DRIVER={MySQL};Server=127.0.0.1;Database=CVXCDTA;UID=EU65535;PWD=CCS65535;";
    public $dsn2 = "DRIVER={MySQL};Server=127.0.0.1;Database=MVXCDTA;UID=root;PWD=geek;";
    public $odbc_conn = null;
    public $odbc_conn2 = null;
   //  public $odbc_conn3= null;

    public function init() {
        $this->odbc_conn = odbc_connect($this->dsn, "", "");
        if (!$this->odbc_conn) {
            echo "pas d'accès à la base de données";
        }
        $this->_auth = Zend_Auth::getInstance();
        $this->view->messages = $this->_helper->flashMessenger->getMessages();
        
        $this->odbc_conn2 = odbc_connect($this->dsn2,"","");
        if(!$this->odbc_conn2){
            echo "pas d'accès à la base de données MVXCDTA";
        }
        // $this->odbc_conn3 = odbc_connect($this->dsn3,"","");
        //if(!$this->odbc_conn3){
        //    print odbc_errormsg();
       //}
        
    }

    public function indexAction() {
        // action body
    }

    public function numwpAction() {
        $numwp = $this->getRequest()->getParam('numwp', null);
        $form = new Application_Form_Numwp();
        if(!is_null($numwp)) {
            $form->populate(array("num_offre_worplace" => $numwp));
        }
        if ($this->getRequest()->isPost()) {
            $redirector = $this->_helper->getHelper('Redirector');

            if ($form->isValid($this->getRequest()->getPost())) {
                $query = "select COUNT(OOLINE.OBORNO) as nbNumwp FROM OOLINE WHERE OOLINE.OBORNO = '{$_POST['num_offre_worplace']}';";
                $results = odbc_exec($this->odbc_conn, $query);
                $r = odbc_fetch_object($results);
                if ($r->nbNumwp > 1) {
                    $redirector->gotoSimple('create', 'xprice', null, array('numwp' => $_POST['num_offre_worplace']));
                } else {
                    $flashMessenger = $this->_helper->getHelper('FlashMessenger');
                    $message = "ce numéro d'offre n'a pas de concordance dans la base MOVEX";
                    $flashMessenger->addMessage($message);
                    $redirector->gotoSimple('numwp', 'xprice', null, array('numwp' => $_POST['num_offre_worplace']));
                }
            } else {
                $form->populate($this->getRequest()->getPost());
            }
        }
        $this->view->form = $form;
    }

    public function createAction() {
        $numwp = $this->getRequest()->getParam('numwp', null);
        $demandes_xprice = new Application_Model_DbTable_Xprices();
        $demandeXprice = $demandes_xprice->getNumwp($numwp);
        if(!is_null($demandeXprice)){
            $redirector = $this->_helper->getHelper('Redirector');
            $flashMessenger = $this->_helper->getHelper('FlashMessenger');
            $message = "Cette offre a déjà été créée.";
            $flashMessenger->addMessage($message);
            $message = "Veuillez cliquer sur : <a href=\"/xprice/update\">'Xprice : Modifier'</a> ou cliquez dans le menu de gauche.";
            $flashMessenger->addMessage($message);
            $redirector->gotoSimple('index', 'xprice');
        }
        $this->view->numwp = $numwp;
        if (!is_null($numwp)) {
            //si le numero workplace est valide alors on fait la requête pour movex
            // requête d'informations de l'offre
            $query = "select "
                    . "OOLINE.OBORNO AS numwp, "
                    . "OOLINE.OBRGDT AS date_offre "
                    . "from OOLINE where OOLINE.OBORNO='{$numwp}' "
                    . "group by OOLINE.OBORNO;";
            $infos_offre = odbc_fetch_object(odbc_exec($this->odbc_conn, $query));
            // echo '<pre>', var_export($infos_offre->date_offre, false), '</pre>';
            $this->view->infos_offre = $infos_offre;
            $user = $this->_auth->getStorage()->read();
            $zoneT = new Application_Model_DbTable_Zones();
            $zone = $zoneT->fetchRow(array('id_zone' => $user->id_zone));
            $Xprices = new Application_Model_DbTable_Xprices();
            $trackingNumber = Application_Model_DbTable_Xprices::makeTrackingNumber($zone->nom_zone, $Xprices->lastId(true));
            // $this->view->trackingNumber = Application_Model_DbTable_Xprices::makeTrackingNumber($zone->nom_zone, $Xprices->lastId(true));
            $this->view->trackingNumber = $trackingNumber;
            // requetes pour remplir le phtml :
            //requete 1 pour remplir  les données du commercial à partir du numwp
            $query1 = "SELECT OOLINE.OBSMCD  as userwp "
                    . "FROM OOLINE "
                    . "WHERE OOLINE.OBORNO='{$numwp}' "
                    . "GROUP BY OOLINE.OBORNO;";
            $numwp_user = odbc_fetch_array(odbc_exec($this->odbc_conn, $query1));
            $usertest = new Application_Model_DbTable_Users();
            $user_info = $usertest->getMovexUser($numwp_user['userwp']);
            $this->view->user_info = $user_info;
            $id_holon = $user_info['id_holon'];
            $holonuser = new Application_Model_DbTable_Holons();
            $holonuser1 = $holonuser->getHolon($id_holon);
            $nom_holon = $holonuser1['nom_holon'];
            $this->view->holon = $nom_holon;
            /*
             * on va chercher les informations concernant les articles dans la table ooline à partir du numwp
             * pour pouvoir ensuite les afficher dans la vue à l'aide d'un foreach
             */
            $query2 = "select "
                    . "OOLINE.OBORNO,"
                    . "OOLINE.OBCUNO,"
                    . "OOLINE.OBITNO,"
                    . "OOLINE.OBITDS,"
                    . "OOLINE.OBORQT,"
                    . "OOLINE.OBLNA2,"
                    . "OOLINE.OBNEPR,"
                    . "OOLINE.OBSAPR,"
                    . "OOLINE.OBELNO,"
                    . "OOLINE.OBRGDT,"
                    . "OOLINE.OBLMDT,"
                    . "OOLINE.OBSMCD "
                    . "from OOLINE WHERE OOLINE.OBORNO='{$numwp}' AND OOLINE.OBDIVI LIKE 'FR0' AND OOLINE.OBCONO=100";
            $resultats = odbc_exec($this->odbc_conn, $query2);

            while ($resultat[] = odbc_fetch_array($resultats)) {
                $this->view->resultat = $resultat;
            }
            /* aller chercher prix fob prix cif sur la base MVCDXTA en utilisant les tables KOPCDT(date) KOITNO ( code article) et KO ( prix cif)
             * 
             */foreach($this->view->resultat as $itnoarticle){
             $query3="select MCHEAD.KOPCDT, MCHEAD.KOCSU3, MCHEAD.KOITNO from MCHEAD where MCHEAD.KOITNO = {$itnoarticle['OBITNO']}";
            
             $itno[]=$itnoarticle['OBITNO'];
             }
             echo var_dump($itno);  
             while ($itno){
                 
             }exit();
            /*
             * à partir du code client de la table ooline on va chercher dans la table ocusma
             * les informations concernant le client pour pouvoir les afficher dans la vue phtml
             */
            $query1bis = "select * "
                    . "from OCUSMA "
                    . "where OCUSMA.OKCUNO = '{$resultat[0]['OBCUNO']}'";
            $infos_client = odbc_fetch_array(odbc_exec($this->odbc_conn, $query1bis));
            $this->view->infos_client = $infos_client;
            /*
             * information concernant  le projet industry auquel appartient le client
             *    donc à partir du code movex industry on va chercher dans la base xsuite
             *  le nom de l'industrie auquel le client appartient pour ensuite l'afficher dans la vue
             */
            $industry = new Application_Model_DbTable_Industry();
            $info_industry = $industry->getMovexIndustry($infos_client['OKCUCL']);
            $this->view->info_industry = $info_industry;
        }

        $form = new Application_Form_CreationDemande();
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                //alors si le client n'existe pas ' on insert d'abord dans la table client
                //"select id_client from clients where id_client = {$infos_client['OKCUNO']}";
                $clients = new Application_Model_DbTable_Clients();
                $client = $clients->getClientnumwp($infos_client['OKCUNO']);

                $adresse_client = $infos_client['OKCUA1'] . $infos_client['OKCUA2'] . $infos_client['OKCUA3'] . $infos_client['OKCUA4'];

                if (is_null($client)) {
                    $newclient = $clients->createClient($infos_client['OKCUNM'], $infos_client['OKCUNO'], $adresse_client, $infos_client['OKCUCL']);
                }
                // et ensuite  on insert dans la table demande_xprices
                //si le client existe  alors on insert immédiatement dans la table demande_xprices

                $numwpexist = $demandes_xprice->getNumwp($numwp);
                if (is_null($numwpexist)) {
                    $demande_xprice = $demandes_xprice->createXprice(
                            $numwp, $trackingNumber, $formData['commentaire_demande_article'], $infos_offre->date_offre, $formData['mini_demande_article'], $user_info['id_user'], null, $infos_client['OKCUNO']);
                }
                /*
                 * ici insertion dans les tables articles et demande_article_xprices
                 * à partir d'un foreach sur $resultat
                 *
                 * donc pour chaque ligne du tableau $resultat  on insert d'abord dans la table articles
                 *  puis dans la table demande_article_xprices
                 */
                $articles_xprice = new Application_Model_DbTable_Articles();
                $demandes_xprice = new Application_Model_DbTable_DemandeArticlexprices();
                foreach ($this->view->resultat as $resultarticle) {
                    $articleexist = $articles_xprice->getArticle($resultarticle['OBITNO']);
                    if (is_null($articleexist)) {
                        $articles_xprice = $articles_xprice->createArticle($resultarticle['OBITDS'], $resultarticle['OBITNO'], null);
                    }
                    $demande_xprice = $demandes_xprice->createDemandeArticlexprice($resultarticle['OBNEPR'], $resultarticle['OBSAPR'], $resultarticle['OBORQT'], round($resultarticle['OBNEPR'] * 100 / $resultarticle['OBSAPR'], 2), $infos_offre->date_offre, null, null, null, null, null, $trackingNumber, $resultarticle['OBITNO'], $resultarticle['OBITDS'], $numwp);
                }
                /*
                 * ici, envoi des mails 
                 */
                $emailVars = Zend_Registry::get('emailVars');
                $fobfrMail =  $emailVars->listes->fobfr;
                $url = "http://{$_SERVER['SERVER_NAME']}/xprice/prixfobfr/numwp/{$numwp}";
                $corpsMail = "Bonjour,\n"
                        . "\n"
                        . "Vous avez une nouvelle demande XPrice à valider.\n"
                        . "Veuillez vous rendre à l'adresse url : \n"
                        . "%s"
                        . "\n\n"
                        . "Cordialement,\n"
                        . "\n"
                        . "--\n"
                        . "Le service info.";
                $mail = new Xsuite_Mail();
                $mail->setSubject("XPrice : Nouvelle demande à valider.")
                        ->setBodyText(sprintf($corpsMail, $url))
                        ->addTo($fobfrMail)
                        ->send();
                /*
                 * Fin du traitement
                 */
                $flashMessenger = $this->_helper->getHelper('FlashMessenger');
                $message = "Votre demande à bien été enregistrée.";
                $flashMessenger->addMessage($message);
                $redirector = $this->_helper->getHelper('Redirector');
                $redirector->gotoSimple('index', 'xprice');
            } else {
                $form->populate($formData);
            }
        }
        $this->view->form = $form;
    }

    public function prixfobfrAction() {
       $numwp = $this->getRequest()->getParam('numwp', null);
       //var_dump($numwp);
       $this->view->numwp = $numwp;
        $form = new Application_Form_Prixfobfr();
       /*
        * on va rechercher les informations concernant la demande _xprice
        */
       $infos_demande_xprice= new Application_Model_DbTable_Xprices();
       $info_demande_xprice = $infos_demande_xprice->getNumwp($numwp);
       echo '<pre>',var_export($info_demande_xprice),'</pre>';
     // var_dump( $info_demande_xprice['id_user']);
      $this->view->info_demande_xprice= $info_demande_xprice;
      $infos_user=new Application_Model_DbTable_Users();
      $info_user = $infos_user->getUserDemande($info_demande_xprice['id_user']);
     // echo '<pre>',var_export($info_user),'</pre>';
      $this->view->info_user=$info_user;
      $infos_client = new Application_Model_DbTable_Clients();
      $info_client = $infos_client->getClientnumwp($info_demande_xprice['numwp_client']);
      //echo '<pre>',var_export($info_client),'</pre>';
      $this->view->info_client=$info_client;
      $infos_demande_article_xprice= new Application_Model_DbTable_DemandeArticlexprices();
     $info_demande_article_xprice = $infos_demande_article_xprice->getDemandeArticlexprice($numwp);
      //echo '<pre>',  var_export($info_demande_article_xprice,true),'</pre>';
      $this->view->info_demande_article_xprice=$info_demande_article_xprice;
      foreach ($info_demande_article_xprice as $value) {
          
     
     $query= "select *"
            . "from "
           . "MCHEAD "
     . "WHERE MCHEAD.KOITNO = '{$value['code_article']}' order by KOPCDT desc limit 1";
     $infos_prixfobfr = odbc_exec($this->odbc_conn2,$query);
     while($info_prixfobfr =  odbc_fetch_array($infos_prixfobfr)){
         $date1 = substr($info_prixfobfr['KOPCDT'],0,-4);
         $date2 = substr($info_prixfobfr['KOPCDT'],4,-2);
         $date3 = substr($info_prixfobfr['KOPCDT'],6,2);
         $date = implode('-',array($date1,$date2,$date3));
         $this->view->info_prixfobfr=$info_prixfobfr;
     }
     
      }
       //il faut afficher le formulaire avec les champs  fobfr et prix cif
        //le commentaire du tc
        //la possibilité de mettre un commentaire
        //
         $this->view->form = $form;
    }

    public function deleteAction() {
        // action body
    }

    public function validateSMCAction() {
        // action body
    }

    public function updateAction() {
        // action body
    }

    public function listAction() {
        // action body
    }


}

