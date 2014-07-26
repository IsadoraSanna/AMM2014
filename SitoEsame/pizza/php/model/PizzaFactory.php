<?php

include_once 'Db.php';
include_once 'Pizza.php';

class PizzaFactory {
    
    private static $singleton;

    private function __constructor() {
        
    }

    /**
     * Restiuisce un singleton per creare Modelli
     * @return PizzaFactory
     */
    public static function instance() {
        if (!isset(self::$singleton)) {
            self::$singleton = new PizzaFactory();
        }

        return self::$singleton;
    }
    
    public function &getPizze() {

        $pizze = array();
        $query = "select * from pizze";
        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[getPizze] impossibile inizializzare il database");
            $mysqli->close();
           
        }
        $result = $mysqli->query($query);
        if ($mysqli->errno > 0) {
            error_log("[getMPizze] impossibile eseguire la query");
            $mysqli->close();
         
        }

        while ($row = $result->fetch_array()) {
            $pizze[] = self::creaPizzaDaArray($row);
        }

        $mysqli->close();
        return $pizze;
    }    
    
    private function creaPizzaDaArray($row) {
        $pizza = new Pizza();
        $pizza->setId($row['id']);
        $pizza->setNome($row['nome']);
        $pizza->setIngredienti($row['ingredienti']);
        $pizza->setPrezzo($row['prezzo']);
        $pizza->setImmagine($row['immagine']);
        return $pizza;
    }


    public function getIdPizze() {

        $pizzeId = array();
        $query = "select 
            pizze.id id 
                from pizze";
        
        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[getIdPizze] impossibile inizializzare il database");
            $mysqli->close();
           
        }
        $result = $mysqli->query($query);
        if ($mysqli->errno > 0) {
            error_log("[getIdPizze] impossibile eseguire la query");
            $mysqli->close();
         
        }
        $i = 0;
        while ($row = $result->fetch_array()) {
            $pizzeId[$i] = $row['id'];
            $i++;
        }

        $mysqli->close();
        return $pizzeId;
    }    
}

?>