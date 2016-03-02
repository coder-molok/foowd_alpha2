
In questa directory sono presenti


* dati.php , pagina di preparazione dei dati, che veniva richiamata dal pannello amministrazione

* foowd-dati-forms-views.php , ovvero la view richiamata da dati.php

* foowd-dati.php , che e' la action 

* user-dati.coffee , il javascript relativo a tale pagina



### start.php utenti

if($segments[0] === 'dati'){
    require elgg_get_plugins_path() . 'foowd_utenti/pages/dati.php';
    return true;
}


### pannello utente

    ?>

    <div>
    <?php
    echo '<h3>Profilo</h3>';
    echo '<p>visualizza e modifica le impostazioni del profilo.</p>';
    echo elgg_view('output/url', array(
            // associate to the action
            'href' => $pid.'dati',
            'text' => elgg_echo('I miei dati'),
            'class' => 'elgg-button',
        ));
    ?>
    </div>

    <?php
