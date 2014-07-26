<?php

include_once 'Pizza_ordine.php';
include_once 'Pizza.php';
include_once 'Ordine.php';

class Pizza_ordineFactory {

    private static $singleton;

    private function __constructor() {
        
    }

    /**
     * Restiuisce un singleton per creare Modelli
     * @return ModelloFactory
     */
    public static function instance() {
        if (!isset(self::$singleton)) {
            self::$singleton = new Pizza_ordineFactory();
        }

        return self::$singleton;
    }
    
    
    public function creaPO($idPizza, $idOrdine, $quantita, $dimensione) {
        $query = "INSERT INTO `pizze_ordini`(`pizza_id`, `ordine_id`, `quantita`, `dimensione`) VALUES (?, ?, ?, ?)";

        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[creaPO] impossibile inizializzare il database");
            return 0;
        }

        $stmt = $mysqli->stmt_init();

        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[creaPO] impossibile" .
                    " inizializzare il prepared statement");
            $mysqli->close();
            return 0;
        }

        if (!$stmt->bind_param('iiis', $idPizza, $idOrdine, $quantita, $dimensione)) {
            error_log("[creaPO] impossibile" .
                    " effettuare il binding in input");
            $mysqli->close();
            return 0;
        }

       if (!$stmt->execute()) {
  
            error_log("[creaPO] impossibile" .
                    " eseguire lo statement");
            $mysqli->close();
            return 0;
        }
        $mysqli->close();
        return $stmt->affected_rows;
    }
    
    public function getPrezzoParziale($id){
        
        $query = "SELECT
                pizze_ordini.quantita quantita,
                pizze_ordini.dimensione dimensione,
                pizze.prezzo pizza_prezzo
                
                FROM pizze_ordini
                JOIN pizze ON pizze_ordini.pizza_id = pizze.id
                WHERE pizze_ordini.ordine_id = ?";

        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[getPrezzoParziale] impossibile inizializzare il database");
            $mysqli->close();
            return true;
        }

        $stmt = $mysqli->stmt_init();
        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[getPrezzoParziale] impossibile" .
                    " inizializzare il prepared statement");
            $mysqli->close();
            return true;
        }

        if (!$stmt->bind_param('i', $id)) {
            error_log("[getPrezzoParziale] impossibile" .
                    " effettuare il binding in input");
            $mysqli->close();
            return true;
        }

        $prezzo = self::caricaPrezzoPODaStmt($stmt);

        $mysqli->close();
        return $prezzo;        
    }
    
    
        public function &caricaPrezzoPODaStmt(mysqli_stmt $stmt) {
        //30% in piu del prezzo normale se è gigante
        $perc = 30/100;    
        if (!$stmt->execute()) {
            error_log("[caricaPrezzoPODaStmt] impossibile" .
                    " eseguire lo statement");
            return null;
        }

        $row = array();
        $bind = $stmt->bind_result(
                $row['quantita'],
                $row['dimensione'],
                $row['pizza_prezzo']);

        if (!$bind) {
            error_log("[caricaPrezzoPODaStmt] impossibile" .
                    " effettuare il binding in output");
            return null;
        }
        $prezzo = 0;
        while ($stmt->fetch()) {
            if($row['dimensione'] == "normale") $prezzo += $row['quantita'] * $row['pizza_prezzo'];
            else $prezzo += $row['quantita'] * ($row['pizza_prezzo']+($row['pizza_prezzo']*$perc));
        }

        $stmt->close();

        return $prezzo;
    }         
    
    public function getNPizze($id){
        $query = "SELECT 
            pizze_ordini.quantita quantita 
            FROM pizze_ordini 
            WHERE pizze_ordini.ordine_id = ?";

        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[getNPizze] impossibile inizializzare il database");
            $mysqli->close();
            return true;
        }

        $stmt = $mysqli->stmt_init();
        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[getNPizze] impossibile" .
                    " inizializzare il prepared statement");
            $mysqli->close();
            return true;
        }

        if (!$stmt->bind_param('i', $id)) {
            error_log("[getPrezzoParziale] impossibile" .
                    " effettuare il binding in input");
            $mysqli->close();
            return true;
        }

       if (!$stmt->execute()) {
            error_log("[caricaPrezzoPODaStmt] impossibile" .
                    " eseguire lo statement");
            return null;
        }

        $row = array();
        $bind = $stmt->bind_result($row['quantita']);

        if (!$bind) {
            error_log("[caricaPrezzoPODaStmt] impossibile" .
                    " effettuare il binding in output");
            return null;
        }
        $nPizze = 0;
        while ($stmt->fetch()) {
            $nPizze += $row['quantita'];
        }

        $mysqli->close();
        return $nPizze;                
    }
    
}



?>