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
                            if ($data == null) {
                                $errori['mydata'] = "Specificare una data valida";
                            }
                        } else {
                            $data = null;
                        }

                        if (isset($request['myora']) && ($request['myora'] != '')) {
                            $ora = filter_var($request['myora'], FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
                            if ($ora == null) {
                                $errori['myora'] = "Specificare un orario valido";
                            }
                        } else {
                            $ora = null;
                        }
                        //var_dump("data".$request['mydata']." Ora " .$request['myora']);
                        $ordini = OrdineFactory::instance()->ricercaPerDataOra($data, $ora);

                        
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

                    // modifica delle informazioni di contatto
                    case 'contatti':
                        $msg = array();
                        if (isset($request['ricevimento'])) {
                            if (!$user->setRicevimento($request['ricevimento'])) {
                                $msg[] = '<li>Il ricevimento specificato non &egrave; corretto</li>';
                            }
                        }
                        $this->aggiornaEmail($user, $request, $msg);

                        $this->creaFeedbackUtente($msg, $vd, "Contatti aggiornati");
                        $this->showHomeAddettoOrdini($vd);
                        break;
                        
                    case 'dettaglio':
                        $ordineId = filter_var($request['ordine'], FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
                        $ordine = OrdineFactory::instance()->getOrdine($ordineId);
                        $POs = Pizza_ordineFactory::instance()->getPOPerIdOrdine($ordine);
                        $cliente = UserFactory::instance()->getClientePerId($ordine->getCliente());
                        $vd->setSottoPagina('dettaglio_ordine');
                        $this->showHomeAddettoOrdini($vd);
                        break; 
                    
                    case 'paga':
                        $msg = array();
                        $ordineId = filter_var($request['ordine'], FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
                        if (OrdineFactory::instance()->setPagato($ordineId)) {
                            $this->creaFeedbackUtente($msg, $vd, "Ordine ".$ordineId." pagato.");
                        }else $this->creaFeedbackUtente($msg, $vd, "Errore cancellazione"); 
                        
                        $vd->setSottoPagina('gestione_ordini');
                        $ordini = OrdineFactory::instance()->getOrdiniNonPagati();
                        $this->showHomeAddettoOrdini($vd);                        
                        break;
                    
                        
  
                    
                    // modifica della password
                    case 'password':
                        $msg = array();
                        $this->aggiornaPassword($user, $request, $msg);
                        $this->creaFeedbackUtente($msg, $vd, "Password aggiornata");
                        $this->showHomeAddettoOrdini($vd);
                        break;

                    // richiesta modifica di un appello esistente,
                    // dobbiamo mostrare le informazioni
                    case 'a_modifica':
                        $appelli = AppelloFactory::instance()->getAppelliPerDocente($user);
                        if (isset($request['appello'])) {
                            $intVal = filter_var($request['appello'], FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
                            if (isset($intVal)) {
                                $mod_appello = $this->cercaAppelloPerId($intVal, $appelli);
                                $insegnamenti = InsegnamentoFactory::instance()->getListaInsegnamentiPerDocente($user);
                                //$vd->setStato('a_modifica');
                            }
                        }
                        $this->showHomeAddettoOrdini($vd);
                        break;

                    // salvataggio delle modifiche ad un appello esistente
                    case 'a_salva':
                        $msg = array();
                        if (isset($request['appello'])) {
                            $intVal = filter_var($request['appello'], FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
                            if (isset($intVal)) {
                                $mod_appello = $this->cercaAppelloPerId($intVal, $appelli);
                                $this->updateAppello($mod_appello, $request, $msg);
                                if (count($msg) == 0 && AppelloFactory::instance()->salva($mod_appello) != 1) {
                                    $msg[] = '<li> Impossibile salvare l\'appello </li>';
                                }
                                $this->creaFeedbackUtente($msg, $vd, "Appello aggiornato");
                                if (count($msg) == 0) {
                                    $vd->setSottoPagina('appelli');
                                }
                            }
                        } else {
                            $msg[] = '<li> Appello non specificato </li>';
                        }
                        $this->showHomeAddettoOrdini($vd);
                        break;

                    // l'utente non vuole modificare l'appello selezionato
                    case 'a_annulla':
                        $vd->setSottoPagina('appelli');
                        $this->showHomeAddettoOrdini($vd);
                        break;

                    // richesta di visualizzazione del form per la creazione di un nuovo
                    // appello
                    case 'a_crea':
                        $appelli = AppelloFactory::instance()->getAppelliPerDocente($user);
                        $vd->setSottoPagina('appelli_crea');
                        $this->showHomeAddettoOrdini($vd);
                        break;

                    // creazione di un nuovo appello
                    case 'a_nuovo':
                        $msg = array();
                        $nuovo = new Appello();
                        $nuovo->setId(-1);
                        $this->updateAppello($nuovo, $request, $msg);
                        $this->creaFeedbackUtente($msg, $vd, "Appello creato");
                        if (count($msg) == 0) {
                            $vd->setSottoPagina('appelli');
                            if (AppelloFactory::instance()->nuovo($nuovo) != 1) {
                                $msg[] = '<li> Impossibile creare l\'appello </li>';
                            }
                        }
                        $appelli = AppelloFactory::instance()->getAppelliPerDocente($user);
                        $this->showHomeAddettoOrdini($vd);
                        break;

                    // mostra la lista degli iscritti
                    case 'a_iscritti':
                        $appelli = AppelloFactory::instance()->getAppelliPerDocente($user);
                        if (isset($request['appello'])) {
                            $intVal = filter_var($request['appello'], FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
                            if (isset($intVal)) {
                                $mod_appello = $this->cercaAppelloPerId($intVal, $appelli);
                            }
                        }
                        $this->showHomeAddettoOrdini($vd);
                        break;

                    // cancella un appello
                    case 'a_cancella':
                        if (isset($request['appello'])) {
                            $intVal = filter_var($request['appello'], FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
                            if (isset($intVal)) {
                                $mod_appello = AppelloFactory::instance()->cercaAppelloPerId($intVal);
                                if ($mod_appello != null) {
                                    if (AppelloFactory::instance()->cancella($mod_appello) != 1) {
                                        $msg[] = '<li> Impossibile cancellare l\'appello </li>';
                                    }
                                }

                                $this->creaFeedbackUtente($msg, $vd, "Appello eliminato");
                            }
                        }
                        $appelli = AppelloFactory::instance()->getAppelliPerDocente($user);
                        $this->showHomeAddettoOrdini($vd);
                        break;

                    // richiesta di creazione di un nuovo elenco di esami
                    case 'r_nuovo':
                        $elenco_id = $this->prossimoIndiceElencoListe($_SESSION[self::elenco]);
                        // salviamo gli oggetti interi in sessione
                        $el = new ElencoEsami($elenco_id);
                        $el->getTemplate()->setData(new DateTime());
                        $_SESSION[self::elenco][$elenco_id] = $el;
                        $elenchi_attivi = $_SESSION[self::elenco];

                        $this->showHomeAddettoOrdini($vd);
                        break;

                    // selezione dell'insegnamento
                    case 'r_sel_insegnamento':
                        if (isset($elenco_id)) {
                            $commissione = $_SESSION[self::elenco][$elenco_id]->getTemplate()->getCommissione();
                            // richiesta di andare al passo successivo
                            if (!isset($request['insegnamento'])) {
                                $msg[] = "<li> Non &egrave; stato selezionato un insegnamento</li>";
                            } else {
                                $insegnamento = InsegnamentoFactory::instance()->creaInsegnamentoDaCodice($request['insegnamento']);
                                if (!isset($insegnamento)) {
                                    $msg[] = "<li> L'insegnamento specificato non &egrave; corretto</li>";
                                }
                            }
                            if (count($msg) == 0) {
                                // nessun errore, impostiamo l'insegnamento
                                $_SESSION[self::elenco][$elenco_id]->getTemplate()->setInsegnamento($insegnamento);
                                $sel_insegnamento = $_SESSION[self::elenco][$elenco_id]->getTemplate()->getInsegnamento();
                                $vd->setSottoPagina('reg_esami_step2');
                            } else {
                                $vd->setSottoPagina('reg_esami_step1');
                            }
                            $this->creaFeedbackUtente($msg, $vd, "Insegnamento selezionato");
                        }
                        $this->showHomeAddettoOrdini($vd);
                        break;

                    // aggiunta di un membro della  commissione
                    case 'r_add_commissione':
                        if (isset($elenco_id)) {
                            // richiesta di aggiungere un nuovo membro
                            $index = filter_var($request['nuovo-membro'], FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
                            if (isset($index) &&
                                    ($new_docente = UserFactory::instance()->cercaUtentePerId($index, User::Docente)) != null) {
                                // docente trovato
                                // aggiungiamo il docente alla lista
                                if (!$_SESSION[self::elenco][$elenco_id]->getTemplate()->aggiungiMembroCommissione($new_docente)) {
                                    $msg[] = '<li>Il docente specificato &egrave; gi&agrave; in lista </li>';
                                } else {
                                    // copiamo la nuova commissione nella variabile della vista
                                    $commissione = $_SESSION[self::elenco][$elenco_id]->getTemplate()->getCommissione();
                                }
                            } else {
                                // docente non trovato
                                $msg[] = '<li>Impossibile trovare il  docente specificato </li>';
                            }
                            $this->creaFeedbackUtente($msg, $vd, "Membro aggiunto in commissione");
                        }
                        $this->showHomeAddettoOrdini($vd);
                        break;


                    // rimozione di un membro della commissione
                    case 'r_del_commissione':
                        if (isset($elenco_id)) {
                            $index = filter_var($request['index'], FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
                            if (isset($index) && $index >= 0 && $index < count($commissione)) {
                                $old_docente = $_SESSION[self::elenco][$elenco_id]->getTemplate()->getCommissione()[$index];
                                if (!$_SESSION[self::elenco][$elenco_id]->getTemplate()->rimuoviMembroCommissione($old_docente)) {
                                    $msg[] = '<li>Il docente specificato non &egrave; in lista </li>';
                                } else {
                                    // copiamo la nuova commissione nella variabile della vista
                                    $commissione = $_SESSION[self::elenco][$elenco_id]->getTemplate()->getCommissione();
                                }
                            } else {
                                $msg[] = '<li>Impossibile trovare il membro specificato </li>';
                            }
                        }
                        $this->creaFeedbackUtente($msg, $vd, "Membro rimosso dalla commissione");
                        $this->showHomeAddettoOrdini($vd);
                        break;


                    // salvataggio della commissione per l'elenco
                    case 'r_save_commissione':
                        if (isset($elenco_id)) {
                            if (!$_SESSION[self::elenco][$elenco_id]->getTemplate()->commissioneValida()) {
                                $msg[] = '<li>Ci devono essere almeno due membri in commissione</li>';
                            }
                            $this->creaFeedbackUtente($msg, $vd, "Commissione inserita correttamente");
                            if (count($msg) > 0) {
                                $vd->setSottoPagina('reg_esami_step2');
                            }
                        }
                        $this->showHomeAddettoOrdini($vd);
                        break;

                    // aggiunta di uno statino
                    case 'r_add_esame':
                        if (isset($elenco_id)) {
                            $new_esame = new Esame();
                            $new_esame->setInsegnamento($_SESSION[self::elenco][$elenco_id]->getTemplate()->getInsegnamento());
                            $new_esame->setCommissione($_SESSION[self::elenco][$elenco_id]->getTemplate()->getCommissione());
                            // aggiungiamo un esame alla lista
                            if (isset($request['matricola'])) {
                                $studente = UserFactory::instance()->cercaStudentePerMatricola($request['matricola']);
                                if (!isset($studente)) {
                                    $msg[] = '<li>La matricola specificata non &egrave; associata ad uno studente</li>';
                                } else {
                                    // impostiamo lo studente
                                    $new_esame->setStudente($studente);
                                }
                            } else {
                                $msg[] = '<li>Specificare una matricola</li>';
                            }

                            if (isset($request['voto'])) {
                                if (!$new_esame->setVoto($request['voto'])) {
                                    $msg[] = '<li>Il voto specificato non &egrave; corretto</li>';
                                }
                            } else {
                                $msg[] = '<li>Specificare un voto </li>';
                            }

                            if (count($msg) == 0
                                    && !$_SESSION[self::elenco][$elenco_id]->aggiungiEsame($new_esame)) {
                                // esame duplicato
                                $msg[] = '<li>Lo statino specificato &egrave; gi&agrave; presente in elenco </li>';
                            } else {
                                // facciamo una copia aggiornata dell'elenco esami per la vista
                                $sel_esami = $_SESSION[self::elenco][$elenco_id]->getEsami();
                            }
                            $this->creaFeedbackUtente($msg, $vd, "Statino inserito in elenco");
                        }
                        $this->showHomeAddettoOrdini($vd);
                        break;

                    // rimozione di uno statino
                    case 'r_del_esame':
                        if (isset($elenco_id)) {
                            $index = filter_var($request['index'], FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
                            if (isset($index) && $index >= 0 && $index < count($sel_esami)) {
                                $old_statino = $_SESSION[self::elenco][$elenco_id]->getEsami()[$index];
                                if (!$_SESSION[self::elenco][$elenco_id]->rimuoviEsame($old_statino)) {
                                    $msg[] = '<li>L\'esame specificato non &egrave; in lista </li>';
                                } else {
                                    // facciamo una copia aggiornata dell'elenco esami per la vista
                                    $sel_esami = $_SESSION[self::elenco][$elenco_id]->getEsami();
                                }
                            } else {
                                $msg[] = '<li>Impossibile trovare lo statino specificato </li>';
                            }
                            $this->creaFeedbackUtente($msg, $vd, "Statino eliminato correttamente");
                        }
                        $this->showHomeAddettoOrdini($vd);
                        break;

                    // salvataggio permanente dell'elenco
                    case 'r_salva_elenco':
                        if (isset($elenco_id)) {
                            if (count($_SESSION[self::elenco][$elenco_id]->getEsami()) > 0) {
                                if (!EsameFactory::instance()->salvaElenco($_SESSION[self::elenco][$elenco_id])) {
                                    $msg[] = '<li> Impossibile salvare l\'elenco</li>';
                                } else {
                                    unset($_SESSION[self::elenco][$elenco_id]);
                                    $elenchi_attivi = $_SESSION[self::elenco];
                                    $vd->setPagina("reg_esami");
                                    $vd->setSottoPagina('reg_esami');
                                }
                            } else {
                                $msg[] = '<li> &Egrave; necessario inserire almeno un esame</li>';
                            }
                            $this->creaFeedbackUtente($msg, $vd, "Esami registrati correttamente");
                        }
                        $this->showHomeAddettoOrdini($vd);
                        break;

                    // cancellazione di un elenco
                    case 'r_del_elenco':
                        if (isset($elenco_id) && array_key_exists($elenco_id, $_SESSION[self::elenco])) {
                            unset($_SESSION[self::elenco][$elenco_id]);
                            $this->creaFeedbackUtente($msg, $vd, "Elenco cancellato");
                            $elenchi_attivi = $_SESSION[self::elenco];
                        }
                        $this->showHomeAddettoOrdini($vd);
                        break;

                    // ricerca di un esame
                    case 'e_cerca':
                        $msg = array();
                        $this->creaFeedbackUtente($msg, $vd, "Lo implementiamo con il db, fai conto che abbia funzionato ;)");
                        $this->showHomeAddettoOrdini($vd);
                        break;

                    // default
                    default:
                        $this->showHomeAddettoOrdini($vd);
                        break;
                }
            } else {
                // nessun comando, dobbiamo semplicemente visualizzare 
                // la vista
                // nessun comando
                $user = UserFactory::instance()->cercaUtentePerId(
                        $_SESSION[BaseController::user], $_SESSION[BaseController::role]);
                $this->showHomeAddettoOrdini($vd);
            }
        }


        // richiamo la vista
        require basename(__DIR__) . '/../view/master.php';
    }

    /**
     * Aggiorna i dati relativi ad un appello in base ai parametri specificati
     * dall'utente
     * @param Appello $mod_appello l'appello da modificare
     * @param array $request la richiesta da gestire 
     * @param array $msg array dove inserire eventuali messaggi d'errore
     */
    private function updateAppello($mod_appello, &$request, &$msg) {
        if (isset($request['insegnamento'])) {
            $insegnamento = InsegnamentoFactory::instance()->creaInsegnamentoDaCodice($request['insegnamento']);
            if (isset($insegnamento)) {
                $mod_appello->setInsegnamento($insegnamento);
            } else {
                $msg[] = "<li>Insegnamento non trovato</li>";
            }
        }
        if (isset($request['data'])) {
            $data = DateTime::createFromFormat("d/m/Y", $request['data']);
            if (isset($data) && $data != false) {
                $mod_appello->setData($data);
            } else {
                $msg[] = "<li>La data specificata non &egrave; corretta</li>";
            }
        }
        if (isset($request['posti'])) {
            if (!$mod_appello->setCapienza($request['posti'])) {
                $msg[] = "<li>La capienza specificata non &egrave; corretta</li>";
            }
        }
    }

    /**
     * Ricerca un apperllo per id all'interno di una lista
     * @param int $id l'id da cercare
     * @param array $appelli un array di appelli
     * @return Appello l'appello con l'id specificato se presente nella lista,
     * null altrimenti
     */
    private function cercaAppelloPerId($id, &$appelli) {
        foreach ($appelli as $appello) {
            if ($appello->getId() == $id) {
                return $appello;
            }
        }

        return null;
    }

    /**
     * Calcola l'id per un nuovo appello
     * @param array $appelli una lista di appelli
     * @return int il prossimo id degli appelli
     */
    private function prossimoIdAppelli(&$appelli) {
        $max = -1;
        foreach ($appelli as $a) {
            if ($a->getId() > $max) {
                $max = $a->getId();
            }
        }
        return $max + 1;
    }

    /**
     * Restituisce il prossimo id per gli elenchi degli esami
     * @param array $elenco un elenco di esami
     * @return int il prossimo identificatore
     */
    private function prossimoIndiceElencoListe(&$elenco) {
        if (!isset($elenco)) {
            return 0;
        }

        if (count($elenco) == 0) {
            return 0;
        }

        return max(array_keys($elenco)) + 1;
    }

    /**
     * Restituisce l'identificatore dell'elenco specificato in una richiesta HTTP
     * @param array $request la richiesta HTTP
     * @param array $msg un array per inserire eventuali messaggi d'errore
     * @return l'identificatore dell'elenco selezionato
     */
    private function getIdElenco(&$request, &$msg) {
        if (!isset($request['elenco'])) {
            $msg[] = "<li> Non &egrave; stato selezionato un elenco</li>";
        } else {
            // recuperiamo l'elenco dalla sessione
            $elenco_id = filter_var($request['elenco'], FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
            if (!isset($elenco_id) || !array_key_exists($elenco_id, $_SESSION[self::elenco])
                    || $elenco_id < 0) {
                $msg[] = "L'elenco selezionato non &egrave; corretto</li>";
                return null;
            }
            return $elenco_id;
        }
        return null;
    }

    /**
     * Restituisce l'appello specificato dall'utente tramite una richiesta HTTP
     * @param array $request la richiesta HTTP
     * @param array $msg un array dove inserire eventuali messaggi d'errore
     * @return Appello l'appello selezionato, null se non e' stato trovato
     */
    private function getAppello(&$request, &$msg) {
        if (isset($request['appello'])) {
            $appello_id = filter_var($request['appello'], FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
            $appello = AppelloFactory::instance()->cercaAppelloPerId($appello_id);
            if ($appello == null) {
                $msg[] = "L'appello selezionato non &egrave; corretto</li>";
            }
            return $appello;
        } else {
            return null;
        }
    }

}

?>
