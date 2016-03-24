

<?php
/**
 * Account settings form used for user settings
 *
 * This form is extended by Elgg with the views in core/settings/account.
 * Plugins can additionally extend it and then register for the
 * 'usersettings:save', 'user' plugin hook.
 *
 * This view is included by "forms/usersettings/save"
 */

/**
 * Manipolo a mio piacimento:
 */

elgg_gatekeeper();

// l'owner della pagina e' l'utente che sto modificando
$owner = elgg_get_page_owner_entity();

$me = elgg_get_logged_in_user_entity();

//////////////////////////////////////////////////////////////// Dati Foowd
$form = 'foowd-dati';
unset($_SESSION['sticky_forms'][$form]);


$guid = $owner->guid;

$f = new \Foowd\Action\UserSave(/*$form*/);

// prendo i valori del vecchio post e li carico nel form
$data['type']='search';
$data['ExternalId'] = $guid;

$r = \Uoowd\API::Request('user','POST', $data);
// \Fprint::r($r);

// se sono qui la validazione lato elgg e' andata bene
// ma ora controllo quella lato API remote

if($r->response){
	// var_dump($r);
	// dico al sistema di scartare gli input di questo form
	// elgg_clear_sticky_form('foowd_offerte/add');
	$input = (array) $r->body;
	$input['MinOrderPrice'] = (isset($input['GroupConstraint']->minPrice)) ? $input['GroupConstraint']->minPrice : 0;

	// salvo nello sticky form tutti i dati ritornati dalla API
	$f->manageSticky($input, $form);
}else{
	$_SESSION['sticky_forms'][$form]['apiError']=$r;
	register_error(elgg_echo('Non riesco a caricare i dati'));
}
$vrs = $f->prepare_form_vars($form);
// gli amministratori possono modificare alcuni campi che in caso contrario rimangono bloccati
$vrs['isAdmin'] = $me->isAdmin();
$vrs['username'] = $owner->username;

// controlli relativi a eventuali cambi di mail
$s = new \Foowd\Action\FoowdUpdateUser();
$par = $s->emailExpiration;
if($owner->{$par}){
	// se e' passato troppo tempo il nuovo cambio mail viene resettato senza dire nulla
	if(time() > $owner->{$par}){
		$owner->{$par} = '';
		$owner->{$s->emailToSetMetadata} = '';
		$owner->save();
	}else{
		$vrs['emailToSet'] = $owner->{$s->emailToSetMetadata};
	}
}


echo elgg_view('foowd_account/foowd_user_settings', $vrs);


////////////////////////////////////////////////// Extra per gli amministratori: 

// inserisco un campo di controllo: serve per gli handler associati allo user update: solo se e' l'amministratore a svolgere modifiche
echo elgg_view('input/hidden', array('name' => 'foowd_user_settings_update', 'value' => true));
echo elgg_view('input/hidden', array('name' => 'foowd_user_to_update_guid', 'value' => $owner->guid));

// hook per javascript in modo da personalizzare alcune impostazioni se necessario
$adminJS = ($vrs['isAdmin'])  ? 'amministratore' : 'nada';
echo elgg_view('input/hidden', array('name' => 'js_admin', 'value' => $adminJS));

// tramite javascript rimuovo i campi che non voglio modificare
?>

