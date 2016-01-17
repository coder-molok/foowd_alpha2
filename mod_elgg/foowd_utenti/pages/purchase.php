<?php

// pagina accessibile solo ai loggati
elgg_admin_gatekeeper();

ob_start();
$user = elgg_get_logged_in_user_entity();
// elgg_unregister_menu_item('topbar', 'administration');

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

// trasformo l'array associativo in una stringa da passare come URI
$url=preg_replace('/^(.*)$/e', '"$1=". $purch["$1"].""',array_flip($purch));
$url=implode('&' , $url);
$r = \Uoowd\API::Request('purchase?'.$url,'GET');
// \Fprint::r($r);

$list = '';
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

	$list .= vsprintf($single,array($ldUsr, $ldMail, $pbUsr, $pbMail, $totQ, $totP, $btn));
}

if(count($r->body) <= 0){
	echo "<h3>Non vi sono offerte da chiudere.</h3>";
}
else{
	echo "<h3>Offerte da chiudere.</h3>";
?>
<div class="list">
<table>
	<thead>
		<tr>
			<th>Leader</th>
			<th>Produttore</th>
			<th>Quantita'</th>
			<th></th>
		</tr>	
	</thead>
	<tbody>
<?php
echo $list;
?>
	</tbody>
</table>
</div>

<?php
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