<?php

// TODO
// inserire controllo utenti gia' iscritti
ob_start();


// var_dump($_SESSION['sticky_forms']['foowd-avatar']);
unset($_SESSION['sticky_forms']['foowd-avatar']);

// $vars['guid']=elgg_get_logged_in_user_guid();

$auth = new \Foowd\HyAuth();

// Facebook invite: per usare la canvas ho bisogno di ssl, ovvero protocollo https
// una volta ottenuta la imposto da settings > + add platform
// vedi https://subinsb.com/add-invite-facebook-friends-in-website


$adapter = $auth->getAdapter('Google');

// Facebook
// $response = $adapter->api()->api('/me/friends');
// $response = $adapter->api()->api('https://www.googleapis.com/plus/v1/people/me/people/visible', 'GET');
// \Fprint::r($response);

// $user_contacts = $adapter->getUserContacts();
// \Fprint::r($user_contacts);

// \Fprint::r($adapter);

?>
<script src="https://apis.google.com/js/platform.js" async defer></script>
<g:plus action="share"></g:plus>

<a href="https://plus.google.com/share?url=<?php echo elgg_get_site_url();?>" onclick="javascript:window.open(this.href,
  '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><img
  src="https://www.gstatic.com/images/icons/gplus-64.png" alt="Share on Google+"/></a>
<?php

$body = ob_get_contents();

ob_end_clean();

$body = '<div class="foowd-page-social">'.$body.'</div>';

echo elgg_view_page('Social',$body);