<ul>
    <li class="<?= strpos($vd->getSottoPagina(),'home') !== false || $vd->getSottoPagina() == null ? 'current_page_item' : ''?>"><a href="addettoOrdini/home">Home</a></li>
    <li class="riga"></li>
    <li class="<?= strpos($vd->getSottoPagina(),'anagrafica') !== false ? 'current_page_item' : '' ?>"><a href="addettoOrdini/anagrafica">Anagrafica</a></li>
    <li class="riga"></li>    
    <li class="<?= strpos($vd->getSottoPagina(),'gestione_ordini') !== false ? 'current_page_item' : '' ?>"><a href="addettoOrdini/gestione_ordini">Gestione ordini</a></li>
    <li class="riga"></li>
    <li class="<?= strpos($vd->getSottoPagina(),'ricerca_ordini') !== false ? 'current_page_item' : '' ?>"><a href="addettoOrdini/ricerca_ordini">Ricerca ordini</a></li>
</ul>