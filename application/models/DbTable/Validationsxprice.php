<?php

class Application_Model_DbTable_Validationsxprice extends Zend_Db_Table_Abstract
{

    protected $_name = 'validations_xprice';

public function createValidation($date_validation,$etat_validation,$commentaire_validation,$id_user,$tracking_number){
    $data =array('date_validation'=>$date_validation,
        'etat_validation'=>$etat_validation,
        'commentaire_validation'=>$commentaire_validation,
        'id_user'=>$id_user,
        'tracking_number_demande_xprice'=>$tracking_number);
     $this->insert($data);
        return $this;
}
}

