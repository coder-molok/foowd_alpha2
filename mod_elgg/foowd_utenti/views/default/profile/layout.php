<?php

/**
 * questo script sovrascrive la view del plugin "profile" (nativo di elgg).
 */


/**
 * Profile owner block
 */

$user = elgg_get_page_owner_entity();

if (!$user) {
	// no user so we quit view
	echo elgg_echo('viewfailure', array(__FILE__));
	return TRUE;
}

// $icon = elgg_view_entity_icon($user, 'large', array(
// 	'use_hover' => false,
// 	'use_link' => false,
// 	'img_class' => 'photo u-photo',
// ));

// grab the actions and admin menu items from user hover
// NB: questo menu viene anche utilizzato da "friend_request"
$menu = elgg_trigger_plugin_hook('register', "menu:user_hover", array('entity' => $user), array());
$builder = new ElggMenuBuilder($menu);
$menu = $builder->getMenu();
// estraggo le sezioni dal menu
$actions = elgg_extract('action', $menu, array());
$admin = elgg_extract('admin', $menu, array());

$profile_actions = '';
if (elgg_is_logged_in() && $actions) {
	$profile_actions = '<ul class="elgg-menu profile-action-menu mvm">';
	foreach ($actions as $action) {
		$item = elgg_view_menu_item($action, array('class' => 'elgg-button elgg-button-action'));
		$profile_actions .= "<li class=\"{$action->getItemClass()}\">$item</li>";
	}
	$profile_actions .= '</ul>';
}

// if admin, display admin links
$admin_links = '';
if (elgg_is_admin_logged_in() && elgg_get_logged_in_user_guid() != elgg_get_page_owner_guid()) {
	$text = elgg_echo('admin:options');

	$admin_links = '<ul class="profile-admin-menu-wrapper">';
	$admin_links .= "<li><a rel=\"toggle\" href=\"#profile-menu-admin\">$text&hellip;</a>";
	$admin_links .= '<ul class="profile-admin-menu" id="profile-menu-admin">';
	foreach ($admin as $menu_item) {
		$admin_links .= elgg_view('navigation/menu/elements/item', array('item' => $menu_item));
	}
	$admin_links .= '</ul>';
	$admin_links .= '</li>';
	$admin_links .= '</ul>';	
}

// content links
// $content_menu = elgg_view_menu('owner_block', array(
// 	'entity' => elgg_get_page_owner_entity(),
// 	'class' => 'profile-content-menu',
// ));

$imgs = \Uoowd\API::pathPics($user->guid);

if(!$imgs->avatar){
	$avatar = null; // usare icona di default
	$icon = elgg_view_entity_icon($user, 'medium');
}
else{
	foreach ($imgs->avatar as $value) {
			if(preg_match('@avatar(\\\|/)small@i', $value)){
				$avatar = $value;
			}
		}	
	//\Fprint::r($url);
	$url = elgg_get_site_url() . \Uoowd\Param::page()->foowdStorage . 'User-' . $user->guid . '/' . $avatar;
	$icon = '<img src="' .$url.'"/>';
}


elgg_require_js('foowdServices');

echo <<<HTML


<div class="circular">
	$icon
</div>
<!-- previous id="profile-owner-block" -->
<center>
<div  class="foowd-profile-style">
	$profile_actions
	$content_menu
	$admin_links
</div>
</center>

<script type="text/javascript">
require([ 
    'foowdServices'
  ],function(serv){
    
   

 });
</script>
HTML;
