<?php
switch ($vd->getSottoPagina()) {
    case 'anagrafica':
        include_once 'anagrafica.php';       
        break;

    case 'ordina':
        include_once 'ordina.php';      
        break;   
    case 'elenco_ordini':
        include_once 'elenco_ordini.php';       
        break;
    
    case 'contatti':
        include_once 'contatti.php';        
        break;
    
    case 'dettaglio_ordine':
       include_once 'dettaglio_ordine.php';         
       break;

    case 'conferma_ordine':
       include_once 'conferma_ordine.php';
       break;
   
   
    default:
        
        ?>
        <h2>Pannello di Controllo</h2>
        
        <table class="content">
            <tr>     
                <td class="noRighe">
                    <h4>Anagrafica</h4>
                    <p><i>Verifica e modifica i dati personali</i></p>
                </td>     
                <td class="noRighe"><a href="cliente/anagrafica" title="anagrafica">
                <img src="../images/anagrafica.png" alt="anagrafica"></a></td> 
                
                <td class="noRighe"><a href="cliente/ordina" title="ordina">
                <img src="../images/gestione.png" alt="ordina"></a></td>
                <td class="noRighe">
                    <h4>Ordina</h4>
                    <p><i>Crea un nuovo ordine</i></p>
                </td>                
            </tr>                 
            <tr>
                <td class="noRighe">
                    <h4>Elenco ordini</h4>
                    <p><i>Visualizza tutti gli ordini effettuati</i></p>                   
                </td> 
                <td class="noRighe"><a href="cliente/elenco_ordini" title="elenco_ordini">
                <img src="../images/ricerca.png" alt="elenco ordini"></a></td>
                
                <td class="noRighe"><a href="cliente/contatti" title="contatti">
                <img src="../images/contatti.png" alt="contatti"></a></td>        
                <td class="noRighe">
                    <h4>Contatti</h4>
                    <p><i>I nostri riferimenti</i></p>
                </td>                
            </tr>           
        </table>       
        
<?php break; } ?>


