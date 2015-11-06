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
$ composer dump-autoload --optimize
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



TroubleShoots
==============

durante l'utilizzo di questo plugin possono avvenire degli errori dovuti alla configurazione, sia di **HybridAuth** sia per i **redirect url** dei vari provider.

Entrando nello specifico:


#### Oophs!

questo errore non si capisce bene da dove venga fuori... per iniziare a farsi un'idea conviene abilitare il debug e avere molta pazienza

IN GENERALE
-----------

un problema riscontrato riguarda l'utilizzo dei dns e gli IP nei redirect url:

(tabella aggiornata al 05/11/2015)

|provider | IP | DNS |
|---------|:--:|:---:|
|Google   | NO | SI  |
|Facebook | SI | SI  |


questo e' importante in quanto per poter testare i servizi mentre sono sulla VPS di sviluppo, che non e' provvista di VPS, bisogna svolgere alcuni trick non banali (almeno le prime volte che si incontrano).

**Per GOOGLE** ho varie opzioni (Realizzate con [No-Ip](http://www.noip.com/))

- creare un **Web Redirect** ed inserire questi nella configurazione di `HybridAuth`. Ad esempio ho creato `web-foowd.ddns.net` che svolge un redirect a `5.196.228.146/elgg-1.10.4` : pertanto `web-foowd.ddns.net` va inserito nella configurazione
- creare un **DNS HOST A** che punta all'indirizzo IP della VPS (xxx.xxx.xxx.xxx). In questo caso bisogna utilizzare due accortezze:
    + utilizzare il `DNS HOST A` nella configurazione di `HybridAuth`
    + fare in modo che i links indirizzati alle pagine che istanziano `HybridAuth` risultino come links del `DNS HOST A` (in sostanza fare in modo che risultino del DNS e non direttamente dell' IP).

***
(APPUNTO PERSONALE)

Ma... **perche' questa differenza?**

ci ho messo un po a trovare la risposta, relazionata alla lettura di [http://www.one.com/it/assistenza/guide/gestisci-le-tue-impostazioni-dns](http://www.one.com/it/assistenza/guide/gestisci-le-tue-impostazioni-dns):

col **web redirect** nella barra degli indirizzi del browser il dominio cambia, ovvero il dominio finale sara' quello a cui punta il redirect, mentre con gli **Alias Web** (dns host(A) ) invece nella barra degli indirizzi del browser e' mantenuto il dominio impostato nel **Host(A)**, anche se il contenuto visualizzato e' del dominio relativo all'indirizzo **IP**.

Per mia interpretazione: in Oauth i continui scambi di chiavi devono avvenire nello stesso dominio (ovvio), pertanto il dominio del redirect deve essere sempre lo stesso (come visualizzabile negli HEADER di RICHIESTA mediante i devtools del browser). Ora, questa cosa e' automatica quando si solge un `redirect`, in quanto l'url viene continuamente aggiornato, ma non avviene coi `DNS`, di conseguenza con questi ultimi devo essere piu' accorto e fare in modo che l'host di origine (di richiesta) sia sempre lo stesso (magari snellire tutto impostando un **rewrite base** in `.htaccess`).

***


Riferimenti
===========


[1] [Registrazione Utenti](http://learn.elgg.org/en/1.11/guides/actions.html?highlight=user%20registration#example-user-registration)

[2] [Faq: Esplicazione Registrazione](http://learn.elgg.org/en/1.11/appendix/faqs/general.html?highlight=user%20registration#how-does-registration-work)

[3] [Tutorial Blog](http://learn.elgg.org/en/1.11/tutorials/blog.html) , *utile per metadati*

[4] [Events](http://learn.elgg.org/en/latest/design/events.html) , *alcuni event handlers*


