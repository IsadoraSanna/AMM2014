<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <h1>Progetto "pasticceria" Isadora Sanna</h1>
        
        <h2> Descrizione dell'applicazione </h2>
        <p>
            L’applicazione si occupa della gestione delle prenotazioni di una pasticceria. 
            Un cliente puo' effettuare delle ordinazioni scegliendo i dolci che gli interessano
            e le rispettive quantità. I dolci possono essere scelti da quelli attualmente presenti in 
            pasticceria oppure, in caso di esigenze particolari, ordinarli per i giorni successivi.
            Ogni pietanza si distingue per:
        </p>


        <p>Inoltre,  studente &egrave; in grado di visualizzare 
            il suo libretto direttamente su web.
            Un esame &egrave; associato ad un insegnamento &egrave;, formato da:
        </p>
        <ul>
            <li>Un titolo</li>
            <li>Un codice</li>
            <li>Un Corso di Laurea di afferenza</li>
            <li>Un numero di crediti</li>
        </ul>

        <p>L’applicazione mantiene una anagrafica dei professori e degli studenti, in particolare:</p>

        <ul>
            <li>Nome e Cognome</li>
            <li>Indirizzo</li>
            <li>Email</li>
        </ul>

        <p>
            Per i professori, si mantiene anche il Dipartimento di afferenza, 
            mentre per gli studenti si mantiene il Corso di Laurea, 
            che a sua volta afferisce ad un Dipartimento. </p>

        <p>
            Inoltre,  l’applicazione 
            fornisce istruzioni dettagliate sulla modalit&agrave; di inserimento dei 
            dati personali (che può essere fatto direttamente da ogni utente) 
            e sulla visualizzazione del libretto per gli studenti
            e delle 
            liste degli esami registrati per i professori, con funzione di ricerca e filtraggio. 
        </p>
        <p>
            L’applicazione gestisce  la prenotazione degli esami da parte degli studenti: 
            il docente inserisce una data ed un numero di studenti che possono sostenere l’esame.
            Lo studente si connette e si iscrive nel caso ci siano ancora posti. </p>

        <h2> Requisiti del progetto </h2>
        <ul>
            <li>Utilizzo di HTML e CSS</li>
            <li>Utilizzo di PHP e MySQL</li>
            <li>Utilizzo del pattern MVC </li>
            <li>Due ruoli (studente e docente)</li>
            <li>Transazione per la registrazione degli esami (metodo salvaElenco della classe EsameFactory.php)</li>
            <li>Caricamento ajax dei risultati della ricerca degli esami (ruolo docente)</li>

        </ul>
    </ul>

    <h2>Accesso al progetto</h2>
    <p>
        La homepage del progetto si trova sulla URL <a href="php/login">http://localhost/SitoMioEsame/php/login</a>
    <p>
    <p>Si pu&ograve; accedere alla applicazione con le seguenti credenziali</p>
    <ul>
        <li>Ruolo docente:</li>
        <ul>
            <li>username: docente</li>
            <li>password: spano</li>
        </ul>
        <li>Ruolo studente:</li>
        <ul>
            <li>username: studente</li>
            <li>password: spano</li>
        </ul>
    </ul>
</body>
</html>
