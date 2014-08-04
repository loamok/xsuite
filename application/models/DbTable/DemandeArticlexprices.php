<?php

class Application_Model_DbTable_DemandeArticlexprices extends Zend_Db_Table_Abstract {

    protected $_name = 'demande_article_xprices';

    public function createDemandeArticlexprice($prixwplace_demande_article, $prix_demande_article, $quantite_demande_article, $remise_demande_article, $date_demande_xprice, $prix_accorde_demande_article, $remise_accorde_demande_article, $prix_fob_demande_article, $prix_cif_demande_article, $marge_demande_article, $tracking_number_demande_xprice, $code_article, $reference_article, $num_workplace_demande_xprice) {
        $data = array(
            'prixwplace_demande_article' => $prixwplace_demande_article,
            'prix_demande_article' => $prix_demande_article,
            'quantite_demande_article' => $quantite_demande_article,
            'remise_demande_article' => $remise_demande_article,
            'date_demande_xprice' => $date_demande_xprice,
            'prix_accorde_demande_article' => $prix_accorde_demande_article,
            'remise_accorde_demande_article' => $remise_accorde_demande_article,
            'prix_fob_demande_article' => $prix_fob_demande_article,
            'prix_cif_demande_article' => $prix_cif_demande_article,
            'marge_demande_article' => $marge_demande_article,
            'tracking_number_demande_xprice' => $tracking_number_demande_xprice,
            'code_article' => $code_article,
            'reference_article' => $reference_article,
            'num_workplace_demande_xprice' => $num_workplace_demande_xprice
        );
        $this->insert($data);
        return $this;
    }
public function getDemandeArticlexprice($numwp){
    $numwp = "$numwp";
        $rows = $this->fetchAll("num_workplace_demande_xprice = '{$numwp}'");
        if (!$rows) {
            return null;
        } else {
            return $rows->toArray();
        }
    }
public function InserPrixFob($prixciff,$code_article,$numwp){
    $code_article = "$code_article";
    $numwp ="$numwp";
    $plop=$this->getAdapter();
    $datas=array('prix_fob_demande_article' => $prixciff,'prix_cif_demande_article' =>$prixciff);
    $where=$plop->quoteInto('code_article = ?',$code_article)
           .$plop->quoteInto('And num_workplace_demande_xprice = ?', $numwp); 
   $plop2=$this->update($datas,$where); var_dump($plop2);
    return $plop2;
}
}

