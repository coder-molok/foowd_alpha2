<?php

admin_gatekeeper();

ob_start();

// elgg_unregister_menu_item('topbar', 'administration');

elgg_require_js('foowdServices');
?>

<script>

require(['foowdServices', 'jquery'], function(serv, $){
	var _page = $('.foowd-page-testPage');

	var obj = { offerId: 54, size: 'small' };
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


