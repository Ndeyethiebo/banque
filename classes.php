<?php
class Client {
    public $id;
    public $prenom;
    public $nom;
    public $adresse;
    public $telephone;
        public function __construct($prenom, $nom, $adresse, $telephone) {
        $this->prenom = $prenom;
        $this->nom = $nom;
        $this->adresse = $adresse;
        $this->telephone = $telephone;
    }
}
class CompteBancaire {
    public $id;
    public $numero;
    public $solde;
    public $proprietaire_id; // ID du propriétaire du compte
    public function __construct($numero, $solde, $proprietaire_id) {
        $this->numero = $numero;
        $this->solde = $solde;
        $this->proprietaire_id = $proprietaire_id;
    }
}
class OperationBancaire {
    public static function effectuerDepot($compte, $montant) {
        $compte->solde += $montant;
    }
    
    public static function effectuerRetrait($compte, $montant) {
        $compte->solde -= $montant;
    }
    
    public static function effectuerVirement($compte_source, $compte_destination, $montant) {
        self::effectuerRetrait($compte_source, $montant);
        self::effectuerDepot($compte_destination, $montant);
    }
}
?>