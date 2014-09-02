<?php

$json = array();
$json['errori'] = $errori;
$json['ordini'] = array();
foreach($ordini as $ordine){
    $element = array();
    $element['id'] = $ordine->getId();
    $element['cliente'] = UserFactory::instance()->getClientePerId($ordine->getCliente())->getNome()  . " " . UserFactory::instance()->getClientePerId($ordine->getCliente())->getCognome();    
    $element['idCliente'] = UserFactory::instance()->getClientePerId($ordine->getCliente())->getId();
    $element['stato'] = $ordine->getStato();
    $element['prezzo'] = OrdineFactory::instance()->getPrezzoTotale($ordine) . "€ ";

    $json['ordini'][] = $element;    

    
}
echo json_encode($json);
?>