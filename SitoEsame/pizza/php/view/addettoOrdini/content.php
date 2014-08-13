<?php
switch ($vd->getSottoPagina()) {
    case 'gestione_ordini':
        include 'gestione_ordini.php';
        break;
    
    case 'ricerca_ordini':
        include 'ricerca_ordini.php';
        break;  
    
    case 'dettaglio_ordine':
        include 'dettaglio_ordine.php';
        break; 
    
    case 'ricerca_ordini_json':
        include_once 'ricerca_ordini_json.php';
        break;      
        ?>
        

    <?php default: ?>
        <h2 class="icon-title" id="h-home">Pannello di Controllo</h2>


        <table class="pControllo">
            <tr>
                <td class="noRighe"></td>
                <td class="noRighe">
                    <h4>Gestione ordini</h4>
                    <p><i>gestisci gli ordini della giornata</i></p>
                </td>
                <td class="noRighe"><a href="addettoOrdini/gestione_ordini" title="gestione_ordini">
                <img src="../images/gestione.png" alt="gestione ordini"></a></td>                                               
            </tr>     
            <tr>
                <td class="noRighe"><a href="addettoOrdini/ricerca_ordini" title="ricerca_ordini">
                <img src="../images/ricerca.png" alt="ricerca ordini"></a></td>
                <td class="noRighe">
                    <h4>Ricerca ordini</h4>
                    <p><i>ricerca gli ordini relativi a date passate</i></p>                   
                </td>               
            </tr>             
        </table>
        
<?php break; } ?>


