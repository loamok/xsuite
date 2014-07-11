<?php

class Application_Form_Numwp extends Zend_Form {

    public function init() {
        $this->setMethod('post');
        $this->setAction('/xprice/numwp');


        $num_offre_workplace = new Zend_Form_Element_Text('num_offre_worplace');
        $num_offre_workplace->setLabel("numero d'offre workplace : ")
                ->setRequired(TRUE)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('regex', false, array('/^00[9][0][6][0-9]/', 'messages' => array(Zend_Validate_Regex::NOT_MATCH => "numéro invalide")))
                ->addValidator('NotEmpty', true, array('messages' => array(Zend_Validate_NotEmpty::IS_EMPTY => "le champ offre workplace ne peut pas être vide.")));

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('num_offre_workplace', 'submitbutton')
                ->setLabel('entrer');
        $this->addElements(array($num_offre_workplace, $submit));
    }

}

