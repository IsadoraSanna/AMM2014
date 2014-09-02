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
    
    /*
    * @return tutte le pizze esistenti all'interno della tabella pizze   
    */
    public function &getPizze() {

        $pizze = array();
        $query = "select * from pizze";
        
        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[getPizze] impossibile inizializzare il database");
            $mysqli->close();
            return 0;
           
        }
        $result = $mysqli->query($query);
        if ($mysqli->errno > 0) {
            error_log("[getMPizze] impossibile eseguire la query");
            $mysqli->close();
            return 0;
         
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
        return $pizza;
    }
    
    /*
    * @param $id id pizza
    * @return la pizza alla quale corrisponde quel determinato id  
    */
    public function getPizzaPerId($id) {
        $pizza = array();
        $query = "SELECT * from pizze WHERE id = ?";
        
        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[getPizzaPerId] impossibile inizializzare il database");
            $mysqli->close();
            return 0;
        }

        $stmt = $mysqli->stmt_init();
        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[getPizzaPerId] impossibile" .
                    " inizializzare il prepared statement");
            $mysqli->close();
            return 0;
        }

        if (!$stmt->bind_param('i', $id)) {
            error_log("[getPizzaPerId] impossibile" .
                    " effettuare il binding in input");
            $mysqli->close();
            return 0;
        }  
        
        $pizza = self::creaPizzaDaStmt($stmt);
        
        $mysqli->close();
        return $pizza;        
    }
    
    public function &creaPizzaDaStmt(mysqli_stmt $stmt) {
        $pizza = array();
        if (!$stmt->execute()) {
            error_log("[creaPizzaDaStmt] impossibile" .
                    " eseguire lo statement");
            return null;
        }

        $row = array();
        $bind = $stmt->bind_result(
                $row['id'], 
                $row['nome'],
                $row['ingredienti'],
                $row['prezzo']);

        if (!$bind) {
            error_log("[creaPizzaDaStmt] impossibile" .
                    " effettuare il binding in output");
            return null;
        }

        while ($stmt->fetch()) {
            $pizza = self::creaPizzaDaArray($row);
        }
        $stmt->close();

        return $pizza;
    }     
    /*
    * @return solo gli id di tutte le pizze presenti all'interno della tabella pizze
    */
    public function getIdPizze() {

        $pizzeId = array();
        $query = "select 
            pizze.id id 
                from pizze";
        
        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[getIdPizze] impossibile inizializzare il database");
            $mysqli->close();
            return 0;
           
        }
        $result = $mysqli->query($query);
        if ($mysqli->errno > 0) {
            error_log("[getIdPizze] impossibile eseguire la query");
            $mysqli->close();
            return 0;
         
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