<h2>Dettaglio ordine n°<?=$ordine->getId()?></h2>

<h4>Dati cliente</h4>
<ul>
    <li><strong>Nome:</strong> <?= $cliente->getNome() ?></li>
    <li><strong>Cognome:</strong> <?= $cliente->getCognome() ?></li>
    <li><strong>Telefono:</strong> <?= $cliente->getTelefono() ?></li>
    <li><strong>Indirizzo:</strong> via <?= $cliente->getVia() ?> <?= $cliente->getCivico() ?> <?= $cliente->getCap() ?> <?= $cliente->getCitta() ?></li>
</ul>
    <table>

            <tr>
                <th class="esami-col-large">Pizza</th>
                <th class="esami-col-small">Dimensione</th>                
                <th class="esami-col-small">Quantita</th>
                <th class="esami-col-small">Prezzo</th>      
                <th class="esami-col-small">Prezzo TOT</th>                 
            </tr>     

    <?foreach ($POs as $PO) {
            $pizza = PizzaFactory::instance()->getPizzaPerId($PO->getPizza());?>
            <tr>
                <td><?= $pizza->getNome()?></td>
                <td><?= $PO->getDimensione() ?></td>
                <td><?= $PO->getQuantita() ?></td>                
                <td><?= $pizza->getPrezzo() ?></td>
                <td><?= Pizza_ordineFactory::instance()->getPrezzoSingolo($PO) ?></td>                               
                   
            </tr>
    <? } ?>    
             <tr>
                <th class="esami-col-large">Fascia oraria*</th>                  
                <th class="esami-col-large">Domicilio</th>
                <th class="esami-col-small">Prezzo Domicilio</th>                
                <th class="esami-col-small">Prezzo Pizze</th>
                <th class="esami-col-small">Prezzo Totale</th>                     
            </tr>       
            <tr>
                <td><?= OrdineFactory::instance()->getValoreOrario($ordine->getOrario()) ?></td>           
                <td><? if($ordine->getDomicilio() == "s"){?> <td>si</td> <? } else {?> <td>no</td> <? } ?></td>            
                <td><? if($ordine->getDomicilio() == "s"){?> <td>1.5</td> <? } else {?> <td>0</td> <? } ?></td>
                <td><?= Pizza_ordineFactory::instance()->getPrezzoParziale($ordine) ?></td>                 
                <td><?= OrdineFactory::instance()->getPrezzoTotale($ordine) ?></td>                 
            </tr>
    </table>
