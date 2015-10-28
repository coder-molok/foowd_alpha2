<?php

admin_gatekeeper();

ob_start();

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

$body = ob_get_contents();
ob_end_clean();

echo elgg_view_page($title, '<div class="foowd-page-testPage">'.$body.'</div>');


