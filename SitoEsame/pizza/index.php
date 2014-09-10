<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <h1>Progetto "Pizza UniCA" di Isadora Sanna</h1>
        
        <h2> Descrizione dell'applicazione</h2>
        <p>
            Il sito in questione si occupa della gestione degli ordini di una pizzeria.
            Ogni ordine comprende un insieme di pizze (almeno 1) ed è caratterizzato da alcuni elementi quali:
        </p>
        <ul>
            <li>Numero identificativo univoco dell'ordine</li>
            <li>Data in cui l'ordine è stato prenotato</li>
            <li>Prezzo totale</li>
            <li>Stato dell'ordine (utilizzato per identificare gli ordini pagati o meno)</li>
            <li>Richiesta di consegna a domicilio</li>       
        </ul> 
        <p> 
            Ogni ordine si distinguerà dagli altri, oltre che per l'identificativo univoco, anche per: 
       </p>
       <ul>
            <li>La fascia oraria in cui verrà consegnato</li>
            <li>Il cliente che l'ha richiesto</li>
            <li>L'addetto agli ordini al quale è stata assegnata la gestione</li>
        </p>
    
        
        <p>
            Sono presenti due tipi di utente che possono interagire in modo differente con l'interfaccia:
        </p>    
        <p><strong>Il cliente</strong></p>
        <p>
            Il cliente ha la possibilità di eseguire 4 operazioni principali:
        </p>
         <ul>
            <li>Visualizzare e modificare il suo indirizzo o la password</li>
            <li>Eseguire un nuovo ordine</li>
            <li>Visualizzare l'elenco di tutti gli ordini richiesti in passato</li>
            <li>Visualizzare i contatti della pizzeria</li>       
        </ul> 
        <p>
            I dati relativi all'indirizzo dell'utente vengono utilizzati principalmente nel caso in cui questo richieda una consegna a domicilio. 
            Infatti, nel caso in cui la consegna debba avvenire ad un indirizzo diverso da quello gia presente, questo deve essere modificato
            nella sezione "anagrafica".
        </p>
        <p>
            Accedendo alla sezione "Ordina" viene visualizzato l'elenco delle pizze disponibili e i rispettivi prezzi. Dopo
            aver selezionato le pizze, la quantità e la fascia oraria in cui si desidera ritirare l'ordine viene visualizzata
            una schermata di riepilogo che comprende l'elenco delle pizze, l'orario di consegna e prezzo totale.
            L'orario di consegna, se in quello richiesto dall'utente non è possibile inserire quel determinato quantitativo di pizze
            (sono presenti dei limiti di ordini per ogni fascia oraria), sarà impostato con quello disponibile piu vicino.
            A questo putno il cliente decide se accettare o cancellare l'ordine.
        </p>
        <p>
            Le altre sezioni sono autoesplicative.
        </p>
        
        <p><strong>L'addetto agli ordini</strong></p>
        <p>
            L'addetto agli ordini ha la possibilità di eseguire 2 operazioni principali:
        </p>
         <ul>
            <li>Visualizzare gli ordini della giornata e contrassegnarli come pagati</li>
            <li>Ricercare gli ordini per data e ora</li>      
        </ul> 
         <p>
            Tramite la pagina "Gestione ordini" un addetto può visualizzare gli ordini del giorno che devono ancora essere ritirati/consegati
            e pagati. Nel momento in cui questi vengono segnalati come pagati non vengono piu visualizzati nel suddetto elenco mentre è possibile
            visualizzarli nella pagina "Ricerca ordini". Un ordine pagato registra l'ID dell'addetto che l'ha segnalato come pagato.
            In questa pagina è possibile ricercare qualsiasi ordine, qualunque sia il suo stato, tramite la scelta della data e dell'ora.
        </p>       
        
        <h2> Requisiti del progetto </h2>
        <ul>
            <li>Utilizzo di HTML e CSS</li>
            <li>Utilizzo di PHP e MySQL</li>
            <li>Utilizzo del pattern MVC </li>
            <li>Due ruoli (cliente e addettoOrdini)</li>
            <li>Transazione per il salvataggio(aggiornamento) di un nuovo ordine. Visibile all'interno della classe OrdineFactory.php metodi aggiornaOrdine e nuovoOrdine</li>
            <li>Caricamento ajax dei risultati della ricerca degli ordini da parte dell'addetto agli ordini</li>

        </ul>
        
    <h2>Accesso al progetto</h2>
    <p>
        Il codice clonabile del progetto si trova all'indirizzo <a href="php/login">https://github.com/IsadoraSanna/AMM2014</a>
    </p>
    <p>
        La homepage del progetto si trova all'indirizzo <a href="php/login">http://spano.sc.unica.it/amm2014/sannaIsadora/SitoEsame/pizza/php/login</a>
    <p>
    <p>E' possibile eseguire l'accesso al sito utilizzando le seguenti credenziali</p>
    <ul>
        <li>Ruolo Cliente:</li>
        <ul>
            <li>username: isadora</li>
            <li>password: amm</li>
        </ul>
        <li>Ruolo Addetto Ordini:</li>
        <ul>
            <li>username: addetto</li>
            <li>password: amm</li>
        </ul>
    </ul>
</body>
</html>
