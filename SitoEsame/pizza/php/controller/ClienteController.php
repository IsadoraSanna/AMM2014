<?php

include_once 'BaseController.php';
include_once basename(__DIR__) . '/../model/Pizza_ordineFactory.php';
include_once basename(__DIR__) . '/../model/OrarioFactory.php';
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
                        $orari = OrarioFactory::instance()->getOrari();
                        $vd->setSottoPagina('ordina');
                        break;

                    // visualizzazione degli esami sostenuti
                    case 'elenco_ordini':
                        $ordini = OrdineFactory::instance()->getOrdiniPerIdCliente($user);
                        $vd->setSottoPagina('elenco_ordini');
                        break;                    
                    case 'elenco_ordini':
                        
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
                        break;
                        
                    case 'procedi_ordine':
                        // in questo array inserisco i messaggi di 
                        // cio' che non viene validato
                        $vd->setSottoPagina('conferma_ordine');
                        $msg = array();
                        $idPizze = PizzaFactory::instance()->getIdPizze();
                        
                        $nPizze = $this->validaForm($idPizze, $request);
                        $flagOrario = false;
                        
                        $ordine = new Ordine();
                        $ordine->setId(OrdineFactory::instance()->getLastId());
                        $ordineId = $ordine->getId();
                                                                     
                        if($nPizze){
                                
                            $orari = OrarioFactory::instance()->getOrariSuccessivi($request['orario']);  
                            foreach ($orari as $orario) {
                                if((Pizza_ordineFactory::instance()->getNPizzePerOrario($orario->getId())+$nPizze) <= $orario->getOrdiniDisponibili()){
                                    var_dump("Pizze per orario ".Pizza_ordineFactory::instance()->getNPizzePerOrario($orario->getId()));
                                    $ordine->setOrario($orario->getId());
                                    $flagOrario = true;
                                    break;
                                }else $ordine->setOrario(NULL);
                            }
                        }
                        
                        if (!$nPizze){
                            $this->creaFeedbackUtente($msg, $vd, "I valori inseriti non sono validi. Ordine annullato");
                            $vd->setSottoPagina('ordina');                            
                        }
                        else if($flagOrario){
                            
                            OrdineFactory::instance()->nuovoOrdine($ordine);                           

                            foreach($idPizze as $idPizza){
                                $quantita = filter_var($request[$idPizza.'normali'], FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
                                if (isset($quantita)){
                                   Pizza_ordineFactory::instance()->creaPO($idPizza, $ordineId, $quantita, "normale");}
                                $quantita = filter_var($request[$idPizza.'giganti'], FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);    
                                if (isset($quantita)){
                                   Pizza_ordineFactory::instance()->creaPO($idPizza, $ordineId, $quantita, "gigante");}
                            }
                            OrdineFactory::instance()->aggiornaOrdine($user, $ordine, $request['domicilio']);                     
                        } 
                        else {
                            Pizza_ordineFactory::instance()->cancellaPO($ordineId);
                            OrdineFactory::instance()->cancellaOrdine($ordineId);                           
                            $this->creaFeedbackUtente($msg, $vd, "Non è possibile ordinare questo quantitativo di pizze in nessuna fascia oraria odierna");
                            $vd->setSottoPagina('ordina');                            
                        }
                        $this->showHomeUtente($vd);
                        break;
                   
                        
                    case 'dettaglio':
                        $ordineId = filter_var($request['ordine'], FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
                        $ordine = OrdineFactory::instance()->getOrdine($ordineId);
                        $POs = Pizza_ordineFactory::instance()->getPOPerIdOrdine($ordine);
                        $vd->setSottoPagina('dettaglio_ordine');
                        $this->showHomeUtente($vd);
                        break; 
                    
                    case 'conferma_ordine':
                        $msg = array();
                        $ordineId = $request['ordineId'];                        
                        $this->creaFeedbackUtente($msg, $vd, "Ordine ".$ordineId." creato con successo.");
                        $vd->setSottoPagina('home');
                        $this->showHomeUtente($vd);                        
                        break;
                    
                    case 'cancella_ordine':
                        //cancella PO e cancella ordine
                        $msg = array();
                        $ordineId = $request['ordineId'];
                        $p = Pizza_ordineFactory::instance()->cancellaPO($ordineId);
                        $o = OrdineFactory::instance()->cancellaOrdine($ordineId);
                        if ($p && $o) {
                            $this->creaFeedbackUtente($msg, $vd, "Ordine ".$ordineId." cancellato.");
                        }else $this->creaFeedbackUtente($msg, $vd, "Errore cancellazione");
                        $vd->setSottoPagina('home');
                        $this->showHomeUtente($vd);
                        break;
                        
                    // aggiornamento indirizzo
                    case 'indirizzo':

                        // in questo array inserisco i messaggi di 
                        // cio' che non viene validato
                        $msg = array();
                        $this->aggiornaIndirizzo($user, $request, $msg);
                        $this->creaFeedbackUtente($msg, $vd, "Indirizzo aggiornato");
                        $this->showHomeCliente($vd);
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


                    default : $this->showHomeUtente($vd);
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
    
    private function validaForm($idPizze , $request) {
         $valide = 0;
         foreach($idPizze as $idPizza){
            $quantitaN = filter_var($request[$idPizza.'normali'], FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
            if (isset($quantitaN) && ($quantitaN != 0)) $valide+=$quantitaN;
            $quantitaG = filter_var($request[$idPizza.'giganti'], FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
            if (isset($quantitaG) && ($quantitaG != 0)) $valide+=$quantitaG;   
         }
         
         return $valide;
    }

}

?>
