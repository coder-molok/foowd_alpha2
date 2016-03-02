<?php

admin_gatekeeper();

ob_start();

// ottengo il logger: inserito nella factory !!!
// see http://reference.elgg.org/ServiceProvider_8php_source.html
// $logger = _elgg_services()->__get('logger');
// $levels = array(
// 	0 => 'OFF',
// 	200 => 'INFO',
// 	250 => 'NOTICE',
// 	300 => 'WARNING',
// 	400 => 'ERROR',
// );
// echo "Log level: " . $levels[$logger->getLevel()];





// \Fprint::r($this);


//////////////////////////////////////////////////////////////////
// test permissions
// $dirTest =  __DIR__.'/'.time();
// echo $dirTest;
// if (!mkdir($dirTest)) {
//     die('Failed to create folders...');
// }
// user php is running
// echo exec('whoami');
// echo 'Current script owner: ' . get_current_user();

// elgg_unregister_menu_item('topbar', 'administration');

/////////////////////////////////////////////////////////////////
// cookie di sessione PHP e JAVASCRIPT

\Fprint::r($_SESSION);

\Fprint::r($_COOKIE);

?>

<div id = "cookie-javascript"></div>
<script>
var cookie = document.cookie;
var el = document.getElementById('cookie-javascript');
el.innerHTML = cookie;
</script>

<?php
//////////////////////////////////////////////////////////////////
// Trigger cronjob
$cron = array('minute', 'fiveminute', 'fifteenmin', 'halfhour', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'reboot');
echo "<ul>";
foreach($cron as $c){
	$str = '<li><a class="cron-trigger" href="%scron/%s">cron %s</a></li>';
	echo sprintf($str, elgg_get_site_url(), $c, $c);
}
echo "<ul>";
elgg_require_js('jquery');
?>
<script type="text/javascript">
require(['jquery'], function($){
	$('.cron-trigger').on('click', function(e){
		var url = $(this).attr('href');
		$.ajax({'url':url, 'type': 'get'});
		e.preventDefault();
	}); 
});	
</script>
<?php




////////////////////////////////////////////////////////////////////
/// Test API Elgg
elgg_require_js('foowdServices');

?>

<script>

require(['foowdServices', 'jquery'], function(serv, $){
	var _page = $('.foowd-page-testPage');

	var obj = { offerId: 3, size: 'small' };
	$.when(serv.getPictureUrl(obj)).then(function(data){
		// alert('done')
		console.log(data)
		console.log(data.result.picture)
		_page.append('<img src="'+data.result.picture+'"/>')
	});

	$.ajax({
		url: 'http://5.196.228.146/elgg-1.10.4/services/api/rest/json/?method=foowd.user.friendsOf&guid=54',
		type: 'GET',
		success: function(data){
			console.log(data)
		},
		error: function(a, b, c){
			console.log(a)
			console.log(b)
			console.log(c)
			alert(a.responseText)
		}
	})



	// $.when(serv.getFriendsOf()).then(function(data){
	// 	// alert('done')
	// 	console.log(data)
	// 	console.log(data.result)
	// 	// $('body').append('<img src="'+data+'"/>')
	// })
});

</script>


<?php
//////////////////////////////////////////////////////////////////////
/// Test document ready
?>
<script type="text/javascript">
	require(['jquery'], function($){
		$ = require('jquery');

		// var pro = $.Deferred(function() { $(this.resolve); }).promise();

		// setTimeout(function(){
		// 	pro.then(function(){alert(document.readyState)})
		// }, 10000)
		// setTimeout(function(){
		// 	// viene triggerata quando lo stato passa a complete
		// 	console.log('onready');
		// 	$(document).on('ready',function(){ alert(document.readyState);});
		// 	// viene triggerata in ogni caso, come una promise
		// 	console.log('ready');
		// 	$(document).ready(function(){ alert(document.readyState);});
		// },10000);
	});
</script>


<?php

$body = ob_get_contents();
ob_end_clean();

echo elgg_view_page($title, '<div class="foowd-page-testPage">'.$body.'</div>');


