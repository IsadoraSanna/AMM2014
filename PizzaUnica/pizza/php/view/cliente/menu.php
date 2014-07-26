<ul>
    <li class="<?= strpos($vd->getSottoPagina(),'home') !== false || $vd->getSottoPagina() == null ? 'current_page_item' : ''?>"><a href="cliente/home">Home</a></li>
    <li class="<?= strpos($vd->getSottoPagina(),'anagrafica') !== false ? 'current_page_item' : '' ?>"><a href="cliente/anagrafica">Anagrafica</a></li>
    <li class="<?= strpos($vd->getSottoPagina(),'ordina') !== false ? 'current_page_item' : '' ?>"><a href="cliente/ordina">Ordina</a></li>
    <li class="<?= strpos($vd->getSottoPagina(),'elenco_ordini') !== false ? 'current_page_item' : '' ?>"><a href="cliente/elenco_ordini">Elenco ordini</a></li>
    <li class="<?= strpos($vd->getSottoPagina(),'contatti') !== false ? 'current_page_item' : '' ?>"><a href="cliente/contatti>">Contatti</a></li>
</ul>