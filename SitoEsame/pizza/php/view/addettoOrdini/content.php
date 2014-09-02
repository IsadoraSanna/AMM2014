<?php
switch ($vd->getSottoPagina()) {
    case 'anagrafica':
        include 'anagrafica.php';
        break;
    
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
        <h2>Pannello di Controllo</h2>


        <table class="content">
            <tr>               
                <td class="noRighe">
                    <h4>Anagrafica</h4>
                    <p><i>Verifica e modifica i dati personali</i></p>
                </td>     
                <td class="noRighe"><a href="addettoOrdini/anagrafica" title="anagrafica">
                    <img src="../images/anagrafica.png" alt="anagrafica"></a></td>                    
                                             
                <td class="noRighe"><a href="addettoOrdini/gestione_ordini" title="gestione_ordini">
                    <img src="../images/gestione.png" alt="gestione ordini"></a>
                </td>  
                <td class="noRighe">
                    <h4>Gestione ordini</h4>
                    <p><i>gestisci gli ordini della giornata</i></p>
                </td>
                </td>               
            </tr>
            
            <tr>
                <td class="noRighe">
                    <h4>Ricerca ordini</h4>
                    <p><i>ricerca gli ordini relativi a date passate</i></p>  
 
               <td class="noRighe"><a href="addettoOrdini/ricerca_ordini" title="ricerca_ordini">
                   <img src="../images/ricerca.png" alt="ricerca ordini"></a>
               </td>
            </tr>
        </table>
        
<?php break; } ?>


