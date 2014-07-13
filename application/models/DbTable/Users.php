<?php

class Application_Model_DbTable_Users extends Zend_Db_Table_Abstract {

    protected $_name = 'users';

    public function getUser($id_user) {
        $id_user = (int) $id_user;
        $row = $this->fetchRow('id_user=' . $id_user);
        if (!$row) {
            throw new Exception("could not find row $id_user");
        }
        return $row->toArray();
    }
    public function getMovexUser($numwp_user) {
       // $query = "select * from users where numwp_user = $numwp_user";
        $numwp_user = "$numwp_user";
        $row = $this->fetchRow('numwp_user like "'."{$numwp_user}".'"');
        if(!$row){
            throw new Exception("could not find row $numwp_user");
        }
        return $row->toArray();
    }

    /* @todo commentaire franck
     * Ajouter un "return $this" permettrais le chainage des appels
     */

    public function createUser($nom_user, $prenom_user, $tel_user, $email_user, $password_user, $numwp_user, $id_fonction, $id_zone, $id_holon) {
        $data = array(
            'nom_user' => $nom_user,
            'prenom_user' => $prenom_user,
            'tel_user' => $tel_user,
            'email_user' => $email_user,
            'password_user' => $password_user,
            'numwp_user' => $numwp_user,
            'id_fonction' => $id_fonction,
            'id_zone' => $id_zone,
            'id_holon' => $id_holon
        );
        $this->insert($data);
        return $this;
    }

    public function updateUser($id_user, $nom_user, $prenom_user, $tel_user, $email_user, $password_user, $numwp_user, $id_fonction, $id_zone, $id_holon) {

        $data = array(
            'nom_user' => $nom_user,
            'prenom_user' => $prenom_user,
            'tel_user' => $tel_user,
            'email_user' => $email_user,
            'password_user' => $password_user,
            'numwp_user' => $numwp_user,
            'id_fonction' => $id_fonction,
            'id_zone' => $id_zone,
            'id_holon' => $id_holon
        );
        $this->update($data, 'id_user=' . (int) $id_user);
        return $this;
    }

    public function createFromForm(Application_Form_User $form) {
        $data= array(
        'nom_user' => $form->getValue('nom_user'),
        'prenom_user' => $form->getValue('prenom_user'),
        'tel_user' => $form->getValue('tel_user'),
        'email_user' => $form->getValue('email_user'),
        'id_holon' => $form->getValue('id_holon'),
        'id_fonction' => $form->getValue('id_fonction'),
        'numwp_user' => $form->getValue('numwp_user'),
        'id_zone' => $form->getValue('id_zone'),
        'password_user' => $form->getValue('password_user')
         );
     $this->insert($data);
     return $this;
    }
    public function updateFromForm ($form) {
         $data = array(
            'nom_user' => $form->getValue('nom_user'),
        'prenom_user' => $form->getValue('prenom_user'),
        'tel_user' => $form->getValue('tel_user'),
        'email_user' => $form->getValue('email_user'),
        'id_holon' => $form->getValue('id_holon'),
        'id_fonction' => $form->getValue('id_fonction'),
        'numwp_user' => $form->getValue('numwp_user'),
        'id_zone' => $form->getValue('id_zone'),
        'password_user' => $form->getValue('password_user')
        );
          $this->update($data, 'id_user=' . (int) $form->getValue('id_user'));
        return $this;
    }
    
    public function deleteUser($id_user) {
        $this->delete('id_user='.(int)$id_user);
    }
}

