<?php

include_once 'User.php';
include_once 'AddettoOrdini.php';
include_once 'Cliente.php';
include_once 'Db.php';


/**
 * Classe per la creazione degli utenti del sistema
 *
 * @author Davide Spano
 */
class UserFactory {

    private static $singleton;

    private function __constructor() {
        
    }

    /**
     * Restiuisce un singleton per creare utenti
     * @return \UserFactory
     */
    public static function instance() {
        if (!isset(self::$singleton)) {
            self::$singleton = new UserFactory();
        }

        return self::$singleton;
    }

    /**
     * Carica un utente tramite username e password
     * @param string $username
     * @param string $password
     * @return \User|\AddettoOrdini|\Cliente
     */
    public function caricaUtente($username, $password) {


        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[loadUser] impossibile inizializzare il database");
            $mysqli->close();
            return null;
        }

        // cerco prima nella tabella clienti
        $query = "SELECT 
            clienti.id cliente_id,
            clienti.nome cliente_nome,
            clienti.cognome cliente_cognome,
            clienti.via cliente_via,
            clienti.civico cliente_civico,               
            clienti.citta cliente_citta,
            clienti.cap cliente_cap,
            clienti.telefono cliente_telefono,
            clienti.username cliente_username,
            clienti.password cliente_password,
            FROM `clienti` WHERE  `username` =  ? AND  `password` =  ?";
        $stmt = $mysqli->stmt_init();
        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[loadUser] impossibile" .
                    " inizializzare il prepared statement");
            $mysqli->close();
            return null;
        }

        if (!$stmt->bind_param('ss', $username, $password)) {
            error_log("[loadUser] impossibile" .
                    " effettuare il binding in input ".$username. " ".$password);
            $mysqli->close();
            return null;
        }

        $cliente = self::caricaClienteDaStmt($stmt);
        if (isset($cliente)) {
            // ho trovato uno studente
            $mysqli->close();
            return $cliente;
        }

        // ora cerco un docente
        $query = "select
               id as addettoOrdini_id,
               nome as addettoOrdini_nome,
               cognome as addettoOrdini_cognome,
               via as addettoOrdini_via,
               civico as addettoOrdini_civico,               
               citta as addettoOrdini_citta,
               cap as addettoOrdini_cap,
               telefono as addettoOrdini_telefono,
               username as addettoOrdini_username,
               password as addettoOrdini_password,            
               from pizzeria.addettoOrdini where username = ? and password = ?";

        $stmt = $mysqli->stmt_init();
        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[loadUser] impossibile" .
                    " inizializzare il prepared statement");
            $mysqli->close();
            return null;
        }

        if (!$stmt->bind_param('ss', $username, $password)) {
            error_log("[loadUser] impossibile" .
                    " effettuare il binding in input");
            $mysqli->close();
            return null;
        }

        $addettoOrdini = self::caricaAddettoOrdiniDaStmt($stmt);
        if (isset($addettoOrdini)) {
            // ho trovato un docente
            $mysqli->close();
            return $addettoOrdini;
        }
    }

    /**
     * Restituisce un array con i Docenti presenti nel sistema
     * @return array
     */
    public function &getListaClienti() {
        $clienti = array();
        $query = "select * from pizzeria.clienti";
        
        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[getListaClienti] impossibile inizializzare il database");
            $mysqli->close();
            return $clienti;
        }
        $result = $mysqli->query($query);
        if ($mysqli->errno > 0) {
            error_log("[getListaClienti] impossibile eseguire la query");
            $mysqli->close();
            return $clienti;
        }

        while ($row = $result->fetch_array()) {
            $clienti[] = self::creaClienteDaArray($row);
        }
        //togliere?
        $mysqli->close();
        return $clienti;
    }

    /**
     * Restituisce la lista degli clienti presenti nel sistema
     * @return array
     */
    public function &getListaAddettoOrdini() {
        $addettoOrdini = array();
        $query = "select * from pizzeria.addettoOrdini ";
        
        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[getListaAddettoOrdini] impossibile inizializzare il database");
            $mysqli->close();
            return $addettoOrdini;
        }
        $result = $mysqli->query($query);
        if ($mysqli->errno > 0) {
            error_log("[getListaAddettoOrdini] impossibile eseguire la query");
            $mysqli->close();
            return $addettoOrdini;
        }

        while ($row = $result->fetch_array()) {
            $addettoOrdini[] = self::creaAddettoOrdiniDaArray($row);
        }

        return $addettoOrdini;
    }


    /**
     * Crea uno studente da una riga del db
     * @param type $row
     * @return \Cliente
     */
    public function creaClienteDaArray($row) {
        $cliente = new Cliente();
        $cliente->setId($row['cliente_id']); 
        $cliente->setNome($row['cliente_nome']);    
        $cliente->setCognome($row['cliente_cognome']);
        $cliente->setVia($row['cliente_via']);
        $cliente->setCivico($row['cliente_civico']);
        $cliente->setCitta($row['cliente_citta']);                  
        $cliente->setCap($row['cliente_cap']);
        $cliente->setTelefono($row['cliente_telefono']);        
        $cliente->setRuolo(User::Cliente);
        $cliente->setUsername($row['cliente_username']);
        $cliente->setPassword($row['cliente_password']);

        return $cliente;
    }

    /**
     * Crea un docente da una riga del db
     * @param type $row
     * @return \AddettoOrdini
     */
    public function creaAddettoOrdiniDaArray($row) {
        $addettoOrdini = new AddettoOrdini();
        $addettoOrdini->setId($row['addettoOrdini_id']);
        $addettoOrdini->setNome($row['addettoOrdini_nome']);
        $addettoOrdini->setCognome($row['addettoOrdini_cognome']);
        $addettoOrdini->setVia($row['addettoOrdini_via']);
        $addettoOrdini->setCivico($row['addettoOrdini_civico']);
        $addettoOrdini->setCitta($row['addettoOrdini_citta']);                  
        $addettoOrdini->setCap($row['addettoOrdini_cap']);
        $addettoOrdini->setTelefono($row['addettoOrdini_telefono']);
        $addettoOrdini->setRuolo(User::AddettoOrdini);
        $addettoOrdini->setUsername($row['addettoOrdini_username']);
        $addettoOrdini->setPassword($row['addettoOrdini_password']);

        return $addettoOrdini;
    }

    /**
     * Salva i dati relativi ad un utente sul db
     * @param User $user
     * @return il numero di righe modificate
     */
    public function salva(User $user) {
        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[salva] impossibile inizializzare il database");
            $mysqli->close();
            return 0;
        }

        $stmt = $mysqli->stmt_init();
        $count = 0;
        switch ($user->getRuolo()) {
            case User::Cliente:
                $count = $this->salvaCliente($user, $stmt);
                break;
            case User::AddettoOrdini:
                $count = $this->salvaAddettoOrdini($user, $stmt);
        }

        $stmt->close();
        $mysqli->close();
        return $count;
    }

    /**
     * Rende persistenti le modifiche all'anagrafica di uno studente sul db
     * @param Cliente $s lo studente considerato
     * @param mysqli_stmt $stmt un prepared statement
     * @return int il numero di righe modificate
     */
    private function salvaCliente(Cliente $c, mysqli_stmt $stmt) {
        $query = " update clienti set 
                    password = ?,
                    nome = ?,
                    cognome = ?,
                    via = ?,
                    civico = ?,
                    citta = ?,
                    cap = ?,
                    telefono = ?,
                    where clienti.id = ?
                    ";
        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[salvaCliente] impossibile" .
                    " inizializzare il prepared statement");
            return 0;
        }

        if (!$stmt->bind_param('ssssisiii',
                $s->getPassword(),
                $s->getNome(),
                $s->getCognome(),
                $d->getVia(), 
                $d->getCivico(),
                $d->getCitta(),
                $d->getCap(),
                $d->getTelefono(),
                $s->getId())) {
            error_log("[salvaCliente] impossibile" .
                    " effettuare il binding in input 2");
            return 0;
        }

        if (!$stmt->execute()) {
            error_log("[caricaIscritti] impossibile" .
                    " eseguire lo statement");
            return 0;
        }

        return $stmt->affected_rows;
    }
    
    /**
     * Rende persistenti le modifiche all'anagrafica di un docente sul db
     * @param AddettoOrdini $d il docente considerato
     * @param mysqli_stmt $stmt un prepared statement
     * @return int il numero di righe modificate
     */
    private function salvaAddettoOrdini(AddettoOrdini $d, mysqli_stmt $stmt) {
        $query = " update addettoOrdini set 
                    password = ?,
                    nome = ?,
                    cognome = ?,
                    via = ?,
                    civico = ?,
                    citta = ?,
                    cap = ?,
                    telefono = ?,
                    where addettoOrdini.id = ?
                    ";
        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[salvaCliente] impossibile" .
                    " inizializzare il prepared statement");
            return 0;
        }

        if (!$stmt->bind_param('ssssisiii', 
                $d->getPassword(), 
                $d->getNome(), 
                $d->getCognome(), 
                $d->getVia(), 
                $d->getCivico(),
                $d->getCitta(),
                $d->getCap(),
                $d->getTelefono(),
                $d->getId())) {
            error_log("[salvaCliente] impossibile" .
                    " effettuare il binding in input");
            return 0;
        }

        if (!$stmt->execute()) {
            error_log("[caricaIscritti] impossibile" .
                    " eseguire lo statement");
            return 0;
        }

        return $stmt->affected_rows;
    }

    /**
     * Carica un docente eseguendo un prepared statement
     * @param mysqli_stmt $stmt
     * @return null
     */
    private function caricaAddettoOrdiniDaStmt(mysqli_stmt $stmt) {

        if (!$stmt->execute()) {
            error_log("[caricaAddettoOrdiniDaStmt] impossibile" .
                    " eseguire lo statement");
            return null;
        }

        $row = array();
        $bind = $stmt->bind_result(
                $row['addettoOrdini_id'], 
                $row['addettoOrdini_nome'], 
                $row['addettoOrdini_cognome'], 
                $row['addettoOrdini_citta'],
                $row['addettoOrdini_cap'],
                $row['addettoOrdini_via'],
                $row['addettoOrdini_civico'],
                $row['addettoOrdini_username'], 
                $row['addettoOrdini_password'], 
                $row['dipartimenti_id']);
        if (!$bind) {
            error_log("[caricaAddettoOrdiniDaStmt] impossibile" .
                    " effettuare il binding in output");
            return null;
        }

        if (!$stmt->fetch()) {
            return null;
        }

        $stmt->close();

        return self::creaAddettoOrdiniDaArray($row);
    }

    /**
     * Carica uno studente eseguendo un prepared statement
     * @param mysqli_stmt $stmt
     * @return null
     */
    private function caricaClienteDaStmt(mysqli_stmt $stmt) {

        if (!$stmt->execute()) {
            error_log("[caricaClienteDaStmt] impossibile" .
                    " eseguire lo statement");
            return null;
        }

        $row = array();
        $bind = $stmt->bind_result(
                $row['cliente_id'], 
                $row['cliente_nome'], 
                $row['cliente_cognome'], 
                $row['cliente_citta'],
                $row['cliente_cap'],
                $row['cliente_via'],
                $row['cliente_civico'],
                $row['cliente_username'], 
                $row['cliente_password']);
        if (!$bind) {
            error_log("[caricaClienteDaStmt] impossibile" .
                    " effettuare il binding in output");
            return null;
        }

        if (!$stmt->fetch()) {
            return null;
        }

        $stmt->close();

        return self::creaClienteDaArray($row);
    }
    
    /**
     * Cerca uno studente per id
     * @param int $id
     * @return Cliente un oggetto Cliente nel caso sia stato trovato,
     * NULL altrimenti
     */
    public function cercaUtentePerId($id, $role) {
        $intval = filter_var($id, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
        if (!isset($intval)) {
            return null;
        }
        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[cercaUtentePerId] impossibile inizializzare il database");
            $mysqli->close();
            return null;
        }

        switch ($role) {
            case User::Cliente:
                $query = "select * from clienti where id = ?";
                $stmt = $mysqli->stmt_init();
                $stmt->prepare($query);
                if (!$stmt) {
                    error_log("[cercaUtentePerId] impossibile" .
                            " inizializzare il prepared statement");
                    $mysqli->close();
                    return null;
                }

                if (!$stmt->bind_param('i', $intval)) {
                    error_log("[cercaUtentePerId] impossibile" .
                            " effettuare il binding in input");
                    $mysqli->close();
                    return null;
                }

                return self::caricaClienteDaStmt($stmt);
                break;

            case User::AddettoOrdini:
                $query = "select * from addettoOrdini where id = ?";

                $stmt = $mysqli->stmt_init();
                $stmt->prepare($query);
                if (!$stmt) {
                    error_log("[cercaUtentePerId] impossibile" .
                            " inizializzare il prepared statement");
                    $mysqli->close();
                    return null;
                }

                if (!$stmt->bind_param('i', $intval)) {
                    error_log("[loadUser] impossibile" .
                            " effettuare il binding in input");
                    $mysqli->close();
                    return null;
                }

                $toRet =  self::caricaAddettoOrdiniDaStmt($stmt);
                $mysqli->close();
                return $toRet;
                break;

            default: return null;
        }
    }

}

?>
