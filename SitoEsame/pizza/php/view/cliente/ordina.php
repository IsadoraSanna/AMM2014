<h2>Lista Pizze</h2>

<div class="input-form">

<table>
    <tr>
        <th>Nome</th>
        <th>Ingredienti</th>    
        <th>Normali</th>
        <th>Prezzo</th>         
        <th>Giganti</th>
        <th>Prezzo</th>         
    </tr>
    
<form action="cliente/ordina" method="post">
    

    <?foreach ($pizze as $pizza) {
        ?>
        <tr>
            <td class="esami-col-small"><?= $pizza->getNome() ?></td>
            <td class="esami-col-large"><?= $pizza->getIngredienti() ?></td>
            <td class="esami-col-small"><input type="number" name=<?= $pizza->getId()."normali" ?> maxlength="2" size="2" min="0" max="10"></td>            
            <td class="esami-col-small"><?= $pizza->getPrezzo() . "€ "?></td>
            <td class="esami-col-small"><input type="number" name=<?= $pizza->getId()."giganti" ?> maxlength="2" size="2" min="0" max="10"></td>
            <td class="esami-col-small"><?= $pizza->getPrezzo()+($pizza->getPrezzo()*30/100) . "€ "?></td>            
        </tr>
    <? } ?>
</table>

    <label for="domicilio">Consegna a domicilio*</label>
        <select name="domicilio" id="domicilio">
                <option value="si" >si</option>
                <option value="no" >no</option>
        </select>  
    <label for="orario">Fascia oraria</label>
        <select name="orario" id="orario">
    <?foreach ($orari as $orario) {
               $ora = substr($orario, 0, 5);
               $oraAttuale = date("H:i");
               if($ora < $oraAttuale){?> 
                
                <option value="<?= $orario->getId() ?>" ><?= $orario->getFasciaOraria() ?></option>
    <? } } ?>                
    </select>     
    <button type="submit" name="cmd" value="procedi_ordine">Procedi</button>
    
</div> 
    
