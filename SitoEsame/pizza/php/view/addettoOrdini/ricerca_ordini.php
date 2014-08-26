<h2>Ricerca Ordini</h2>


<h4>Seleziona i dettagli della ricerca</h4>
<form method="get" action="addettoOrdini/ricerca_ordini">
    
        <label for="myData">Data</label>
        <select name="myData" id="myData">
            <option value="myData"></option>
            <?php foreach ($date as $data) { ?>
                <option value="<?=substr($data,0,10)?>" ><?=substr($data,0,10)?></option>
            <?php } 

            ?>
                

        </select>

        <br/>
        <label for="myOra">Fascia oraria</label>
        <select name="myOra" id="myOra">
            <option value=""></option>
            <?php foreach ($orari as $orario) { ?>
                <option value="<?= $orario->getId() ?>" ><?= $orario->getFasciaOraria() ?></option>
            <?php } ?>
        </select>
        <button id="filtra" type="submit" name="cmd">Cerca</button>
    </form>

<h2>Risultati</h2>

<p id="nessuno">Nessun ordine trovato</p>


<table id="tabella_ordini">
    <thead>
        <tr>
            <th>Id ordine</th>
            <th>Cliente</th>
            <th>Id cliente</th>
            <th>Stato</th>
            <th>Prezzo</th>
        </tr>
    </thead>
    <tbody>
        
    </tbody>
</table>