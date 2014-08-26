<h2 class="icon-title">Informazioni</h2>
<p> 
    Benvenuto <?= $user->getNome() ?>.
</p>

<!--Il seguente script ricarica la pagina una sola volta perchè in caso contrario il valore dentro la variabile $_SESSION['pagina']
sarebbe ancora quello della pagina visitata in precedenza-->
<script type="text/javascript">
  window.onload = function() {
    if(!window.location.hash) {
        window.location = window.location + '#loaded';
        window.location.reload();
    }
}
</script>


<?
switch ($_SESSION['pagina']) {
    case 'content.php':?>
        <p>
            Seleziona la voce che ti interessa sul menù.
        </p>
       <?break;
    case 'anagrafica.php':?>
        <p>
            Indirizzo in cui verra consegnata la pizza in caso di consegnaa domicilio.
        </p>
       <?break;    
}


?>