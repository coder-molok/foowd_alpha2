<?php

// pagina accessibile solo ai loggati
elgg_admin_gatekeeper();

// in teoria deve comparire anche l'utente
$tgs = array('vino d oc', 'altro tag suggerito');

$s = new \Foowd\SuggestedTags();
$tgs = $s->getDescription();

if(count($tgs) <= 0){
	echo '<h2><div>Non vi sono tag proposti.</div></h2>';
	goto __fileEND;
}

// \Fprint::r($tgs);
$offers = array();
$users = array();

foreach ($tgs as $value) {
	foreach($value as $v){
		$usrId = $v->userId;
		$ofId = $v->offerId;
		$usr = get_entity($usrId);
		if(!isset($users[$usrId])) $users[$usrId] = $usr;
		if(!isset($offers[$ofId])) $offers[$ofId] = $ofId;
	}
}

// \Fprint::r($offers);
// \Fprint::r(implode(',' ,$offers));
// \Fprint::r($users);

$url = 'type=search&Id=' . implode(',' ,$offers);
$r = \Uoowd\API::offerGet($url);


foreach($r->body as $v){
	$v = $v->offer;
	$offers[$v->Id] = $v;
}

// uso i dati per generare la tabella
$model = <<<__RW
	<td>%s</td>
	<td><a href="%s" target="_blank">%s</a></td>
	<td class="cell-hidden" data-userid="%s" data-offerid="%s" data-name="%s"></td>
	</tr>
__RW;
$tbody =  '';
foreach($tgs as $k=>$v){
	$rowspan = count($v);
	$str = "<tr><td rowspan=\"$rowspan\" data-first=\"true\"><input class=\"inpt\" type=\"checkbox\" name=\"$k\" value=\"\"/>$k</td>";
	foreach ($v as $key => $obj) {
		$usrId = $obj->userId;
		$ofId = $obj->offerId;
		// $name = ($offers[$ofId]->Name != '') ? $offers[$ofId]->Name : 'N.P.';
		$url = elgg_get_site_url() . 'foowd_offerte/single?Id=' .$ofId.'&Publisher='.$usrId;
		$username = ($users[$usrId]->username != '') ? $users[$usrId]->username : 'N.P.';
		if($key != 0) $str .= "<tr>";
		$data = array($username, $url, $url,  $usrId, $ofId, $k);
		$str .= vsprintf($model, $data);
	}
	
	$tbody .=  $str;
}
ob_start();
?>
Pagina di esempio non ancora funzionante
<div>Scegli cosa vuoi fare:</div>

<table>
<thead>
	<tr>
		<th>Tag Proposto</th>
		<th>Utente</th>
		<th>Offerta</th>
		<th></th>
	</tr>
</thead>
<tbody>
<?php echo $tbody; ?>
</tbody>
</table>


<div class="remember">
	Assicurati di aver precedentemente salvato nella pagina dei settings (<a href="<?php echo elgg_get_site_url(); ?>/admin/plugin_settings/foowd_utility" target="_blank">Clicca Qui</a>) i tag scelti 
</div>

<div class="what-do">
	Scegli azione da svolgere per le caselle selezionate:
</div>

<!--
<div class="action">
	<label>Salva e Aggiorna</label>
	<p>I tags spuntati verranno automaticamente aggiunti all'offerta e il produttore ricevera' una mail in cui verra' avvisato dell'accettazione del tag. RICORDA che il tag deve essere inserito manualmente <a href="<?php echo elgg_get_site_url(); ?>/admin/plugin_settings/foowd_utility" target="_blank">Qui</a>.</p>
	<button class="elgg-button" data-manage-tag="save">Salva</button>
</div>
-->
<div class="action">
	<label>Elimina Completamente</label>
	<p>Il tag verra' completamente eliminato. Vogliamo anche avvisare il produttore relativamente al rifiuto del suo tag???????</p>
	<button class="elgg-button" data-manage-tag="delete">Rimuovi</button>
</div>




<script type="text/javascript">

require([ 
	'jquery', 'page'
], function($, _page){

	// elgg = require('elgg');

	$('[data-manage-tag]').on('click', function(e){
		var send = generate_object();
		var mytype = $(this).attr('data-manage-tag');
		send = JSON.stringify(send);
		$.ajax({
			url: _page.action.suggestedTags,
			data: {'send': send, 'action': mytype},
			method: 'POST',
			success: function(json){
				console.log(json)
				location.reload();
			}

		})
	});

	function generate_object(){
		var ar = [];
		$('input.inpt').each(function(){
			// quelli col check vanno nella lista
			if($(this).attr('checked')){
				var obj = {"tag": $(this).attr('name'), data: [] };
				var parent = $(this).closest('tr');
				$('[data-name="'+obj.tag+'"]').each(function(){
					console.log(obj.tag)
					var tmp = {
						'userid': $(this).attr('data-userid'),
						'offerid': $(this).attr('data-offerid')
					}
					obj.data.push(tmp);
				})
				ar.push(obj);
			}
		});
		console.log(ar);
		return ar;
	}

});

</script>


<?php
__fileEND:


$body = ob_get_contents();

ob_end_clean();

$body = '<div class="foowd-body foowd-page-suggestedTags">'.$body.'</div>';

echo elgg_view_page('SuggestedTags',$body);

