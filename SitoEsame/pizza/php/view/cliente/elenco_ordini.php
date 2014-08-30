<h2>Elenco Ordini</h2>


<?php if (count($ordini) > 0) { ?>
    <table>
        <thead>
            <tr>
                <th>N. Ordine</th>
                <th>Data</th>                
                <th>Stato</th>
                <th>Prezzo</th>
                <th>Dettaglio</th>                
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($ordini as $ordine) { ?>
                    <td class="col-small"><?= $ordine->getId() ?></td>
                    <td class="col-small"><?= substr($ordine->getData(),0,10)?>              
                    <td class="col-small"><?= $ordine->getStato() ?></td>
                    <td class="col-small"><?= round($ordine->getPrezzo(),2) . "€"?></td>
                    <td class="col-small"><a href="cliente/ordini?cmd=dettaglio&ordine=<?= $ordine->getId() ?>" title="dettaglio_ordine">
                    <img src="../images/dettaglio.png" alt="dettaglio ordine"></a></td>                    
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
<?php } else { ?>
    <p> Non è presente alcun ordine </p>
<?php } ?>
