<?php

include_once 'BaseController.php';
include_once basename(__DIR__) . '/../model/Pizza_ordineFactory.php';
include_once basename(__DIR__) . '/../model/PizzaFactory.php';
include_once basename(__DIR__) . '/../model/OrdineFactory.php';
/**
 * Controller che gestisce la modifica dei dati dell'applicazione relativa agli 
 * Studenti da parte di utenti con ruolo Studente o Amministratore 
 *
 * @author Davide Spano
 */
class ClienteController extends BaseController {

    
    /**
     * Costruttore
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Metodo per gestire l'input dell'utente. 
     * @param type $request la richiesta da gestire
     */
    public function handleInput(&$request) {

        // creo il descrittore della vista
        $vd = new ViewDescriptor();


        // imposto la pagina
        $vd->setPagina($request['page']);

        // gestion dei comandi
        // tutte le variabili che vengono create senza essere utilizzate 
        // direttamente in questo switch, sono quelle che vengono poi lette
        // dalla vista, ed utilizzano le classi del modello

        if (!$this->loggedIn()) {
            // utente non autenticato, rimando alla home

            $this->showLoginPage($vd);
        } else {
            // utente autenticato
            $user = UserFactory::instance()->cercaUtentePerId(
                            $_SESSION[BaseController::user], $_SESSION[BaseController::role]);


            // verifico quale sia la sottopagina della categoria
            // Docente da servire ed imposto il descrittore 
            // della vista per caricare i "pezzi" delle pagine corretti
            // tutte le variabili che vengono create senza essere utilizzate 
            // direttamente in questo switch, sono quelle che vengono poi lette
            // dalla vista, ed utilizzano le classi del modello
            if (isset($request["subpage"])) {
                switch ($request["subpage"]) {

                    // modifica dei dati anagrafici
                    case 'anagrafica':
                        $vd->setSottoPagina('anagrafica');
                        break;

                    // visualizzazione degli esami sostenuti
                    case 'ordina':
                        $pizze = PizzaFactory::instance()->getPizze();
                        $vd->setSottoPagina('ordina');
                        break;

                    // visualizzazione degli esami sostenuti
                    case 'elenco_ordini':
                        $ordini = OrdineFactory::instance()->getOrdiniPerIdCliente($user);
                        $vd->setSottoPagina('elenco_ordini');
                        break;                    

                    // iscrizione ad un appello
                    case 'contatti':
                        $vd->setSottoPagina('contatti');
                        break;
                    default:

                        $vd->setSottoPagina('home');
                        break;
                }
            }



            // gestione dei comandi inviati dall'utente
            if (isset($request["cmd"])) {
                // abbiamo ricevuto un comando
                switch ($request["cmd"]) {

                    // logout
                    case 'logout':
                        $this->logout($vd);
 
                    case 'procedi_ordine':
                        // in questo array inserisco i messaggi di 
                        // cio' che non viene validato
                        $vd->setSottoPagina('ordina');
                        $msg = array();
                        $ordine = new Ordine();
                        $ordine->setId(OrdineFactory::instance()->getLastId());
                        OrdineFactory::instance()->nuovoOrdine($ordine);

                        $ordineId = $ordine->getId();
                        $idPizze = PizzaFactory::instance()->getIdPizze();
                        
                        if(!isset($idPizze)){error_log("pizzeId non inizializzata");}
                        
                        foreach($idPizze as $idPizza){
                            $quantita = filter_var($request[$idPizza.'normali'], FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
                            if (isset($quantita)){
                               Pizza_ordineFactory::instance()->creaPO($idPizza, $ordineId, $quantita, "normale");}
                            else{
                                error_log("[pizza non settata".$idPizza);}
                            $quantita = filter_var($request[$idPizza.'giganti'], FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
                            if (isset($quantita)){
                               Pizza_ordineFactory::instance()->creaPO($idPizza, $ordineId, $quantita, "gigante");}
                            else{
                                error_log("[pizza non settata".$idPizza);}                                
                        } 
                        OrdineFactory::instance()->aggiornaOrdine($ordine, $request['domicilio']);
                        $ordine = OrdineFactory::instance()->getOrdine($ordineId);
                        $nPizze = Pizza_ordineFactory::instance()->getNPizze($ordineId);
                        $prezzo = OrdineFactory::instance()->getPrezzoTotale($ordine);
                        //fare if per vedere se è a domicilio o no
                        if ($ordine->getDomicilio() == "s"){
                        $this->creaFeedbackUtente($msg, $vd, "Ordine creato! Costo : ".$prezzo.
                                " Numero pizze: ".$nPizze." a domicilio all'indirizzo: ".$user->getCognome().
                                "nella fascia oraria: ".$ordine->getOrario());
                        
                                }
                        else {
                         $this->creaFeedbackUtente($msg, $vd, "Ordine creato! Costo : ".$prezzo.
                                " Numero pizze: ".$nPizze."pronte per la fascia oraria: ".$ordine->getOrario());                           
                        }        
                        $this->showHomeUtente($vd);
                        break;
                        
                    case 'dettaglio':
                        $ordineId = filter_var($request['ordine'], FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
                        $ordine = OrdineFactory::instance()->getOrdine($ordineId);
                        $vd->setSottoPagina('dettaglio_ordine');
                        $this->showHomeCliente($vd);
                        break;             
                        
                    // aggiornamento indirizzo
                    case 'indirizzo':

                        // in questo array inserisco i messaggi di 
                        // cio' che non viene validato
                        $msg = array();
                        $this->aggiornaIndirizzo($user, $request, $msg);
                        $this->creaFeedbackUtente($msg, $vd, "Indirizzo aggiornato");
                        $this->showHomeUtente($vd);
                        break;


                    // cambio password
                    case 'password':
                        // in questo array inserisco i messaggi di 
                        // cio' che non viene validato
                        $msg = array();
                        $this->aggiornaPassword($user, $request, $msg);
                        $this->creaFeedbackUtente($msg, $vd, "Password aggiornata");
                        $this->showHomeCliente($vd);
                        break;

                    // iscrizione ad un appello
                    case 'iscrivi':
                        // recuperiamo l'indice 
                        $msg = array();
                        $a = $this->getAppelloPerIndice($appelli, $request, $msg);
                        if (isset($a)) {
                            $isOk = $a->iscrivi($user);
                            $count = AppelloFactory::instance()->aggiungiIscrizione($user, $a);
                            if (!$isOk || $count != 1) {
                                $msg[] = "<li> Impossibile cancellarti dall'appello specificato </li>";
                            }
                        } else {
                            $msg[] = "<li> Impossibile iscriverti all'appello specificato. Verifica la capienza del corso </li>";
                        }

                        $this->creaFeedbackUtente($msg, $vd, "Ti sei iscritto all'appello specificato");
                        $this->showHomeStudente($vd);
                        break;

                    // cancellazione da un appello
                    case 'cancella':
                        // recuperiamo l'indice 
                        $msg = array();
                        $a = $this->getAppelloPerIndice($appelli, $request, $msg);

                        if (isset($a)) {
                            $isOk = $a->cancella($user);
                            $count = AppelloFactory::instance()->cancellaIscrizione($user, $a);
                            if (!$isOk || $count != 1) {
                                $msg[] = "<li> Impossibile cancellarti dall'appello specificato </li>";
                            }
                        } else {
                            $msg[] = "<li> Impossibile cancellarti dall'appello specificato </li>";
                        }
                        $this->creaFeedbackUtente($msg, $vd, "Ti sei cancellato dall'appello specificato");
                        $this->showHomeUtente($vd);
                        break;
                    default : $this->showLoginPage($vd);
                }
            } else {
                // nessun comando
                $user = UserFactory::instance()->cercaUtentePerId(
                                $_SESSION[BaseController::user], $_SESSION[BaseController::role]);
                $this->showHomeUtente($vd);
            }
        }

        // includo la vista
        require basename(__DIR__) . '/../view/master.php';
    }

}

?>
