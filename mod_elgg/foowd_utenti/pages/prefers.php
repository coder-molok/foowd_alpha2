<?php


/* classe di supporto: in realta' sarebbe superflua  */
class tmpUser {
	public function __construct($id){
		$this->id = $id;
		$this->entity = get_user($id);
		$this->dirAvatar = \Uoowd\Param::pathStore($id, 'avatar', 'web');
		$this->img = $this->dirAvatar . "small/$id.jpg";
		$this->username = $this->entity->username;
		$this->htmlAvatar = '<img src="' . $this->img . '"/>';
		$this->viewUser = '<div class="single-user">'. $this->htmlAvatar .'<div>'.$this->username.'</div></div>';
		// \Fprint::r($this->htmlAvatar);
	}
}

/* classe di supporto: in realta' sarebbe superflua  */
class userFactory{

	public $farm = array();
	public function __construct($arId){
		foreach($arId as $id){
			$t = new tmpUser($id);
			$this->farm[$id] = $t;
		}
	}
}

// inizio a memorizzare lo streaming di aoutup
ob_start();

echo 'Di seguito puoi vedere l\'elenco delle tue preferenze assieme agli amici che ne hanno aderito.<br/> Se tu e i tuoi amici avete raggiunto la quota minima, allora puoi decidere di diventare capogruppo e far partire la spedizione, ma prima contatta i tuoi amici!';

// ottengo il guid dell'utente loggato
$guid=elgg_get_logged_in_user_guid();

// Trovo gli id degli amici
$entities = elgg_get_entities_from_relationship(array(
    'relationship' => 'friend',
    'relationship_guid' => $guid,
));
// array con gli id per trovare i match lato API foowd
$friends = array($guid);
foreach($entities as $ent) array_push($friends, $ent->guid);

// classe che colleziona gli amici in un array // utilzzata solo in questa pagina
$farm = new userFactory($friends);
// stringa per le API Foowd
$friendsList = implode($friends, ',');

// con questo trovo le offerte comuni tra l'utente e i suoi amici
$data['type'] = 'commonOffers'; // metodo commonOffers di ApiUser
$data['ExternalId'] = $friendsList;

// Ritorna un array di offerte comuni; 
// Ciascuna offerta di questo array contriene la proprieta' "friends", un semplice array contenente le preferenze degli utenti
$r = \Uoowd\API::Request('user','POST', $data);
// visualizzo il json di risposta
// \Fprint::r($r);

// elenco delle offerte
$offers = $r->body->offers;




// per ogni offerta calcolo il totale delle preferenze (sommo le preferenze mie e dei miei amici)
// inoltre visualizzo l'immagine dell'offerta e gli avatar degli amici che vi hanno aderito
foreach($offers as $of){

	$oid = $of->Id;
	$owner = $of->Publisher;

	// ottengo l'immagine dell'offerta
	$img = \Uoowd\Param::pathStore($owner, 'offers', 'web') . "$oid/medium/$oid.jpg";
	$img = "<img src=\"$img\"/>";


	?>
	<table>
		<tr>
			<td> <?php echo "$img"; ?> </td>
			<td class="img-list">
	<?php

	
	$friendsQt = 0; // semplice contatore
	$prefs = $of->friends; // array con l'elenco delle preferenze espresse dagli amici
	// visualizzo le preferenze di questa offerta
	// \Fprint::r($prefs);
	foreach($prefs as $p){ 
		$friendsQt += $p->Qt;
		echo $farm->farm[$p->UserId]->viewUser;
	}
	?>
		</td>
		<td>
	<?php
	echo "Preferenze espresse dal gruppo $friendsQt, su una quota minima di ".$of->Minqt . '<br/>';
	?>
		</td>
		</tr>
	</table>
	<?php
}

?>

<style>
	.img-list img{
		width: 70px;
		height: 70px;
		border-radius: 35px;
	}

	.single-user{
		margin: 0 15px 15px 0;
		width: 70px;
	}

	.single-user div{
		font-size: 10px;
		margin: auto;
	}

	table td{
		width: 33%;
		margin: 10px;
	}

	table {
		margin-top: 20px;
	}

</style>

<?php
// memorizzo lo stream di output
$body = ob_get_contents();
// cancello lo stream di output
ob_end_clean();

$body = '<div class="foowd-page-preferences">'.$body.'</div>';

echo elgg_view_page('Preferenze',$body);


// creare action elgg javascript


// decommentare \Fprint::r() per vedere direttamente gli output delle api
// Tolto il link di questa pagina my-preferences dalla home
// pagina da visitare: <sito>/foowd_utenti/my-preferences



