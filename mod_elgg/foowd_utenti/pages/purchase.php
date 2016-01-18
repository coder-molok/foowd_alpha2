<?php

// pagina accessibile solo ai loggati
elgg_gatekeeper();

ob_start();
$user = elgg_get_logged_in_user_entity();
// elgg_unregister_menu_item('topbar', 'administration');

$check = ($user->isAdmin() || $user->Genre == 'offerente');

if(!$check){
	register_error('Non disponi dei permessi necessari a visualizzare la pagina.');
	forward(REFERER);
}

?>

<!--

classi elgg:

	elgg-body , mi permette di rendere il box al 100% del rimanente, nonostante sia preceduto da un div con float:left
	pll , lascia un paddin sinistro di 20 px

-->

<div class="elgg-body foowd-purchase-container">

<?php

// raccolto le offerte da chiudere
$purch = array();
$purch['type']='search';
// $purch['LeaderId'] = $user->getGuid();
$purch['State'] = 'opened';
// \Fprint::r($purch);

// se non e' un amministratore, ritorno solo le offerte di suo interesse
if(!$user->isAdmin()) $purch['PublisherId'] = $user->guid;

// se l'utente e' un produttore non amministratore, allora

// trasformo l'array associativo in una stringa da passare come URI
$url=preg_replace('/^(.*)$/e', '"$1=". $purch["$1"].""',array_flip($purch));
$url=implode('&' , $url);
$r = \Uoowd\API::Request('purchase?'.$url,'GET');
// \Fprint::r($r);

$list = array(
	'noadmin' => array(
			'title' => 'Ordinazioni da chiudere:',
			'list' => array()
	),
	'admin' => array(
			'title' => 'Ordinazioni da chiudere (amministratore):',
			'list' => array()
	),
);


$single = <<<__SINGLE
	<tr>
		<td>
			- %s<br/>
			- %s
		</td>
		<td>
			- %s<br/>
			- %s
		</td>
		<td>
			%s
		</td>
		<td>
			quote: %s<br/>
			totale: %s &euro;
		</td>
		<td>
			%s
		</td>
__SINGLE;


// Come info mostra nome e mail del leader + nome e mail del produttore + q.tà totale e prodotto
foreach($r->body as $p) {
	// \Fprint::r($p);
	$leader = get_entity($p->LeaderId);
	$ldMail = $leader->email;
	$ldUsr = $leader->username;

	$ofName = $p->OfferName;

	$publisher = get_entity($p->PublisherId);
	$pbMail = $publisher->email;
	$pbUsr = $publisher->username;

	$totQ = $p->totalQt;
	$totP = number_format($p->totalPrice, 2, ',', ' ');

	$btn = elgg_view('output/url', array(
		// associate to the action
		// 'href' => \Uoowd\Param::page()->add,
	    'text' => elgg_echo('Chiudi Ordine (' . $p->Id. ')'),
	    'class' => 'elgg-button',
	    'data-purchase' => $p->Id
    ));

	$txt = ($p->PublisherId !== $user->guid) ? 'admin' : 'noadmin' ;
	// \Fprint::r($txt);
	$list[$txt]['list'][] = vsprintf($single,array($ldUsr, $ldMail, $pbUsr, $pbMail, $ofName,$totQ, $totP, $btn));
}

if(count($r->body) <= 0){
	echo "<h3>Non vi sono offerte da chiudere.</h3>";
}
else{

foreach($list as $lst){
	if(count($lst['list']) == 0) continue;
	echo "<h3>" . $lst['title'] . "</h3>";
	// \Fprint::r($lst);


?>
<div class="list">
<table>
	<thead>
		<tr>
			<th>Leader</th>
			<th>Produttore</th>
			<th>Prodotto</th>
			<th>Quantita'</th>
			<th></th>
		</tr>	
	</thead>
	<tbody>
<?php
		foreach($lst['list'] as $l){
			echo $l;
		}
?>
	</tbody>
</table>
</div>

<?php
	} // end foreach $list

} // end if count $r->body
?>

</div><!-- foowd-profilo-container -->

<script type="text/javascript">
require([ 
    'foowd_utenti/admin-purchase'
  ],function(admin){
  	// alert(JSON.stringify(admin))
});
</script>




<?php

$body = ob_get_contents();
ob_end_clean();
$title = 'Ordinazioni in sospeso';
echo elgg_view_page($title, '<div class="foowd-page-purchase">'.$body.'</div>');

elgg_require_js('foowd_utenti/admin-purchase');