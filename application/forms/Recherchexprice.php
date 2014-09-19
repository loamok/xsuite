<?php

class Application_Form_Recherchexprice extends Zend_Form {

    public function init() {
        $this->setMethod('post');
        $this->setAction('/xprice/');


        $tracking_number = new Zend_Form_Element_Text('tracking_number');
        $tracking_number->setLabel("Entrez le tracking number: SP-FR")
                ->setRequired(TRUE)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('regex', false, array('/^[a-zA-Z]|[a-zA-Z]|\d$/', 'messages' => array(Zend_Validate_Regex::NOT_MATCH => "numéro invalide")))
                ->addValidator('NotEmpty', true, array('messages' => array(Zend_Validate_NotEmpty::IS_EMPTY => "lechamp tracking number ne peut pas être vide.")));

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('tracking_number', 'submitbutton')
                ->setLabel('rechercher');
        $this->addElements(array($tracking_number, $submit));
    }

}

