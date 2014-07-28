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
   
    default:
        
        ?>
        <h2 class="icon-title" id="h-home">Pannello di Controllo</h2>
        <p>
            Benvenuto, <?= $user->getNome() ?>
        </p>
        <p>
            Scegli una fra le seguenti sezioni:
        </p>
        <ul class="panel">
            <li><a href="cliente/anagrafica" id="pnl-anagrafica">Anagrafica</a></li>
            <li><a href="cliente/ordina" id="pnl-libretto">Ordina</a></li>
            <li><a href="cliente/elenco_ordini" id="pnl-libretto">Elenco Ordini</a></li>            
            <li><a href="cliente/contatti" id="pnl-iscrizione">Contatti</a></li>
        </ul>
        <?php
        break;
}
?>


