<?php

class Application_Model_DbTable_Clients extends Zend_Db_Table_Abstract {

    protected $_name = 'clients';

    public function getClientnumwp($numwp_client) {
        $numwp_client = "$numwp_client";
        $row = $this->fetchRow("numwp_client = '{$numwp_client}'");
        if (!$row) {
            //throw new Exception("could not find row $numwp_client");
            return null;
        } else {
            return $row->toArray();
        }
    }

    public function createClient($nom_client, $numwp_client, $adresse_client, $id_industry) {
        $data = array(
            'nom_client' => $nom_client,
            'numwp_client' => $numwp_client,
            'adresse_client' => $adresse_client,
            'id_industry' => $id_industry
        );
        $this->insert($data);
        return $this;
    }

}

