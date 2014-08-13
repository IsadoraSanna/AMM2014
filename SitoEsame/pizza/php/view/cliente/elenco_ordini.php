<h2>Elenco Ordini</h2>
<ul>
    <li><strong>Nome:</strong> <?= $user->getNome() ?></li>
    <li><strong>Cognome:</strong> <?= $user->getCognome() ?></li>
</ul>

<?php if (count($ordini) > 0) { ?>
    <table>
        <thead>
            <tr>
                <th class="esami-col-large">N. Ordine</th>
                <th class="esami-col-small">Domicilio</th>                
                <th class="esami-col-small">Stato</th>
                <th class="esami-col-small">Prezzo</th>
                <th class="esami-col-small">Dettaglio</th>                
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($ordini as $ordine) { ?>
                    <td class="normal"><?= $ordine->getId() ?></td>
                    <? if($ordine->getDomicilio() == "s"){?> <td>si</td> <? } else {?> <td>no</td> <? } ?>                 
                    <td class="normal"><?= $ordine->getStato() ?></td>
                    <td class="normal"><?= $ordine->getPrezzo() ?></td>
                    <td class="normal"><a href="cliente/ordini?cmd=dettaglio&ordine=<?= $ordine->getId() ?>" title="dettaglio_ordine">
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
