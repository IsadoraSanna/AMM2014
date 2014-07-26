<h2>Lista Pizze</h2>

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
    <?

    foreach ($pizze as $pizza) {
        ?>
        <tr>
            <td><?= $pizza->getNome() ?></td>
            <td><?= $pizza->getIngredienti() ?></td>
            <td><input type="number" name=<?= $pizza->getId()."normali" ?> maxlength="2" size="2" min="0" max="10"></td>            
            <td><?= $pizza->getPrezzo() . "€ "?></td>
            <td><input type="number" name=<?= $pizza->getId()."giganti" ?> maxlength="2" size="2" min="0" max="10"></td>
            <td><?= $pizza->getPrezzo()+($pizza->getPrezzo()*30/100) . "€ "?></td>            
        </tr>
    <? } ?>
</table>

    <label for="domicilio">Consegna a domicilio</label>
        <select name="domicilio" id="domicilio">
                <option value="si" >si</option>
                <option value="no" >no</option>
        </select>  
 
    <button type="submit" name="cmd" value="procedi_ordine">Procedi</button>
</div> 
    </form>