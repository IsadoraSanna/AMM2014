<?php

include_once 'BaseController.php';
include_once basename(__DIR__) . '/../model/Pizza_ordineFactory.php';
include_once basename(__DIR__) . '/../model/OrarioFactory.php';
include_once basename(__DIR__) . '/../model/PizzaFactory.php';
include_once basename(__DIR__) . '/../model/OrdineFactory.php';

/**
 * Controller che gestisce la modifica dei dati dell'applicazione relativa ai 
 * Docenti da parte di utenti con ruolo Docente o Amministratore 
 *
 * @author Davide Spano
 */
class AddettoOrdiniController extends BaseController {

    const elenco = 'elenco';

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

                    // inserimento di una lista di appelli
                    case 'gestione_ordini':
                        $ordini = OrdineFactory::instance()->getOrdiniNonPagati();
                        $vd->setSottoPagina('gestione_ordini');
                        break;
                    
                    case 'ricerca_ordini':
                        $orari = OrarioFactory::instance()->getOrari();
                        $date = OrdineFactory::instance()->getDate();
                        $vd->setSottoPagina('ricerca_ordini');
                   
                        $vd->addScript("../js/jquery-2.1.1.min.js");
                        $vd->addScript("../js/ricercaOrdini.js");
                        break;                    
                    
                    case 'filtra_ordini':
                        $vd->toggleJson();
                        $vd->setSottoPagina('ricerca_ordini_json');
                        
                        $errori = array();

                        if (isset($request['mydata']) && ($request['mydata'] != '')) {
                            $data = $request['mydata'];
                        } else {
                            $data = null;
                        }

                        if (isset($request['myora']) && ($request['myora'] != '')) {
                            $ora = $request['myora'];
                        } else {
                            $ora = null;
                        }
                        //var_dump("data ".$data." Ora " .$ora);
                        $ordini = OrdineFactory::instance()->getOrdiniPerDataOra($data, $ora);
                        //i dati si vedono nel js ma non nel controller ne nel json

                        
                        break;

                    default:
                        $vd->setSottoPagina('home');
                        break;
                }
            }


            // gestione dei comandi inviati dall'utente
            if (isset($request["cmd"])) {

                switch ($request["cmd"]) {

                    // logout
                    case 'logout':
                        $this->logout($vd);
                        break;

                    case 'dettaglio':
                        $ordineId = filter_var($request['ordine'], FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
                        $ordine = OrdineFactory::instance()->getOrdine($ordineId);
                        $POs = Pizza_ordineFactory::instance()->getPOPerIdOrdine($ordine);
                        $cliente = UserFactory::instance()->getClientePerId($ordine->getCliente());
                        $vd->setSottoPagina('dettaglio_ordine');
                        $this->showHomeUtente($vd);
                        break; 
                    
                    case 'paga':
                        $msg = array();
                        $ordineId = filter_var($request['ordine'], FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
                        if (OrdineFactory::instance()->setPagato($ordineId)) {
                            $this->creaFeedbackUtente($msg, $vd, "Ordine ".$ordineId." pagato.");
                        }else $this->creaFeedbackUtente($msg, $vd, "Errore cancellazione"); 
                        
                        $vd->setSottoPagina('gestione_ordini');
                        $ordini = OrdineFactory::instance()->getOrdiniNonPagati();
                        $this->showHomeUtente($vd);                        
                        break;
                    
                        
  
                    
                    // modifica della password
                    case 'password':
                        $msg = array();
                        $this->aggiornaPassword($user, $request, $msg);
                        $this->creaFeedbackUtente($msg, $vd, "Password aggiornata");
                        $this->showHomeUtente($vd);
                        break;


                    // default
                    default:
                        $this->showHomeUtente($vd);
                        break;
                }
            } else {
                // nessun comando, dobbiamo semplicemente visualizzare 
                // la vista
                // nessun comando
                $user = UserFactory::instance()->cercaUtentePerId(
                        $_SESSION[BaseController::user], $_SESSION[BaseController::role]);
                $this->showHomeUtente($vd);
            }
        }

        // richiamo la vista
        require basename(__DIR__) . '/../view/master.php';
    }


}

?>
