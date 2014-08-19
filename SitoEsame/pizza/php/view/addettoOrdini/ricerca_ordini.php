<h2>Ricerca Ordini</h2>


<h4>Seleziona i dettagli della ricerca</h4>
<form method="get" action="addettoOrdini/ricerca_ordini">
    
        <label for="mydata">Data</label>
        <select name="mydata" id="mydata">
            <option value="mydata"></option>
            <?php foreach ($date as $data) { ?>
                <option value="<?=substr($data,0,10)?>" ><?=substr($data,0,10)?></option>
            <?php } 

            ?>
                

        </select>

        <br/>
        <label for="myora">Fascia oraria</label>
        <select name="myora" id="myora">
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
            <th>id</th>
            <th>data</th>
            <th>ora</th>
            <th>cliente</th>
            <th>prezzo</th>
        </tr>
    </thead>
    <tbody>
        
    </tbody>
</table>