<?php

class Application_Model_DbTable_Xprices extends Zend_Db_Table_Abstract {

    protected $_name = 'demande_xprices';

    public function createXprice($num_workplace_demande_xprice, $tracking_number_demande_xprice, $commentaire_demande_xprice, $date_demande_xprice, $id_demande_article, $id_user, $id_validation) {
        $data = array(
            'num_workplace_demande_xprice' => $num_workplace_demande_xprice,
            'tracking_number_demande_xprice' => $tracking_number_demande_xprice,
            'commentaire_demande_xprice' => $commentaire_demande_xprice,
            'date_demande_xprice' => $date_demande_xprice,
            'id_demande_xprice' => $id_demande_article,
            'id_user' => $id_user,
            'id_validation' => $id_validation
        );
        $this->insert($data);
        return $this;
    }

    public function updateXprice($id_demande_xprice, $num_workplace_demande_xprice, $tracking_number_demande_xprice, $commentaire_demande_xprice, $date_demande_xprice, $id_demande_article, $id_user, $id_validation) {

        $data = array(
            'num_workplace_demande_xprice' => $num_workplace_demande_xprice,
            'tracking_number_demande_xprice' => $tracking_number_demande_xprice,
            'commentaire_demande_xprice' => $commentaire_demande_xprice,
            'date_demande_xprice' => $date_demande_xprice,
            'id_demande_xprice' => $id_demande_article,
            'id_user' => $id_user,
            'id_validation' => $id_validation
        );
        $this->update($data, 'id_demande_xprice=' . (int) $id_demande_xprice);
        return $this;
    }

    public function prixfob($trackingNumber){
        $query = "select * from demande_xprices"
                . " join demande_articles_xprice"
                . " on demande_xprices.tracking_number_demande_xprice = demande_articles_xprice.tracking_number_demande_xprice "
                . "where tracking_number_demande_xprice =$trackingNumber "; 
    }
}

