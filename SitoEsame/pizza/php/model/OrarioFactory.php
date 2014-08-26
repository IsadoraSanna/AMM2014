<?php

include_once 'Orario.php';

class OrarioFactory {
    
    private static $singleton;

    private function __constructor() {
        
    }

    /**
     * Restiuisce un singleton per creare Modelli
     * @return PizzaFactory
     */
    public static function instance() {
        if (!isset(self::$singleton)) {
            self::$singleton = new OrarioFactory();
        }

        return self::$singleton;
    }
    

    public function &getOrari(){
        $orari = array();
        $query = "select * from orari";
        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[getOrari] impossibile inizializzare il database");
            $mysqli->close();
           
        }
        $result = $mysqli->query($query);
        if ($mysqli->errno > 0) {
            error_log("[getOrari] impossibile eseguire la query");
            $mysqli->close();
         
        }

        while ($row = $result->fetch_array()) {
            $orari[] = self::creaOrariDaArray($row);
        }

        $mysqli->close();
        return $orari;        
    }

    
    public function &getOrariSuccessivi($orarioId){
        $orari = array();
        $query = "select * from orari where id >= ?";
        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[getOrari] impossibile inizializzare il database");
            $mysqli->close();
           
        }
        
        $stmt = $mysqli->stmt_init();
        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[getPizzaPerId] impossibile" .
                    " inizializzare il prepared statement");
            $mysqli->close();
            return $orari;
        }

        if (!$stmt->bind_param('i', $orarioId)) {
            error_log("[getPizzaPerId] impossibile" .
                    " effettuare il binding in input");
            $mysqli->close();
            return $orari;
        }  

        $orari = self::caricaOrariDaStmt($stmt);

        $mysqli->close();
        return $orari;        
    }
    
    private function caricaOrariDaStmt(mysqli_stmt $stmt){
        
        $orari = array();
        if (!$stmt->execute()) {
            error_log("[caricaOrariDaStmt] impossibile" .
                    " eseguire lo statement");
            return null;
        }

        $row = array();
        $bind = $stmt->bind_result(
                $row['id'], 
                $row['fasciaOraria'],
                $row['ordiniDisponibili']);

        if (!$bind) {
            error_log("[caricaOrariDaStmt] impossibile" .
                    " effettuare il binding in output");
            return null;
        }

        while ($stmt->fetch()) {
            $orari[] = self::creaOrariDaArray($row);
        }

        $stmt->close();

        return $orari;        
    }   
    
    private function creaOrariDaArray($row) {
        $orari = new Orario();
        $orari->setId($row['id']);
        $orari->setFasciaOraria($row['fasciaOraria']);
        $orari->setOrdiniDisponibili($row['ordiniDisponibili']);
        return $orari;
    }    
   
}
?>