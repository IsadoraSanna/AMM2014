<h2>Dettaglio ordine n°<?=$ordine->getId()?> del <?=substr($ordine->getData(),0,10)?></h2>

    <table>

            <tr>
                <th >Pizza</th>
                <th>Dimensione</th>                
                <th>Quantita</th>
                <th>Prezzo</th>      
                <th>Prezzo TOT</th>                 
            </tr>     

    <?foreach ($POs as $PO) {
            $pizza = PizzaFactory::instance()->getPizzaPerId($PO->getPizza());?>
            <tr>
                <td><?= $pizza->getNome()?></td>
                <td><?= $PO->getDimensione() ?></td>
                <td><?= $PO->getQuantita() ?></td>                
                <td><?= (Pizza_ordineFactory::instance()->getPrezzoPerPizze($PO)/$PO->getQuantita()) . "€ "?></td>
                <td><?= Pizza_ordineFactory::instance()->getPrezzoPerPizze($PO) . "€ "?></td>                               
                   
            </tr>
    <? } ?>    
             <tr>
                <th>Fascia oraria*</th>                  
                <th>Domicilio</th>
                <th>Prezzo Domicilio</th>                
                <th>Prezzo Pizze</th>
                <th>Prezzo Totale</th>                     
            </tr>       
            <tr>
                <td><?= OrdineFactory::instance()->getValoreOrario($ordine->getOrario()) ?></td>           
                <td><? if($ordine->getDomicilio() == "si"){?>si<? } else {?>no<? } ?></td>            
                <td><? if($ordine->getDomicilio() == "si"){?>1.5€<? } else {?>0€<? } ?></td>
                <td><?= Pizza_ordineFactory::instance()->getPrezzoParziale($ordine) . "€ "?></td>                 
                <td><?= OrdineFactory::instance()->getPrezzoTotale($ordine) . "€ "?></td>                 
            </tr>
    </table>


