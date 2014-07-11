<?php

class Application_Model_DbTable_Articlexprices extends Zend_Db_Table_Abstract
{

    protected $_name = 'demande_article_xprices';

    public function createArticlexprice($prix_demande_article,$quantite_demande_article,$remise_demande_article,$date_demande_article,$id_demande_xprice,$prix_accorde_demande_article,$remise_accorde_demande_article,$prix_fob_demande_article,$prix_cif_demande_article,$marge_demande_article,$tracking_number_demande_xprice,$id_article) {
        $data = array(
            'prix_demande_article'=>$prix_demande_article,
            'quantite_demande_article'=>$quantite_demande_article,
            'remise_demande_article'=>$remise_demande_article,
            'date_demande_article'=>$date_demande_article,
            'id_demande_xprice'=>$id_demande_xprice,
            'prix_accorde_demande_article'=>$prix_accorde_demande_article,
            'remise_accorde_demande_article'=>$remise_accorde_demande_article,
            'prix_fob_demande_article'=>$prix_fob_demande_article,
            'prix_cif_demande_article'=>$prix_cif_demande_article,
            'marge_demande_article'=>$marge_demande_article,
            'tracking_number_demande_xprice'=>$tracking_number_demande_xprice,
            'id_article'=>$id_article
        );
    }
}

