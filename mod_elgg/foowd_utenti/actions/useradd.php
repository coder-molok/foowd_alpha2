<?php

\Uoowd\Logger::addDebug('Registrazione lato Admin');

// inserisco la useradd di default
//require(elgg_get_plugins_path().'../actions/useradd.php');
//



//\Uoowd\Param::logger(elgg_get_sticky_values('Genre'));

// Parte del codice e' presa da <progetto elgg>/actions/useradd.php

elgg_make_sticky_form('useradd');

// Get variables
$username = get_input('username');
$password = get_input('password', null, false);
$password2 = get_input('password2', null, false);
$email = get_input('email');
$name = get_input('name');

$admin = get_input('admin');
if (is_array($admin)) {
	$admin = $admin[0];
}

// no blank fields
if ($username == '' || $password == '' || $password2 == '' || $email == '' || $name == '') {
	register_error(elgg_echo('register:fields'));
	forward(REFERER);
}

if (strcmp($password, $password2) != 0) {
	register_error(elgg_echo('RegistrationException:PasswordMismatch'));
	forward(REFERER);
}

// For now, just try and register the user
try {
	$guid = register_user($username, $password, $name, $email, TRUE);

	if ($guid) {
		$new_user = get_entity($guid);
		if ($new_user && $admin && elgg_is_admin_logged_in()) {
			$new_user->makeAdmin();
		}

		//----- Lines added by Simone Scardoni:
		$usr = new \Foowd\User();
		$usr->form = 'useradd';
		$par['user'] = $new_user;
		if(! $usr->register('','','',$par)){
			// se non avviene la registrazione lato api, la cancello anche lato elgg
			$new_user->delete();
			\Uoowd\Logger::addError('Impossibile registrare l\'utente');
			forward(REFERER);
		}
		//----- End lines added by Simone Scardoni.

		elgg_clear_sticky_form('useradd');

		$new_user->admin_created = TRUE;
		// @todo ugh, saving a guid as metadata!
		$new_user->created_by_guid = elgg_get_logged_in_user_guid();

		$subject = elgg_echo('useradd:subject', array(), $new_user->language);
		$body = elgg_echo('useradd:body', array(
			$name,
			elgg_get_site_entity()->name,
			elgg_get_site_entity()->url,
			$username,
			$password,
		), $new_user->language);

		notify_user($new_user->guid, elgg_get_site_entity()->guid, $subject, $body);

		system_message(elgg_echo("adduser:ok", array(elgg_get_site_entity()->name)));
	} else {
		register_error(elgg_echo("adduser:bad"));
	}
} catch (RegistrationException $r) {
	register_error($r->getMessage());
}

forward(REFERER);

