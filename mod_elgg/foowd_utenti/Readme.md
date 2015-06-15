Foowd Utenti
============

modulo per la gestione degli utenti.



Aggiungere Utente
-----------------

Per aggiungere un utente vi sono due modalita':

- Registrazione diretta,

    Elgg di default usa un modulo di registrazione nella pagina `<sito_elgg>/register`;

- Registrazione lato Admin,

    e' l'amministratore ad aggiungere il nuovo utente direttamente dal suo pannello: `Users > Add New User`



Requisiti
---------

Il server deve essere abilitato a inviare email mediante la funzione `mail()` di PHP. 

Questa configurazione risulta necessaria per la Registrazione Diretta: compilato e sottoscritto il modulo Elgg non produrra' alcun errore, ma non potendo inviare l'email all'utente non giungera' alcuna mail di conferma, pertanto la sua situazione risultera' in stallo.

Per quanto concerne la registrazione lato Admin, l'unica pecca e' che l'utente aggiunto non ricevera' alcuna mail di avviso dell'iscrizione al sito, ma risultera' gia' automaticamente abilitato in Elgg.

Naturalmente Tenere presente che una piattaforma social e mirata all'interscambio di dati DEVE potersi avvalere di un servizio di notifica mail funzionante.

E' possibile testare la presenza di questa caratteristica mediante lo script
````
<?php
$address = "your_email@your_host.com";
 
$subject = 'Test email.';
 
$body = 'If you can read this, your email is working.';
 
echo "Attempting to email $address...<br />";
 
if (mail($address, $subject, $body)) {
        echo 'SUCCESS!  PHP successfully delivered email to your MTA.  If you don\'t see the email in your inbox in a few minutes, there is a problem with your MTA.';
} else {
        echo 'ERROR!  PHP could not deliver email to your MTA.  Check that your PHP settings are correct for your MTA and your MTA will deliver email.';
}
````

Qualora ritorni errore, provvedere a impostare la configurazione in base al proprio server.


#### WampServer
utilizzando **WampServer**, per configurare l'invio SMTP ho utilizzato la seguente guida: 

http://blog.techwheels.net/send-email-from-localhost-wamp-server-using-sendmail/



Login via  Social
=================

Il sistema di login tramite socials (allo stato attuale Facebook e Google+), sfrutta la libreria [HybridAuth](https://github.com/hybridauth/hybridauth), che provvede un sistema unificato per svolgere l'autenticazione OAuth verso i principali socials.

### Installazione

Per il suo utilizzo e' necessario andare nella directory del plugin `foowd_utility`, dove sara' presente il file `composer.json`, e runnare da linea di comando:

````
$ composer install
````

### Configurazioni

Svolta l'installazione il codice dovrebbe gia' essere funzionante, ma la libreria richiede delle configurazioni esplicite.

Per ciascun social e' necessario creare una rispettiva app in modo da ottenere un **ID** e una **SECRET**, che dovranno poi essere inserite nel pannello d'amministrazione del sito, sotto la categoria Socials.

#### Facebook :

- seguire il link [http://hybridauth.sourceforge.net/userguide/IDProvider_info_Facebook.html](http://hybridauth.sourceforge.net/userguide/IDProvider_info_Facebook.html) per visionare gli step da svolgere per l'installazione. 

    > NB: il sito da indicare nella app e' www.foowd.eu

- nel caso si volesse rimuovere la propria sottoscrizione all'app (ad esempio per test vari), e' necessario andare nella propria pagina di facebook e:
    + Cliccare  in alto su Facebook e seleziona Impostazioni.
    + Cliccare su Applicazioni nella colonna sinistra.
    + Posizionare il cursore sull'applicazione Foowd e cliccare sulla x.
    
    > NB: l'url da indicare nella app e' http://www.foowd.eu/elgg/foowd_utenti/indexauth?hauth.done=Google




#### Google+:

- seguire il link [http://hybridauth.sourceforge.net/userguide/IDProvider_info_Google.html](http://hybridauth.sourceforge.net/userguide/IDProvider_info_Google.html).

- nel caso si volesse rimuovere la propria sottoscrizione all'app (ad esempio per test vari), e' sufficiente andare al link [https://security.google.com/settings/security/permissions](https://security.google.com/settings/security/permissions).




Riferimenti
===========


[1] [Registrazione Utenti](http://learn.elgg.org/en/1.11/guides/actions.html?highlight=user%20registration#example-user-registration)

[2] [Faq: Esplicazione Registrazione](http://learn.elgg.org/en/1.11/appendix/faqs/general.html?highlight=user%20registration#how-does-registration-work)

[3] [Tutorial Blog](http://learn.elgg.org/en/1.11/tutorials/blog.html) , *utile per metadati*

[4] [Events](http://learn.elgg.org/en/latest/design/events.html) , *alcuni event handlers*


