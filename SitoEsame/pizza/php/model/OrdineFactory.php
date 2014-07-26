<?php

include_once 'Ordine.php';
include_once 'User.php';
include_once 'Pizza.php';
include_once 'Pizza_ordine.php';

class OrdineFactory {

    private static $singleton;

    private function __constructor() {
        
    }

    /**
     * Restiuisce un singleton per creare Modelli
     * @return ModelloFactory
     */
    public static function instance() {
        if (!isset(self::$singleton)) {
            self::$singleton = new OrdineFactory();
        }

        return self::$singleton;
    }

    public function nuovoOrdine($ordine){
        $query = "INSERT INTO ordini (`id`) VALUES (?)";   
        
        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[nuovoOrdine] impossibile inizializzare il database");
            return 0;
        }

        $stmt = $mysqli->stmt_init();

        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[nuovoOrdine] impossibile" .
                    " inizializzare il prepared statement");
            $mysqli->close();
            return 0;
        }

        if (!$stmt->bind_param('i',
                $ordine->getId())) {
            error_log("[nuovoOrdine] impossibile" .
                    " effettuare il binding in input");
            $mysqli->close();
            return 0;
        }


        // inizio la transazione
        $mysqli->autocommit(false);

        if (!$stmt->execute()) {
            error_log("[nuovoOrdine] impossibile" .
                    " eseguire lo statement");
            $mysqli->rollback();
            $mysqli->close();
            return 0;
        }

        //query eseguita correttamente, termino la transazione
        $mysqli->commit();
        $mysqli->autocommit(true);

        $mysqli->close();
        return $stmt->affected_rows;
        
    }
    
    public function aggiornaOrdine($ordine, $domicilio){
        $query = "UPDATE `ordini` SET 
            `domicilio`= ?,
            `prezzo`= ?,
            `stato`= ?,
            `cliente_id`= ?,
            `addettoOrdini_id`= ?,
            `orario_id`= ? 
            WHERE id = ?";   
        
        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[nuovoOrdine] impossibile inizializzare il database");
            return 0;
        }

        $stmt = $mysqli->stmt_init();

        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[nuovoOrdine] impossibile" .
                    " inizializzare il prepared statement");
            $mysqli->close();
            return 0;
        }
        $prezzo = Pizza_ordineFactory::instance()->getPrezzoParziale($ordine->getId()); 
        $cliente_id = 1;
        $addetto_id = 1;
        $ora = 3;
        $stato = "non pagato";
        if (!$stmt->bind_param('sisiiii',
                $domicilio,
                $prezzo,
                $stato,
                $cliente_id,
                $addetto_id,
                $ora,
                $ordine->getId())) {
            error_log("[nuovoOrdine] impossibile" .
                    " effettuare il binding in input");
            $mysqli->close();
            return 0;
        }


        if (!$stmt->execute()) {
            error_log("[nuovoOrdine] impossibile" .
                    " eseguire lo statement");
            $mysqli->close();
            return 0;
        }

        $mysqli->close();
        return $stmt->affected_rows;        
    }
    
    public function getLastId(){
        $query = "SELECT ordini.id ordine_id FROM ordini ORDER BY Id DESC LIMIT 1";

        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[getLastId] impossibile inizializzare il database");
            $mysqli->close();
        }
        
        $result = $mysqli->query($query);
        $res = $result->fetch_row();

        $mysqli->close();
        return $res[0]+1;         
        
    }   
    
    public function getPrezzoTotale($ordine){
        $domicilio = 1.5;
        $prezzoParziale = Pizza_ordineFactory::instance()->getPrezzoParziale($ordine->getId());
        if ($ordine->getDomicilio() == "s") return  $prezzoParziale + $domicilio;
        else return $prezzoParziale;
    }
    
    public function getOrdine($id){

        $query = "SELECT * FROM ordini WHERE id = ?";
        
        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[getOrdine] impossibile inizializzare il database");
            $mysqli->close();
            return $ordini;
        }

        $stmt = $mysqli->stmt_init();
        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[getOrdine] impossibile" .
                    " inizializzare il prepared statement");
            $mysqli->close();
            return $ordini;
        }

        if (!$stmt->bind_param('i', $id)) {
            error_log("[getOrdine] impossibile" .
                    " effettuare il binding in input");
            $mysqli->close();
            return $ordini;
        }
        
        $ordine = self::caricaOrdiniDaStmt($stmt);

        $mysqli->close();
        return $ordine;        
        
    }
   

    public function &caricaOrdiniDaStmt(mysqli_stmt $stmt) {
        $ordine = array();
        if (!$stmt->execute()) {
            error_log("[caricaOrdiniDaStmt] impossibile" .
                    " eseguire lo statement");
            return null;
        }

        $row = array();
        $bind = $stmt->bind_result(
                $row['ordine_id'], 
                $row['ordine_domicilio'],
                $row['ordine_prezzo'],
                $row['ordine_stato'], 
                $row['cliente_id'], 
                $row['addettoOrdini_id'],
                $row['orario_id']);

        if (!$bind) {
            error_log("[caricaOrdiniDaStmt] impossibile" .
                    " effettuare il binding in output");
            return null;
        }

        while ($stmt->fetch()) {
            $ordine = self::creaOrdineDaArray($row);
        }

        $stmt->close();

        return $ordine;
    }                
              
    
    public function creaOrdineDaArray($row) {
        $ordine = new Ordine();
        $ordine->setId($row['ordine_id']);
        $ordine->setDomicilio($row['ordine_domicilio']);        
        $ordine->setPrezzo($row['ordine_prezzo']);
        $ordine->setStato($row['ordine_stato']);
        $ordine->setCliente($row['cliente_id']);
        $ordine->setAddettoOrdini($row['addettoOrdini_id']);
        $ordine->setOrario($row['orario_id']);        
        return $ordine;
    }
    
    
        public function &getOrdiniPerIdCliente($user){
        $ordini = array();
        $query = "SELECT * FROM ordini WHERE ordini.cliente_id = ? ";
        
        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[OrdiniForIdCliente] impossibile inizializzare il database");
            $mysqli->close();
            return $ordini;
        }

        $stmt = $mysqli->stmt_init();
        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[OrdiniForIdCliente] impossibile" .
                    " inizializzare il prepared statement");
            $mysqli->close();
            return $ordini;
        }

        if (!$stmt->bind_param('i', $user->getId())) {
            error_log("[OrdiniForIdCliente] impossibile" .
                    " effettuare il binding in input".$user->getId());
            $mysqli->close();
            return $ordini;
        }

        $ordini = self::caricaOrdiniDaStmt($stmt);

        $mysqli->close();
        return $ordini;
    }  
}

?>