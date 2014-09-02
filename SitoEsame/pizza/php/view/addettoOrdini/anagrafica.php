<div>
    <h2>Dati personali</h2>
<ul>
    <li><strong>Codice dipendente:</strong> <?= $user->getId() ?></li>
    <li><strong>Nome:</strong> <?= $user->getNome() ?></li>
    <li><strong>Cognome:</strong> <?= $user->getCognome() ?></li>
</ul>
</div>

<div>
    <h3>Indirizzo</h3>
    <ul>

        <li><strong>Via: </strong><?= $user->getVia() ?> <?= $user->getCivico() ?></li>
        <li><strong>CAP: </strong><?= $user->getCap() ?></li>       
        <li><strong>Città: </strong><?= $user->getCitta() ?></li>       
        <li><strong>Telefono: </strong><?= $user->getTelefono() ?></li>       
    </ul>
</div>

