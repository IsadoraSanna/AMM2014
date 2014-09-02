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
    
    /*
     * La funzione crea un nuovo PO
    * @param $idPizza id della pizza considerata
    * @param $idOrdine id dell'ordine considerato
    * @param $quantita di pizze
    * @param $dimensione delle pizze
    * @return il numero di righe create
    */    
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

    /*
     * La funzione cancella PO
    * @param $id id dell'ordine considerato
    * @return il numero di righe cancellate
    */    
    public function cancellaPO($id){
        $query = "delete from pizze_ordini where ordine_id = ?";
        
        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[cancellaPO] impossibile inizializzare il database");
            return false;
        }

        $stmt = $mysqli->stmt_init();

        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[cancellaPO] impossibile" .
                    " inizializzare il prepared statement");
            $mysqli->close();
            return false;
        }

        if (!$stmt->bind_param('i', $id)){
        error_log("[cancellaPO] impossibile" .
                " effettuare il binding in input");
        $mysqli->close();
        return false;
        }

        if (!$stmt->execute()) {
            error_log("[cancellaPO] impossibile" .
                    " eseguire lo statement");
            $mysqli->close();
            return false;
        }

        $mysqli->close();
        return $stmt->affected_rows;        
    }
    

    /*
    * La funzione fornisce il prezzo di un insieme di pizze dello stesso tipo appartenente allo stesso ordine valutandone la dimensione  
    * @param $PO PO di riferimento
    * @return il prezzo dell'insieme di pizze
    */    
    public function getPrezzoPerPizze(Pizza_ordine $PO){
        $query = "SELECT
                pizze_ordini.quantita quantita,
                pizze_ordini.dimensione dimensione,
                pizze.prezzo pizza_prezzo
                
                FROM pizze_ordini
                JOIN pizze ON pizze_ordini.pizza_id = pizze.id
                WHERE pizze_ordini.id = ?";

        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[getPrezzoPerPizze] impossibile inizializzare il database");
            $mysqli->close();
            return false;
        }

        $stmt = $mysqli->stmt_init();
        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[getPrezzoPerPizze] impossibile" .
                    " inizializzare il prepared statement");
            $mysqli->close();
            return false;
        }

        if (!$stmt->bind_param('i', $PO->getId())) {
            error_log("[getPrezzoPerPizze] impossibile" .
                    " effettuare il binding in input");
            $mysqli->close();
            return false;
        }

        $prezzo = self::caricaPrezzoPODaStmt($stmt);

        $mysqli->close();
        return $prezzo;         
    
    }

    /*
    * La funzione calcola il prezzo totale dell'ordine senza aggiungere i costi del trasporto a domicilio
    * @param $ordine ordine di riferimento
    * @return prezzo dell'ordine
    */       
    public function getPrezzoParziale(Ordine $ordine){
        
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
            return false;
        }

        if (!$stmt->bind_param('i', $ordine->getId())) {
            error_log("[getPrezzoParziale] impossibile" .
                    " effettuare il binding in input");
            $mysqli->close();
            return false;
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

    /*
    * @param $id id dell'ordine di riferimento
    * @return la quantità di pizze relative all'ordine di riferimento
    */    
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
            return null;
        }

        if (!$stmt->bind_param('i', $id)) {
            error_log("[getNPizze] impossibile" .
                    " effettuare il binding in input");
            $mysqli->close();
            return null;
        }

       if (!$stmt->execute()) {
            error_log("[getNPizze] impossibile" .
                    " eseguire lo statement");
            return null;
        }

        $row = array();
        $bind = $stmt->bind_result($row['quantita']);

        if (!$bind) {
            error_log("[getNPizze] impossibile" .
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

    /*
    * @param $orarioId id dell'orario di riferimento
    * @return il numero di pizze gia ordinate in quella giornata per la fascia oraria di riferimento
    */      
    public function getNPizzePerOrario($orarioId){
        $query = "SELECT 
            pizze_ordini.quantita quantita 

            FROM pizze_ordini
            JOIN ordini ON pizze_ordini.ordine_id = ordini.id
            WHERE ordini.orario_id = ? AND ordini.data LIKE ?";
        
        $data = date('Y\-m\-d').'%';
        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[getNPizzePerOrario] impossibile inizializzare il database");
            $mysqli->close();
            return true;
        }

        $stmt = $mysqli->stmt_init();
        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[getNPizzePerOrario] impossibile" .
                    " inizializzare il prepared statement");
            $mysqli->close();
            return null;
        }

        if (!$stmt->bind_param('is', $orarioId, $data)) {
            error_log("[getNPizzePerOrario] impossibile" .
                    " effettuare il binding in input");
            $mysqli->close();
            return null;
        }

       if (!$stmt->execute()) {
            error_log("[getNPizzePerOrario] impossibile" .
                    " eseguire lo statement");
            return null;
        }

        $row = array();
        $bind = $stmt->bind_result($row['quantita']);

        if (!$bind) {
            error_log("[getNPizzePerOrario] impossibile" .
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

    /*
    * @param $ordine ordine di riferimento
    * @return un determinato record in cui l'id dell'ordine è uguale a quello dato come riferimento
    */     
    public function getPOPerIdOrdine(Ordine $ordine){
        $po = array();
        $query = "SELECT *             
            FROM pizze_ordini
            WHERE pizze_ordini.ordine_id = ?";   
        
        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[getPOPerIdOrdine] impossibile inizializzare il database");
            $mysqli->close();
            return 0;
        }

        $stmt = $mysqli->stmt_init();
        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[getPOPerIdOrdine] impossibile" .
                    " inizializzare il prepared statement");
            $mysqli->close();
            return 0;
        }

        if (!$stmt->bind_param('i', $ordine->getId())) {
            error_log("[getPOPerIdOrdine] impossibile" .
                    " effettuare il binding in input");
            $mysqli->close();
            return 0;
        }        
        
        $po = self::caricaPODaStmt($stmt);

        $mysqli->close();
        return $po;        
    }    
    
    public function &caricaPODaStmt(mysqli_stmt $stmt) {
        $po = array();
        if (!$stmt->execute()) {
            error_log("[caricaPODaStmt] impossibile" .
                    " eseguire lo statement");
            return null;
        }

        $row = array();
        $bind = $stmt->bind_result(
                $row['pizzaId'], 
                $row['ordineId'],
                $row['id'],
                $row['quantita'],
                $row['dimensione']);

        if (!$bind) {
            error_log("[caricaPODaStmt] impossibile" .
                    " effettuare il binding in output");
            return null;
        }

        while ($stmt->fetch()) {
            $po[] = self::creaPODaArray($row);
        }

        $stmt->close();

        return $po;
    }                
        
    public function creaPODaArray($row) {
        $po = new Pizza_ordine();
        $po->setPizza($row['pizzaId']);
        $po->setOrdine($row['ordineId']);        
        $po->setId($row['id']);
        $po->setQuantita($row['quantita']);       
        $po->setDimensione($row['dimensione']);         
        return $po;
    }    
}
?>