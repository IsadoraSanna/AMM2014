<?php


class Pizza {
    
    private $id;
    
    private $nome;
    
    private $ingredienti;
    
    private $prezzo;

    
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
    
    public function setNome($nome) {
        $this->nome = $nome;
        return true;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setIngredienti($ingredienti) {
        $this->ingredienti = $ingredienti;
        return true;
    }

    public function getIngredienti() {
        return $this->ingredienti;
    }
    
    public function getPrezzo() {
        return $this->prezzo;
    }

    public function setPrezzo($prezzo) {
        $this->prezzo = $prezzo;
    }
    
}
?>