<?php
	/**
	 * wrap della corrispettiva view nel plugin search
	 */
?>

La ricerca non ha prodotto alcun risultato. <br/><br/>

<?php
	if(!elgg_is_logged_in()) return;
?>

Se vuoi invitare uno o piu amici non ancora presente clicca sul pulsante <br/><br/>

<a class="elgg-button" href="<?php echo elgg_get_site_url().'invite'; ?>">Invita</a>