<?php

class Application_Form_Prixfobfr extends Zend_Form
{

    public function init()
    {
          /*
         * fieldset commentaire
         */
        
        $commentaire_fobfr = new Zend_Form_Element_Textarea('commentaire_fobfr');
        $commentaire_fobfr->setLabel('Commentaire fobfr:')
                ->setRequired(false);
        
        /*
         * bouton de soumission
         */
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id_demande_article', 'submitbutton')->setLabel("Enregistrez");
        
        /*
         * ajout des élements au form
         */
        $this->addElement($commentaire_fobfr);
        
        
        $this->addDisplayGroup(array("commentaire_fobfr"), 'commentairefobfr', array('disableLoadDefaultDecorators' => true));
        
        $this->setDisplayGroupDecorators(array(
            'FormElements',
            'Fieldset'
        ));
        
        $this->getDisplayGroup('commentairefobfr')
                ->setLegend("Commentaires Fobfr");
        $this->addElement($submit);
        
    }


}

