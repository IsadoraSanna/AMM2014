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
                <!--<th class="esami-col-small">Pizza</th>                
                <th class="esami-col-small">Quantità</th>
                <th class="esami-col-small">Ora</th>-->
                <th class="esami-col-small">Domicilio</th>                
                <th class="esami-col-small">Stato</th>
                <!--<th class="esami-col-small">Prezzo</th>-->
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($ordini as $ordine) { ?>
                    <td><?= $ordine->getId() ?></td>
                    <td><?= $ordine->getDomicilio() ?></td>                    
                    <td><?= $ordine->getStato() ?></td>
                    <td><?= $ordine->getPrezzo() ?></td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
<?php } else { ?>
    <p> Non è presente alcun ordine </p>
<?php } ?>
