<h2>Lista Pizze</h2>

<form action="cliente/conferma_ordine" method="post">
<table>
    <tr>
        <th>Nome</th>
        <th>Ingredienti</th>
        <th>Prezzo</th>     
        <th>Quantità</th> 
    </tr>

    <?

    foreach ($pizze as $pizza) {
        ?>
        <tr>
            <td><?= $pizza->getNome() ?></td>
            <td><?= $pizza->getIngredienti() ?></td>
            <td><?= $pizza->getPrezzo() . " € "?></td>
            <td><input type="number" name=<?= $pizza->getId() ?> maxlength="2" size="2" min="0" max="10"></td>
        </tr>
    <? } ?>
</table>
    <label for="domicilio">Consegna a domicilio</label>
        <select name="domicilio" id="domicilio">
                <option value="si" >si</option>
                <option value="no" >no</option>
        </select>  
    <label for="indirizzoConsegna">Indirizzo consegna</label>
    <textarea rows="4" cols="20" name="indirizzoConsegna" id="indirizzoConsegna"></textarea>
    
    <button type="submit" name="cmd" value="procedi_ordine">Procedi</button>
    </form>