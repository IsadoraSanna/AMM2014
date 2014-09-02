<h2>Gestione ordini del <?= date('d-m-Y');?></h2>
<?php if (count($ordini) > 0) { ?>
    <table>
        <tr>
            <th>Ordine</th>
            <th>Nome</th>    
            <th>Cognome</th>
            <th>Domicilio</th>         
            <th>Indirizzo</th>
            <th>Prezzo</th>      
            <th>Paga</th>
            <th>Dettaglio</th>         
        </tr>

       <?foreach ($ordini as $ordine) {
           $cliente = UserFactory::instance()->getClientePerId($ordine->getCliente());
            ?>
            <tr>
                <td class="col-small"><?= $ordine->getId() ?></td>
                <td class="col-large"><?= $cliente->getNome() ?></td>
                <td class="col-large"><?= $cliente->getCognome() ?></td>           
                <td class="col-small"><?= $ordine->getDomicilio() ?></td>
                <td class="col-large"><?= $cliente->getVia() ?> <?= $cliente->getCivico() ?> <?= $cliente->getCap() ?> <?= $cliente->getCitta() ?></td>
                <td class="col-small"><?= OrdineFactory::instance()->getPrezzoTotale($ordine) . "€ " ?></td>      
                <td class="col-small"><a href="addettoOrdini/ordini?cmd=paga&ordine=<?= $ordine->getId() ?>" title="paga">
                <img src="../images/paga.png" alt="paga"></a></td> 
                <td class="col-small"><a href="addettoOrdini/ordini?cmd=dettaglio&ordine=<?= $ordine->getId() ?>" title="dettaglio_ordine">
                <img src="../images/dettaglio.png" alt="dettaglio ordine"></a></td>              
            </tr>
        <? } ?>    

    </table>

<?php } else { ?>
    <p> Non è presente alcun ordine per la data odierna</p>
<?php } ?>