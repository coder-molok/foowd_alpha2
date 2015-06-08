<?php
// make sure only logged in users can see this page
gatekeeper();

$Pid = \Uoowd\Param::pid();
$form = $Pid.'/add';

// set the title
// for distributed plugins, be sure to use elgg_echo() for internationalization
$title = "Aggiungi la tua Offerta";

// start building the main column of the page
$content = elgg_view_title($title);


// metodo per istanziare la variabile $session se lo sticky esiste
// in particolare mi serve per l'array_merge prima di richiamare la view
($session = $_SESSION['sticky_forms'][$form]); 
// \Fprint::r($session);

$f = new \Foowd\Action\FormAdd();


// ricorda che questa funzione distrugge lo sticky form 
$vars = $f->prepare_form_vars($form);

///// preparo i valori da passare alla view
// $fadd->createField('Tag', 'Tags (singole parole separate da una virgola) *', 'input/text');

// mi serve perche' lo uso come default
$value = elgg_get_plugin_setting('tags', \Uoowd\Param::uid());
$value = json_decode($value);

// \Fprint::r($vars['Tag']);
$checkBox = array();

foreach($value as $category => $obj){
	// var_dump($category);
	$i = 0;
	foreach($obj as $single){
		if(in_array( $single, $vars['Tag'] )){
		    $checked = true;
		}else{
		    $checked = false;
		}	
		// $var_dump($single);
		$checkBox[$category][$i++] = array('tag'=>$single, 'checked'=>$checked);
	}
}


unset($session['Tag']);
$vars['Tag'] = $checkBox;
// per il css del box contenitore
$vars['TagAttributes'] = array('class' => 'foowd-Tag');

// altri valori utili per il form
$vars['guid']=elgg_get_logged_in_user_guid();
$vars['sticky']=$form;
$vars['tags'] = $value;

// salvo eventuali parametri di sessione, magari ritornati dalle mie action
$vars = array_merge($vars, (array) $session);
// var_dump($vars);

$content .= elgg_view_form($form, array('enctype'=>'multipart/form-data'), $vars);


// add the form stored in /views/default/forms/foowd_offerte/add.php
//$content .= elgg_view_form('foowd_offerte/add');

// optionally, add the content for the sidebar
$sidebar = "";

// layout the page one_sidebar
$body = elgg_view_layout('one_sidebar', array(
   'content' => $content
));

// draw the page
echo elgg_view_page($title, $body);

unset($_SESSION['sticky_forms'][$form]);
//var_dump($_SESSION['my']);
