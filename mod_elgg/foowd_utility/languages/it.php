<?php

return array(
/**
 * general override
 */

	'save' => 'Salva',

/**
 * Foowd
 */
	'foowd:name' => 'Titolo offerta',
	'foowd:name:need' => 'Titolo offerta *',
	'foowd:name:error' => 'Devi scrivere il titolo',

	'foowd:description' => 'Descrivi il tuo prodotto',
	'foowd:description:need' => 'Descrivi il tuo prodotto *',
	'foowd:description:error' => 'Manca la descrizione del prodotto',

	'foowd:file' => 'Carica l\'immagine',
	'foowd:file:need' => 'Carica l\'immagine *',
	'foowd:file:error' => 'Non hai aggiunto alcuna immagine',

	'foowd:quota:need' => 'Quantit&agrave per Quota *',
	'foowd:quota:error' => 'Massimo 5 cifre + 3 decimali separate da un punto',
	'foowd:quota:preview' => 'Ecco come verr&agrave; visualizzata la tua quota',

	'foowd:unit:need' => 'Unit&agrave; di misura *',
	'foowd:unit:error' => 'Scegli un valore',
	'foowd:unit:extra' => 'Aggiungi ulteriori specifiche alla quota',

	'foowd:price' => 'Prezzo per quota',
	'foowd:price:need' => 'Prezzo per quota (&euro;) *',
	'foowd:price:error' => 'Massimo 8 cifre + 2 decimali separate da un punto',
	
	'foowd:tag:need' => 'Tags (selezionane almeno uno) *',
	'foowd:tag:error' => 'Devi selezionale almeno un tag',
	
	'foowd:minqt' => 'Numero minimo di Quote',
	'foowd:minqt:need' => 'Numero minimo di Quote *',
	'foowd:minqt:error' => 'Massimo 5 cifre + 3 decimali separate da un punto',

	'foowd:maxqt' => 'Numero massimo di Quote',
	'foowd:maxqt:error' => 'Massimo 5 cifre + 3 decimali separate da un punto',
	'foowd:maxqt:error:larger' => 'La quantit&agrave; massima deve superare o eguagliare quella minima.<br/>Se non vuoi inserire un massimo, cancella i numeri dal campo sottostante.',

	'foowd:expiration' => 'Scadenza',

	
	// user form

	'foowd:user:description' => 'Qui puoi scrivere qualcosa di te (puoi usare codice html)',
	'foowd:user:description:need' => 'Inserisci una descrizione per farti conoscere **',

	'foowd:user:username:error' => 'Il nome utente non &egrave; valido',
	
	'foowd:user:site' => 'Sito Internet',
	'foowd:user:site:optional' => 'Sito Internet *',
	'foowd:user:site:need' => 'Sito Internet',
	'foowd:user:site:error' => 'Il nome del dominio inserito non &egrave; corretto',

	'foowd:user:piva' => 'Partita Iva',
	'foowd:user:piva:optional' => 'Partita Iva (opzionale)',
	'foowd:user:piva:need' => 'Partita Iva **',
	'foowd:user:piva:error' => 'La P.Iva inserita deve essere composta da 11 cifre',

	'foowd:user:phone' => 'Telefono',
	'foowd:user:phone:optional' => 'Telefono (opzionale)',
	'foowd:user:phone:need' => 'Telefono **',
	'foowd:user:phone:error' => 'Il numero di telefono deve essere costituito solo da numeri senza spazi.',

	'foowd:user:address' => 'Indirizzo',
	'foowd:user:address:optional' => 'Indirizzo (opzionale)',
	'foowd:user:address:need' => 'Indirizzo **',
	'foowd:user:address:error' => 'Devi inserire l\'indirizzo',

	'foowd:user:company' => 'Ragione Sociale',
	'foowd:user:company:optional' => 'Ragione Sociale (opzionale)',
	'foowd:user:company:need' => 'Ragione Sociale **',
	'foowd:user:company:error' => 'Manca la Ragione Sociale',

	'foowd:user:email:error' => 'Indirizzo email non valido',

	// Immagini
	'foowd:image:cut:area' => 'puoi ritagliare l\'immagine caricata',

	// utility
	// 'foowd:offer:image-tmp' => 'foowd_offerte/image-tmp',
	'foowd:image-tmp' => 'foowd_utility/image-tmp',
	'foowd:image-profile' => 'foowd_utility/image-profile',

	'developers:event_log_msg' => 'messaggio di log da sviluppatore',

	'search_types:foowd-username-email' => 'Cerca per Nome utente o Email',

	/**
	 * Plugin search
	 */
	
	'search:results' => 'Risultati per %s',

	/**
	 * plugin reportedcontent
	 */
	
	'reportedcontent:user' => 'Segnala utente',

	/**
	 * plugin friend request
	 */
	
		'friend_request' => "Friends Request",
		'friend_request:menu' => "Richieste d'amicizia",
		'friend_request:title' => "Richieste d'amicizia per: %s",

		'friend_request:new' => "Nuova richiesta d'amicizia",
		
		'friend_request:friend:add:pending' => "Richiesta d'amiciza in sospeso",
		
		'friend_request:newfriend:subject' => "%s vuole essere tuo amico!",
		'friend_request:newfriend:body' => "%s vogliono essere tuoi amici! Ma stanno attendendo che tu approvi la loro richiesta... loggati per poterla approvare!

	Puoi vedere le tue richieste in sospeso al link:
	%s

	Assicurati di aver effettuato l'accesso al sito prima di cliccare sul seguente link, altrimenti sarai reindirizzato alla pagina di log in.

	(Non puoi rispondere a questa mail.)",
			
		// Actions
		// Add request
		'friend_request:add:failure' => "Spiacenti, a causa di un errore di sistema non &egrave; possibile completare la richiesta. Sei pregato di riprovare.",
		'friend_request:add:successful' => "Hai mandato una richiesta d'amicizia a %s. E' necessario attendere l'approvazione prima di poter visualizzare i nuovi amici nella tua lista di amcizie.",
		'friend_request:add:exists' => "Hai gi&agrave; richiesto l'amicizia a %s.",
		
		// Approve request
		'friend_request:approve' => "Approva",
		'friend_request:approve:subject' => "%s ha accettato la tua richiesta d'amicizia.",
		'friend_request:approve:message' => "Utente %s,

	%s ha accettato di diventare tuo amico.",
		'friend_request:approve:successful' => "%s &egrave; ora un amico.",
		'friend_request:approve:fail' => "Errore nella creazione dell'amicizia con %s",

		// Decline request
		'friend_request:decline' => "Declina",
		'friend_request:decline:subject' => "%s ha declinato la tua richiesta d'amicizia",
		'friend_request:decline:message' => "Utente %s,

	%s ha declinato la tua richiesta d'amicizia.",
		'friend_request:decline:success' => "Richiesta d'amicizia declinata con successo",
		'friend_request:decline:fail' => "Errore durante la declinazione della richiesta d'amicizia, sei pregato di riprovare.",
		
		// Revoke request
		'friend_request:revoke' => "Revoca",
		'friend_request:revoke:success' => "Richiesta d'amicizia revocata con successo.",
		'friend_request:revoke:fail' => "Errore nella revoca della richiesta d'amicizia, sei pregato di riprovare.",

		// Views
		// Received
		'friend_request:received:title' => "Richieste d'amicizia ricevute",
		'friend_request:received:none' => "Non vi sono richieste ricevute in attesa di essere approvate.",

		// Sent
		'friend_request:sent:title' => "Richieste d'amicizia inviate",
		'friend_request:sent:none' => "Non vi sono richieste inviate in attesa di essere approvate.",


	/**
	 * Plugin Invite Friends
	 */
	
			'friends:invite' => 'Invita Amici',
			
			'invitefriends:registration_disabled' => 'La registrazione di nuovi utenti su questo sito &egrave; stata disabilitata; non puoi invitare nuovi utenti.',
			
			'invitefriends:introduction' => 'Per invitare amici a collegarsi a questo network, inserisci i loro indirizzi email e un messaggio che riceveranno assieme al tuo invito.',
			'invitefriends:emails' => 'Indirizzi Email (uno per riga)',
			'invitefriends:message' => 'Messaggio',
			'invitefriends:subject' => 'Invito a collegarsi a %s',

			'invitefriends:success' => 'I tuoi amici sono stati invitati.',
			'invitefriends:invitations_sent' => 'Inviti spediti: %s. Sono avvenuti i seguenti problemi:',
			'invitefriends:email_error' => 'I seguenti indirizzi non sono validi: %s',
			'invitefriends:already_members' => 'I seguenti sono gi&agrave; membri: %s',
			'invitefriends:noemails' => 'Non hai inserito l\'indirizzo email.',
			
			'invitefriends:message:default' => 'Ciao,

		sei invitato a collegarti al mio network su %s.',

			'invitefriends:email' => 'Hai ricevuto un invito per collegarti a %s da %s. E\' stato incluso il seguente messaggio:

		%s

		Per collegarti clicca sul link seguente:

		%s

		Saranno automaticamente aggiunti alle tue amicizie appena avrai creato il tuo account.',
			
	/**
	 * Plugin UserValidationByEmail
	 */
			'admin:users:unvalidated' => 'Non validato',
			
			'email:validate:subject' => "%s ti chiediamo gentilmente di confermare il tuo indirizzo email per %s!",
			'email:validate:body' => "%s,

		Prima di iniziare a utilizzare %s, e' necessario confermare il proprio indirizzo.

		A tal fine ti chiediamo di cliccare sul link sottostante:

		%s

		Se non puoi cliccare, copia e incolla il link nella barra del browser.

		%s
		%s
		",
			'email:confirm:success' => "Hai confermato il tuo indirizzo email!",
			'email:confirm:fail' => "Il tuo indirizzo email non puo' essere verificato...",

			'uservalidationbyemail:emailsent' => "Email spedita a <em>%s</em>",
			'uservalidationbyemail:registerok' => "Per attivare il tuo account sei invitato a confermare il tuo indirizzo email cliccando sul link che troverai nalla mail che ti abbiamo spedito.",
			'uservalidationbyemail:login:fail' => "Il tuo account non &egrave; validato, pertanto il tentativo di log in &egrave; fallito. E' stata spedita un'altra email di validazione.",

			'uservalidationbyemail:admin:no_unvalidated_users' => 'Non ci sono Utenti in attesa di validazione.',

			'uservalidationbyemail:admin:unvalidated' => 'Unvalidated',
			'uservalidationbyemail:admin:user_created' => 'Registrato %s',
			'uservalidationbyemail:admin:resend_validation' => 'Rispedita validazione',
			'uservalidationbyemail:admin:validate' => 'Valida',
			'uservalidationbyemail:confirm_validate_user' => 'Validare %s?',
			'uservalidationbyemail:confirm_resend_validation' => 'Rispedire email di validazione a %s?',
			'uservalidationbyemail:confirm_delete' => 'Eliminare %s?',
			'uservalidationbyemail:confirm_validate_checked' => 'Validare gli utenti selezionati?',
			'uservalidationbyemail:confirm_resend_validation_checked' => 'Rispedire la validazione agli utenti selezionati?',
			'uservalidationbyemail:confirm_delete_checked' => 'Eliminare gli utenti selezionati?',
			
			'uservalidationbyemail:errors:unknown_users' => 'Utenti sconosciuti',
			'uservalidationbyemail:errors:could_not_validate_user' => 'Non &egrave; possibile validare l\'utente.',
			'uservalidationbyemail:errors:could_not_validate_users' => 'Non &egrave; possibile validare tutti gli utenti selezionati.',
			'uservalidationbyemail:errors:could_not_delete_user' => 'Non &egrave; possibile eliminare l\'utente.',
			'uservalidationbyemail:errors:could_not_delete_users' => 'Non &egrave; possibile eliminare tutti gli utenti selezionati.',
			'uservalidationbyemail:errors:could_not_resend_validation' => 'Non &egrave; possibile rispedire la richiesta di validazione.',
			'uservalidationbyemail:errors:could_not_resend_validations' => 'Non &egrave; possibile inviare la richiesta di validazione a tutti gli utenti selezionati.',

			'uservalidationbyemail:messages:validated_user' => 'Utente validato.',
			'uservalidationbyemail:messages:validated_users' => 'Validati tutti gli utenti selezionati.',
			'uservalidationbyemail:messages:deleted_user' => 'Utente eliminato.',
			'uservalidationbyemail:messages:deleted_users' => 'Eliminati tutti gli utenti selezionati.',
			'uservalidationbyemail:messages:resent_validation' => 'Richiesta di validazione inviata.',
			'uservalidationbyemail:messages:resent_validations' => 'Richiesta di validazione inviata a tutti gli utenti selezionati.',


/**
 * Account
 */

	'account' => "Account",
	'settings' => "Impostazioni",
	'tools' => "Strumenti",
	'settings:edit' => 'Modifica Impostazioni',

	'register' => "Registrati",
	'registerok' => "Ti sei registrato con successo come %s.",
	'registerbad' => "La tua registrazione non &egrave; andata a buon fine a causa di un errore sconosciuto.",
	'registerdisabled' => "La registrazione &egrave; stata disabilitata dagli Amministratori",
	'register:fields' => 'Sono richiesti tutti i campi',

	'registration:notemail' => 'L\'indirizzo email inserito non e\' valido.',
	'registration:userexists' => 'Nome Utente gia\' utilizzato',
	'registration:usernametooshort' => 'Il nome utente deve contenere almeno %u caratteri.',
	'registration:usernametoolong' => 'Nome utente troppo lungo. Puo\' contenere massimo %u caratteri.',
	'registration:passwordtooshort' => 'La password deve contenere almeno %u caratteri.',
	'registration:dupeemail' => 'Indirizzo email gi\'a utilizzato.',
	'registration:invalidchars' => 'Spiacenti, il nome utente contiene il carattere %s , non consentito. I seguenti caratteri non sono ammissibili: %s',
	'registration:emailnotvalid' => 'Spiacenti, l\'indirizzo email inserito non risulta valido in questo sistema',
	'registration:passwordnotvalid' => 'Spiacenti, la password inserita non risulta valido in questo sistema',
	'registration:usernamenotvalid' => 'Spiacenti, il nome utente inserito non risulta valido in questo sistema',

	'adduser' => "Aggiungi utente",
	'adduser:ok' => "Hai aggiunto con successo un nuovo utente.",
	'adduser:bad' => "Non e' possibile creare il nuovo utente.",

	'user:set:name' => "Account utente",
	'user:name:label' => "Nome utente",
	'user:name:success' => "Cambiato con successo il nome visualizzato nel sistema.",
	'user:name:fail' => "Non e' possibile cambiare il nome visualizzato nel sistema.",

	'user:set:password' => "Password dell'account",
	'user:current_password:label' => 'Password attuale',
	'user:password:label' => "Nuova password",
	'user:password2:label' => "Conferma la nuova password",
	'user:password:success' => "Password modificata",
	'user:password:fail' => "Non è possibile al momento cambiare la password.",
	'user:password:fail:notsame' => "Le due password non sono uguali!",
	'user:password:fail:tooshort' => "La password e' troppo corta!",
	'user:password:fail:incorrect_current_password' => "La password inserita e' errata.",
	'user:changepassword:unknown_user' => 'Utente non valido.',
	'user:changepassword:change_password_confirm' => "Questo cambiera' la tua password.",

	'user:set:language' => "Impostazioni della Lingua",
	'user:language:label' => "Lingua",
	'user:language:success' => "Impostazioni della lingua aggiornate.",
	'user:language:fail' => "Le impostazioni della lingua non possono essere aggiornate.",

	'user:username:notfound' => 'Nome Utente %s non trovato.',

	'user:password:lost' => 'Password smarrita',
	'user:password:changereq:success' => 'Richiesta nuova password avvenuta con successo. Riceverai a breve una mail.',
	'user:password:changereq:fail' => 'Non &egrave; possibile richiedere una nuova password.',

	'user:password:text' => 'Per richiedere una nuova password, inserisci il tuo nome Utente o indirizzo email nel campo sottostante e clicca sul bottone di richiesta.',

	'user:persistent' => 'Login automatico',

	'walled_garden:welcome' => 'Welcome to',






/**
 * Sites
 */

	'item:site' => 'Siti',

/**
 * Sessions
 */

	'login' => "Log in",
	'loginok' => "Benvenuto!", //"Hai appena effettuato il log in.",
	'loginerror' => "Non riusciamo a effettuare il log you in. Per favore, controlla le tue credenziali e prova ad accedere nuovamente.",
	'login:empty' => "Nome Utente o Email e password sono necessari.",
	'login:baduser' => "Impossibile caricare il tuo accont utente.",
	'auth:nopams' => "Errore interno. Non e' installato alcun metodo di autenticazione utente.",

	'logout' => "Log out",
	'logoutok' => "E' stato effettuato il log out.",
	'logouterror' => "Non riusciamo a effettuare il log out. Per favore, riprova nuovamente.",
	'session_expired' => "La tua sessione e' scaduta. Per favore, ricarica la pagina per rieffettuare il log in.",

	'loggedinrequired' => "Devi essere loggato per visualizzare la pagina richiesta.",
	'adminrequired' => "Devi essere un amministratore per visualizzare la pagina richiesta.",
	'membershiprequired' => "Devi essere un membro di questo gruppo per visualizzare la pagina richiesta.",
	'limited_access' => "Non hai i permessi necessari per visualizzare la pagina richiesta.",


/**
 * Errors
 */

	'exception:title' => "Errore Fatale.",
	'exception:contact_admin' => 'An unrecoverable error has occurred and has been logged. Contact the site administrator with the following information:',

	'actionundefined' => "L'azione richiesta (%s) non e' definita nel sistema.",
	'actionnotfound' => "The action file for %s was not found.",
	'actionloggedout' => "Spiacenti, non puoi svolgere questa azione fino a quando non sei connesso.",
	'actionunauthorized' => 'Non hai i permessi per svolgere questa azione',
	
	'ajax:error' => 'Errore inaspettato durante la chiamata AJAX. Forse la connessione al server ha dei malfunzionamenti.',

/**
 * User details
 */

	'name' => "Nome visualizzato",
	'email' => "Indirizzo email",
	'username' => "Nome Utente",
	'loginusername' => "Nome Utente o Email",
	'password' => "Password",
	'passwordagain' => "Ripeti la password",
	'admin_option' => "Vuoi rendere questo utente un amministratore?",

/**
 * Access
 */

	'PRIVATE' => "Privato",
	'LOGGED_IN' => "Utenti Connessi",
	'PUBLIC' => "Pubblico",
	'LOGGED_OUT' => "Utenti Disconnessi",
	'access:friends:label' => "Amici",
	'access' => "Accesso",
	'access:overridenotice' => "Nota: a causa di restrizioni del gruppo, questo contenuto e' accessibile solo a membri del gruppo.",
	'access:limited:label' => "Limitato",
	'access:help' => "Il livello d'accesso",
	'access:read' => "Leggi accesso",
	'access:write' => "Scrivi accesso",
	'access:admin_only' => "Solo Amministratori",

/**
 * Groups
 */

	'group' => "Gruppo",
	'item:group' => "Gruppi",

/**
 * Users
 */

	'user' => "Utente",
	'item:user' => "Utenti",

/**
 * Friends
 */

	'friends' => "Amici",
	'friends:yours' => "I tuoi amici",
	'friends:owned' => "Amici di %s",
	'friend:add' => "Aggiungi amico",
	'friend:remove' => "Rimuovi amico",

	'friends:add:successful' => "Hai aggiunto con successo l'utente %s alle tue amicizie.",
	'friends:add:failure' => "Non e' possibile aggiungere %s alle tue amicizie.",

	'friends:remove:successful' => "Hai rimosso con successo %s dalle tue amicizie.",
	'friends:remove:failure' => "Non e' possibile rimuovere %s dalle tue amicizie.",

	'friends:none' => "Ancora non hai amici.",
	'friends:none:you' => "Non hai ancora alcun amico.",

	'friends:none:found' => "Non sono stati trovati amici.",

	'friends:of:none' => "Nessuno ha ancora aggiunto questo utente come amico.",
	'friends:of:none:you' => "Nessuno ti ha ancora aggiunto come amico. Inizia con l'aggiungere contenuti e riempire il tuo profilo per consentire alle altre persone di trovarti!",

	'friends:of:owned' => "People who have made %s a friend",

	'friends:of' => "Amici di",
	'friends:collections' => "Liste di Amici",
	'collections:add' => "Nuova collezione",
	'friends:collections:add' => "Nuova collezione di Amici",
	'friends:addfriends' => "Seleziona Amici",
	'friends:collectionname' => "Nome della collezione",
	'friends:collectionfriends' => "Amici nella collezione",
	'friends:collectionedit' => "Modifica questa collezione",
	'friends:nocollections' => "Non hai ancora aggiunto alcuna collezione.",
	'friends:collectiondeleted' => "La collezione e' stata eliminata.",
	'friends:collectiondeletefailed' => "Non e' possibile rimuovere la collezione. Potresti non avere i permessi necessari, oppure e' sono avvenuti altri problemi.",
	'friends:collectionadded' => "La collezione e' stata creata con successo",
	'friends:nocollectionname' => "Devi dare un nome alla collezione prima di crearla.",
	'friends:collections:members' => "Membri della collezione",
	'friends:collections:edit' => "Modifica collezione",
	'friends:collections:edited' => "Collezione salvata",
	'friends:collection:edit_failed' => 'Non e\' possibile salvare la collezione.',

	'friendspicker:chararray' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',


	

/**
 * River
 */
	'river' => "River",
	'river:friend:user:default' => "%s &egrave; ora amico di %s",
	'river:update:user:avatar' => '%s ha un nuovo avatar',
	'river:update:user:profile' => '%s has updated their profile',
	'river:noaccess' => 'Non hai i permessi per visualizzare questa opzione.',
	'river:posted:generic' => '%s posted',
	'riveritem:single:user' => 'a user',
	'riveritem:plural:user' => 'some users',
	'river:ingroup' => 'nel gruppo %s',
	'river:none' => 'Nessuna attivit&acute;',
	'river:update' => 'Aggiornamento per %s',
	'river:delete' => 'Remove this activity item',
	'river:delete:success' => 'River item has been deleted',
	'river:delete:fail' => 'River item could not be deleted',
	'river:subject:invalid_subject' => 'Utente non valido',
	'activity:owner' => 'Visualizza attivit&agrave;',

	'river:widget:title' => "Attivit&agrave;",
	'river:widget:description' => "Display latest activity",
	'river:widget:type' => "Type of activity",
	'river:widgets:friends' => 'Friends activity',
	'river:widgets:all' => 'Tutte le attivit&agrave; del Sito',

/**
 * Notifications
 */
	'notifications:usersettings' => "Notifiche ed avvisi",
	'notification:method:email' => 'Email',

	'notifications:usersettings:save:ok' => "Impostazione delle notifiche modificata con successo.",
	'notifications:usersettings:save:fail' => "E' avvenuto un problema nel salvataggio delle impostazioni delle notifiche.",

	'notification:subject' => 'Notifica riguardante %s',
	'notification:body' => "Visualizza le attivita' a %s",



/**
 * Administration
 */
/*
	'admin:configuration:success' => "Impostazioni salvate con successo.",
	'admin:configuration:fail' => "Le impostazioni non possono essere salvate.",
	
	'admin' => "Amministrazione",
	
	'admin:users:description' => "Questo pannello d'amministrazione consente di controllare le impostazioni del proprio sito. Sceglio una opzione tra le seguenti per poter iniziare.",
	
	'admin:settings' => 'Impostazioni',
	'admin:settings:basic' => 'Impostazioni Base',
	'admin:settings:advanced' => 'Impostazioni Avanzate',
	'admin:site:description' => "Questo pannello d'amministrazione consente di controllare le impostazioni generali del tuo sito. Scegli un'opzione per iniziare",
	'admin:site:opt:linktext' => "Configura sito...",
	'admin:settings:in_settings_file' => 'Questa impostazione e\' configurata in settings.php' ,
*/

/**
 * Plugins
 */


	'plugins:settings:save:ok' => "Le impostazioni del plugin %s sono state salvate con successo.",
	'plugins:settings:save:fail' => "E' avvenuto un problema durante il salvataggio delle impostazioni relative al plugin %s",
	'plugins:usersettings:save:ok' => "Impostazioni utente per il plugin %s salvate con successo.",
	'plugins:usersettings:save:fail' => "E' avvenuto un problema durante il salvataggio impostazioni utente del plugin %s.",
	'item:object:plugin' => 'Plugins',

	'admin:plugins' => "Plugins",
	
/**
 * Avatar
 */

	'avatar:edit' => 'Modifica la tua immagine',

/**
 * Profile
 */
 
	'profile:edit' => 'Modifica il tuo profilo',

/**
 * User settings
 */
		
	'usersettings:description' => "Il pannello Impostazioni Utente consente di controllare tutte le tue impostazioni personali, dalla gestione utenti sino al funzionamento dei plugins. Scegli un'opzione sottostante per iniziare.",

	'usersettings:statistics' => "Le tue statistiche",
	'usersettings:statistics:opt:description' => "Visualizza statistiche relative a utenti o oggetti nel tuo sito.",
	'usersettings:statistics:opt:linktext' => "Statistiche dell'account",

	'usersettings:user' => "Impostazioni di %s",
	'usersettings:user:opt:description' => "Questo ti consente di controllare le tue impostazioni utente.",
	'usersettings:user:opt:linktext' => "Modifica le tue impostazioni",

	'usersettings:plugins' => "Strumenti",
	'usersettings:plugins:opt:description' => "Configura impostazioni per gli Strumenti utilizzati (se ne hai).",
	'usersettings:plugins:opt:linktext' => "Configura i tuoi strumenti",

	'usersettings:plugins:description' => "Questo pannello consente di controllare e configurare le Impostazioni personali relative agli strumenti installati dagli amministratori del sistema.",
	'usersettings:statistics:label:numentities' => "I tuoi contenuti",

	'usersettings:statistics:yourdetails' => "I tuoi dettagli",
	'usersettings:statistics:label:name' => "Nome completo",
	'usersettings:statistics:label:email' => "Email",
	'usersettings:statistics:label:membersince' => "Membero dal",
	'usersettings:statistics:label:lastlogin' => "Ultimo accesso",

/**
 * Activity river
 */
		
	'river:all' => 'Tutte le attivit&agrave; del Sito',
	'river:mine' => 'Mie attivit&agrave;',
	'river:owner' => 'Attivit&agrave; di %s',
	'river:friends' => 'Attivit&agrave; degli amici',
	'river:select' => 'Mostra %s',
	'river:comments:more' => '+%u altro',
	'river:generic_comment' => 'commento su %s %s',		
/**
 * Generic action words
 */

	'save' => "Salva",
	'reset' => 'Reset',
	'publish' => "Pubblica",
	'cancel' => "Cancella",
	'saving' => "Sto salvando ...",
	'update' => "Aggiorna",
	'preview' => "Preview",
	'edit' => "Modifica",
	'delete' => "Elimina",
	'accept' => "Accetta",
	'reject' => "Rifiuta",
	'decline' => "Declina",
	'approve' => "Approva",
	'activate' => "Attiva",
	'deactivate' => "Disattiva",
	'disapprove' => "Disapprova",
	'revoke' => "Revoca",
	'load' => "Carica",
	'upload' => "Carica",
	'download' => "Scarica",
	'ban' => "Banna",
	'unban' => "Rimuovi banner",
	'banned' => "Bannato",
	'enable' => "Abilita",
	'disable' => "Disabilita",
	'request' => "Richiedi",
	'complete' => "Completato",
	'open' => 'Apri',
	'close' => 'Chiudi',
	'hide' => 'Nascondi',
	'show' => 'Mostra',
	'reply' => "Rispondi",
	'more' => 'Altro',
	'more_info' => 'Ulteriori informazioni',
	'comments' => 'Commenti',
	'import' => 'Importa',
	'export' => 'Esporta',
	'untitled' => 'Senza titolo',
	'help' => 'Aiuto',
	'send' => 'Invia',
	'post' => 'Post',
	'submit' => 'Submit',
	'comment' => 'Commenta',
	'upgrade' => 'Aggiorna',
	'sort' => 'Ordina',
	'filter' => 'Filtra',
	'new' => 'Nuovo',
	'add' => 'Aggiungi',
	'create' => 'Crea',
	'remove' => 'Rimuovi',
	'revert' => 'Inverti',

	'site' => 'Sito',
	'activity' => 'Attivit&agrave;',
	'members' => 'Membri',
	'menu' => 'Menu',

	'up' => 'Su',
	'down' => 'Giu',
	'top' => 'Alto',
	'bottom' => 'Basso',
	'right' => 'Destra',
	'left' => 'Sinistra',
	'back' => 'Indietro',

	'invite' => "Invita",

	'resetpassword' => "Azzera password",
	'changepassword' => "Cambia password",
	'makeadmin' => "Rendi Amministratore",
	'removeadmin' => "Rimuovi Amministratore",

	'option:yes' => "Si",
	'option:no' => "No",

	'unknown' => 'Sconosciuto',
	'never' => 'Mai',

	'active' => 'Attivo',
	'total' => 'Totale',
	
	'ok' => 'OK',
	'any' => 'Qualunque',
	'error' => 'Errore',
	
	'other' => 'Altro',
	'options' => 'Opzioni',
	'advanced' => 'Avanzato',

	'learnmore' => "Clicca qui per saperne di piu.",
	'unknown_error' => 'Errore sconosciuto',

	'content' => "contenuto",
	'content:latest' => 'Ultima Attivit&agrave;',
	'content:latest:blurb' => 'In alternativa clicca qui per vedere l\'ultimo contenuto al di fuori del sito.',

	'link:text' => 'visualizza link',
	
/**
 * Generic questions
 */

	'question:areyousure' => 'Sei sicuro?',

/**
 * Status
 */

	'status' => 'Stato',
	
/**
 * Generic data words
 */

	'title' => "Titolo",
	'description' => "Descrizione",
	'tags' => "Tags",
	'all' => "Tutto",
	'mine' => "Mie",

	'by' => 'per',
	'none' => 'nessuno',

	'annotations' => "Annotazioni",
	'relationships' => "Relazioni",
	'metadata' => "Metadata",
	'tagcloud' => "Tag cloud",

	'on' => 'On',
	'off' => 'Off',


/**
 * User add
 */

	'useradd:subject' => 'Profilo utente creato',
	'useradd:body' => '
%s,

E\' stato creato per te un profilo utente su %s. Per accedere visita:

%s

ed effettua l\'accesso con queste credenziali:

Nome Utente: %s
Password: %s

Una volta effettuato l\'accesso, ti raccomandiamo di cambiare la tua password.
',

/**
 * System messages
 */

	'systemmessages:dismiss' => "clicca per rifiutare",


/**
 * Time
 */

	'friendlytime:justnow' => "proprio adesso",
	'friendlytime:minutes' => "%s minuti fa",
	'friendlytime:minutes:singular' => "un minuto fa",
	'friendlytime:hours' => "%s ore fa",
	'friendlytime:hours:singular' => "un'ora fa",
	'friendlytime:days' => "%s giorni fa",
	'friendlytime:days:singular' => "ieri",
	'friendlytime:date_format' => 'j F Y @ g:ia',
	
	'friendlytime:future:minutes' => "tra %s minuti",
	'friendlytime:future:minutes:singular' => "tra un minuto",
	'friendlytime:future:hours' => "tra %s ore",
	'friendlytime:future:hours:singular' => "tra un'ora",
	'friendlytime:future:days' => "tra %s giorni",
	'friendlytime:future:days:singular' => "domani",

	// 'date:month:01' => 'January %s',
	// 'date:month:02' => 'February %s',
	// 'date:month:03' => 'March %s',
	// 'date:month:04' => 'April %s',
	// 'date:month:05' => 'May %s',
	// 'date:month:06' => 'June %s',
	// 'date:month:07' => 'July %s',
	// 'date:month:08' => 'August %s',
	// 'date:month:09' => 'September %s',
	// 'date:month:10' => 'October %s',
	// 'date:month:11' => 'November %s',
	// 'date:month:12' => 'December %s',

	// 'date:weekday:0' => 'Sunday',
	// 'date:weekday:1' => 'Monday',
	// 'date:weekday:2' => 'Tuesday',
	// 'date:weekday:3' => 'Wednesday',
	// 'date:weekday:4' => 'Thursday',
	// 'date:weekday:5' => 'Friday',
	// 'date:weekday:6' => 'Saturday',
	
	// 'interval:minute' => 'Every minute',
	// 'interval:fiveminute' => 'Every five minutes',
	// 'interval:fifteenmin' => 'Every fifteen minutes',
	// 'interval:halfhour' => 'Every half hour',
	// 'interval:hourly' => 'Hourly',
	// 'interval:daily' => 'Daily',
	// 'interval:weekly' => 'Weekly',
	// 'interval:monthly' => 'Monthly',
	// 'interval:yearly' => 'Yearly',
	// 'interval:reboot' => 'On reboot',


/**
 * Welcome
 */

	'welcome' => "Benvenuto",
	'welcome:user' => 'Benvenuto %s',

/**
 * Emails
 */
		
	'email:from' => 'Da',
	'email:to' => 'A',
	'email:subject' => 'Oggetto',
	'email:body' => 'Corpo',
	
	'email:settings' => "Impostazioni email",
	'email:address:label' => "Indirizzo email",

	'email:save:success' => "Nuovo indirizzo email salvato con successo. E' richiesta la verifica.",
	'email:save:fail' => "Il nuovo indirizzo email non puo' essere salvato.",

	'friend:newfriend:subject' => "%s ti ha aggiunto ai suoi amici!",
	'friend:newfriend:body' => "%s ti ha aggiunto ai suoi amici!

Per visualizzare il suo profilo clicca qui:

%s

Cortesemente, non rispondere a questa mail.",

	'email:changepassword:subject' => "Password modificata!",
	'email:changepassword:body' => "Salve %s,

la tua password e' stata modificata.",

	'email:resetpassword:subject' => "Password azzerata!",
	'email:resetpassword:body' => "Salve %s,

La tua password e' stata reimpostata a: %s",

	'email:changereq:subject' => "Rcihiesta cambio password.",
	'email:changereq:body' => "Salve %s,

Qualcuno (dall'indirizzo IP %s) ha chiesto un cambio password per il suo profilo.

Se ha svolto tale richiesta clicchi qui, altrimenti ignori pure questa email.

%s
",


/**
 * Action gatekeeper
 */

	'actiongatekeeper:missingfields' => 'Nel form manca __token o __ts fields',
	'actiongatekeeper:tokeninvalid' => "La pagina che stavi usando e' scaduta. Si consiglia di riprovare.",
	'actiongatekeeper:timeerror' => 'La paghina che stavi usando e\' scaduta. Si consiglia di la pagina e riprovare.',
	'actiongatekeeper:pluginprevents' => 'Spiacenti. Il modulo non puo\' essere sottoscritto per ragioni sconosciute.',
	'actiongatekeeper:uploadexceeded' => 'Le dimensioni del file caricato superano i limiti autorizzati dagli amministratori del sito',
	'actiongatekeeper:crosssitelogin' => "Spiacenti, l'accesso da domini differenti non e' autorizzato. Si prega di riprovare.",

/**
 * Javascript
 */

	'js:security:token_refresh_failed' => 'Connessione a %s fallita. Potresti incontrare problemi nel salvataggio dei contenuti. Si consiglia aggiornare la pagina.',
	'js:security:token_refreshed' => 'Connesione a %s ristabilita!',
	'js:lightbox:current' => "immagine %s di %s",

/**
 * Miscellaneous
 */
	'elgg:powered' => "Sviluppato da Elgg",

);
