<style>

	.well, .error, .warning{
		padding: 5px;
		display: inline-block;
		margin: 20px;
	}

	.well{
		background-color: #7FD97F;
	}
	.error{
		background-color: #E24848;
	}
	.warning{
		background-color: yellow;
	}

	.ereturn{
		margin-top: 30px;
		padding: 5px;
		background-color: plum;
		display: table;
	}
</style>

<?php

$test = "<div >Test di %s :</div>";
$error = "<div class=\"error\">Errore! %s non attivo</div>";
$well = "<div class=\"well\">OK: %s attivo</div>";


// NB: aggiungere controllo su SendMail;
$funcTest = array('curl_init', 'imagecopyresampled');

foreach ($funcTest as $func) {
	printf($test, $func);
	if(is_callable($func)){
		printf($well, $func);
	}else{
		printf($error, $func);
	}
}



$func = 'elgg_send_email';
printf($test, $func );

// testo lungo con spazi
$txt = 
'%s, 

    se hai ricevuto questa mail, vuol dire che il servizio mail sul sito funziona.


Grande soddisfazione!
';

$user = elgg_get_logged_in_user_entity();
$to = $user->email;
echo "<div>Invio mail di prova a $to.</div>";
$mail = elgg_send_email('foowd',$to,'Test', sprintf($txt, 'Foowd'), array());
if($mail){
	$checkMail = true;
	printf($well, $func);
}else{
	printf($error, $func);
	$checkMail = false;
}

if(!$checkMail){
	?>
	<div><div class="warning">ATTENZIONE!!!</div> <br/> Sino a quando l'invio delle mail non sara' abilitato, il sito risultera' ingestibile nella parte concernente registrazioni e messaggi di notifica.</div>
	<div>Per l'utilizzo dell'email devi abilitare la funzione php "mail()" oppure configurare l'invio di email tramite PHPMailer.</div>
	<?php
}

?>
<div class="ereturn">
<a class="elgg-button elgg-button-submit" href="<?php echo $_SERVER['HTTP_REFERER']; ?>">Torna ai Settings</a>
</div>