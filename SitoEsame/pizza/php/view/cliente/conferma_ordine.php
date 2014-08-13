<h2>Riepilogo Ordine</h2>
<form action="cliente/conferma_ordine" method="post">
    
 <?    
    $ordine = OrdineFactory::instance()->getOrdine($ordineId);
    $POs = Pizza_ordineFactory::instance()->getPOPerIdOrdine($ordine);
    
    if($ordine->getDomicilio() == "s") $domicilioSi = true;
    else $domicilioSi = false;
    

?>  
    <input type="hidden" name="ordineId" value="<?= $ordine->getId() ?>" />
    <table>

            <tr>
                <th class="esami-col-small">Pizza</th>
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
                <?if ($domicilioSi) {?> <td><?= $pizza->getPrezzo() ?></td>
                <?}else {?> <td><?= $pizza->getPrezzo()+($pizza->getPrezzo()*30/100) . "€ ";}?></td>   
                <td><?= Pizza_ordineFactory::instance()->getPrezzoSingolo($PO) ?></td>                               
                   
            </tr>
    <? } ?>    
             <tr>
                <th class="esami-col-small">Fascia oraria</th> 
                <th class="esami-col-small">Domicilio</th>
                <th class="esami-col-small">Prezzo Domicilio</th>                
                <th class="esami-col-small">Prezzo Pizze</th>
                <th class="esami-col-small">Prezzo Totale</th>                     
            </tr>       
            <tr>
                <td><?= OrdineFactory::instance()->getValoreOrario($ordine->getOrario()) ?></td>
                <td><? if($domicilioSi){?>si<? } else {?>no<? } ?></td>            
                <td><? if($domicilioSi){?>1.5<? } else {?>0<? } ?></td>
                <td><?= Pizza_ordineFactory::instance()->getPrezzoParziale($ordine) ?></td>                 
                <td><?= OrdineFactory::instance()->getPrezzoTotale($ordine) ?></td>                 
            </tr>
    </table>        
    <button type="submit" name="cmd" value="conferma_ordine">Conferma</button>
    <button type="submit" name="cmd" value="cancella_ordine">Cancella</button>
    </form>