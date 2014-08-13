<?php

class Orario{
    private $id;
    private $fasciaOraria;
    private $ordiniDisponibili;
    
   public function __construct() {
        
    }
    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $intVal = filter_var($id, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
        if (!isset($intVal)) {
            return false;
        }
        $this->id = $intVal;
    }
    
    public function setFasciaOraria($fasciaOraria) {
        $this->fasciaOraria = $fasciaOraria;
        return true;
    }

    public function getFasciaOraria() {
        return $this->fasciaOraria;
    }    
    
    public function setOrdiniDisponibili($ordiniDisponibili) {
        $this->ordiniDisponibili = $ordiniDisponibili;
        return true;
    }

    public function getOrdiniDisponibili() {
        return $this->ordiniDisponibili;
    }       
}
?>