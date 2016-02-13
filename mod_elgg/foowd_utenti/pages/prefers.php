<?php

/**
 * pagina di esempio per preferenze my-preferences
 */

/**
 * classe di supporto: in realta' sarebbe superflua
 */
class tmpUser {
	/**
	 * costruisco un tmpUser
	 * 
	 * @param [type] $id [description]
	 */
	public function __construct($id){
		$this->id = $id;
		$this->entity = get_user($id);
		$this->dirAvatar = \Uoowd\Param::pathStore($id, 'avatar', 'web');
		$this->img = $this->dirAvatar . "small/$id.jpg";
		$this->username = $this->entity->username;
		$this->htmlAvatar = '<img src="' . $this->img . '"/>';
		// \Fprint::r($this->htmlAvatar);
	}
}

/**
 * classe di supporto: in realta' sarebbe superflua
 */
class userFactory{
	/**
	 * raccolta di tmpUser
	 * @var array
	 */
	public $farm = array();
	/**
	 * aggiungo alla farm
	 * @param [type] $arId [description]
	 */
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
$currentUser = elgg_get_logged_in_user_entity();
$guid=$currentUser->guid;

if(!$guid){
	register_error('Spiacenti, ma la pagina cercata non e\' accessibile.');
	forward('');
	exit(-1);
} 

// Trovo gli id degli amici
$entities = elgg_get_entities_from_relationship(array(
    'relationship' => 'friend',
    'relationship_guid' => $guid,
));
// array con gli id per trovare i match lato API foowd
// inserisco l'utente attuale e poi i suoi amici
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


?>
<table>
<?php



// per ogni offerta calcolo il totale delle preferenze (sommo le preferenze mie e dei miei amici)
// inoltre visualizzo l'immagine dell'offerta e gli avatar degli amici che vi hanno aderito
foreach($offers as $of){
	// \Fprint::r($offers);

	$oid = $of->Id;
	$owner = $of->Publisher;
	$publisher = get_user($owner);

	// ottengo l'immagine dell'offerta
	$img = \Uoowd\Param::pathStore($owner, 'offers', 'web') . "$oid/medium/$oid.jpg";
	
	$data = sprintf('publisher="%s" leader="%s" offerid="%s"', $publisher->username, $currentUser->username, $oid); 
	$img = "<img class=\"single-offer\" $data src=\"$img\"/>";

	$friendsQt = 0; // semplice contatore
	$prefs = $of->friends; // array con l'elenco delle preferenze espresse dagli amici
	
	// contantore di preferenze: dato che Qt puo' valere 0, nel caso sia cosi' per tutte, allora evito di scrivere l'offerta
	$countPref = 0;

	// visualizzo le preferenze di questa offerta raccogliendole in una stringa
	$displayFriends = '';
	foreach($prefs as $p){ 
		if($p->Qt <= 0 || $p->State === 'solved') continue;
		$countPref++;
		$friendsQt += $p->Qt;
		$usr = $farm->farm[$p->UserId];
		// \Fprint::r($p);
		// $data = sprintf(' data-username="%s" data-offerid="%s" data-qt="%s" ', $usr->username, $p->OfferId, $p->Qt);
		$data = sprintf(' data-preferid="%s" data-username="%s" ', $p->Id, $usr->username);
		$displayFriends .= '<div class="single-user" '. $data .'>'. $usr->htmlAvatar .'<div>'.$usr->username.'</div></div>';
	}

	$purchable = ($friendsQt >= $of->Minqt);

	$trClass = $purchable ? "purchable" : "normal" ;

	if($countPref > 0){
	?>
			<tr class=" <?php echo $trClass;?>">
				<td> <?php echo "$img"; ?> </td>
				<td class="img-list">
				<?php echo $displayFriends; ?>	
			</td>
			<td>
		<?php
		echo "Preferenze espresse dal gruppo $friendsQt, su una quota minima di ".$of->Minqt . '<br/>';
		echo "Quota Massima ordinabile: " . $of->Maxqt . "<br/>";

		if($purchable) echo "<div class=\"elgg-button elgg-button-submit ordina\">Ordina</div>";
		?>
			</td>
			</tr>
	<?php
	}// end di $countPref>0
}
?>
</table>

<!-- esempio di javascript per effettuare l'ordinazione al click -->
<script type="text/javascript">
require(['jquery', 'page'], function($, _page){
	$('.ordina').on('click', function(){
		// il td che contiene il bottone
		var td = $(this).parent();
		// la riga: ciascuna riga di questa tabella e' in corrispondenza iniettiva con una offerta
		// 			inoltre dentro a ogni riga sono presenti, per ciascun utente, i campi 'data' che utilizzo per ottenere i dati da mandare alla action.
		var tr = td.parent();

		// scrivo un messaggio di esecuzione
		tr.addClass('row-attend');
		var t = {"text": 'In esecuzione\n\n .', "tmp": 0};
		tr.attr('data-content', t.text);
		var timer = setInterval(function(){
			tr.attr('data-content', t.text + ".".repeat(t.tmp%10) );
			t.tmp++;
		}, 1000);

		// raccolgo i dati da inviare alla pagina action
		var send =  {};
		send.prefers = [];
		// per ciascun utente raccolgo i dati necessari
		tr.find('.single-user').each(function(){
			// console.log(this.attributes)
			// loop su tutti gli attributi
			var pref = null;
			$.each(this.attributes, function(idx, attr){
				var rx = /data-/ ;
				if(attr.name.match(rx)){
					if( pref === null ) pref = {}
					var prop = attr.name.replace(rx, '');
					pref[prop] = attr.value;
				}
			});
			if(typeof pref === 'object') send.prefers.push(pref)
		});
		var of = tr.find('.single-offer');
		send.publisher = of.attr('publisher');
		send.leader= of.attr('leader');
		send.offerid = of.attr('offerid');
		// invio i dati alla action
		// L'invio avviene tramite metodo POST
		console.log('dati inviati')
		console.log(send)
		elgg.action( _page.action.initPurchase , {
		   data: send,
		   // sui dati ritornati non e' necessario eseguire un parser, perche' ci pensa gia' elgg
		   success: function(json) {
		   		// rimuovo .row-attend e blocco il timer
		   		clearInterval(timer);
		   		tr.removeClass('row-attend')
		   		
		   		// in json.output sono presenti i dati ritornati dalla action invocata
		   		var data = json.output;
		   		console.log(json.output);
		   		// se il responso e' positivo vuol dire che sono stati cambiati dei valori, pertanto riaggiorno la pagina
		   		// 			o eventualmente la copro con della trasparenza
		   		if(data.response){
		   			// location.reload();
		   			tr.attr('data-content', 'Ordine Creato!');
		   			tr.addClass('row'); // lo copro con un messaggio di successo
		   		} 
		   		// NB: se durante l'esecuzione della action avvengono errori, allora viene in automatico ripristinato tutto
		   }
		});
	});
	// $('.ordina').first().trigger('click');
})
</script>

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

	table tr{
		position: relative;
		display: flex;
	}

	table {
		margin: auto;
		margin-top: 20px;
	}

	tr.normal, tr.purchable{
		margin-top: 7px;
	}

	tr.normal{
		background-color: rgba(195, 194, 197, 0.37);
	}

	tr.purchable{
		background-color: rgba(128, 126, 0, 0.18);
	}

	.row, .row-attend{
		position: relative;
	}

	.row::before, .row-attend::before{
		background-color: rgba(72, 162, 79, 0.4);
		content: attr(data-content);
		display: flex;
		justify-content: center;
		align-items: center;
		font-size: 4em;
		color:white;
		position: absolute;
		top: 0;
		left: 0;
		height: 100%;
		width: 100%;
		z-index: 3;
	}

	.row-attend::before{
		background-color: rgba(162, 72, 72, 0.4);
		content: attr(data-content);
		white-space:pre;
		text-align: center;
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



