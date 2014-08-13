<?php

$json = array();
$json['errori'] = $errori;
$json['ordini'] = array();
foreach($ordini as $ordine){

    $element = array();
    $element['id'] = $ordine->getId();
    $element['data'] = $ordine->getData();
    $element['ora'] = $ordine->getOrario();
    $element['cliente'] = UserFactory::instance()->getClientePerId($ordine->getCliente())->getNome()  . " " . UserFactory::instance()->getClientePerId($ordine->getCliente())->getCognome();
    $element['prezzo'] = $ordine->getPrezzo();

    $json['ordini'][] = $element;    

    
}
echo json_encode($json);
?>