<h2>Dettaglio ordine n°<?=$ordineId?></h2>

    <table>
        <thead>
            <tr>
                <th class="esami-col-large">N. Ordine</th>
                <th class="esami-col-small">Domicilio</th>                
                <th class="esami-col-small">Stato</th>
                <th class="esami-col-small">Prezzo</th>              
            </tr>
        </thead>
        <tbody>
                    <td><?= $ordine->getId() ?></td>
                    <? if($ordine->getDomicilio() == "s"){?> <td>si</td> <? } else {?> <td>no</td> <? } ?>                 
                    <td><?= $ordine->getStato() ?></td>
                    <td><?= $ordine->getPrezzo() ?></td>
                   
                </tr>
        </tbody>
    </table>

