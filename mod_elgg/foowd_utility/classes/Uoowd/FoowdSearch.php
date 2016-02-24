<?php

// views di interesse in foowd_theme ed eventualmente da sovrascrivere:
// search/no_results.php
// search/search_box.php


namespace Uoowd;

class FoowdSearch{

	public static $search = array(
			'foowd-username-email'
		);

	public static function register(){

		elgg_register_plugin_hook_handler('search_types', 'get_queries', array('\Uoowd\FoowdSearch', 'get_queries') );
		elgg_register_plugin_hook_handler('search_types', 'get_types', array('\Uoowd\FoowdSearch', 'get_types') );
		elgg_register_plugin_hook_handler('search', 'foowd-username-email', array('\Uoowd\FoowdSearch', 'get_search') );

		// menu sidebar
		elgg_register_plugin_hook_handler("register", "menu:page", array('\Uoowd\FoowdSearch', 'menu_search') );

		//prepends al search_box 
		elgg_extend_view('search/search_box', 'search/search_box_prepend', 450);
		
	}


	// aggiungere controllo su context o params
	public static function get_queries($hook, $type, $return, $params){
		// evito:
		// 1 - che vengano svolte le query
		// 2 - che le pagine su cui si fanno le query finiscano nella sidebar
		return array();
		// return $return;
	}


	// Set $custom_types in "search" plugin
	// aggiungo per ciascun valore in self::$search la possibilita' di essere utilizzato come parametro
	// La sua ricerca viene poi implementata con l'handler search, type , dove type e' appunto l'elemento di questo array
	public static function get_types($hook, $type, $return, $params){
		$return = array_merge($return, self::$search);
		// \Fprint::r($return);
		return $return;
	}

	public static function menu_search($hook, $type, $return, $params) {
	    // echo '<style>.elgg-sidebar{width: 500px;}</style>';
	    $accepted = array();
	    foreach(self::$search as $val){
	    	$accepted['search_types:'.$val] = $val;
	    }

	    foreach($return as $key => $item) {

	    	// rimuovo "all" perche' attualmente la ricerca non e' estesa
	    	if($item->getName() === 'all' ) unset($return[$key]);

	    	if(!preg_match('@search_types@', $item->getName())) continue;
	        if (array_key_exists($item->getName(), $accepted)) {
	            // $return[$key]->name = 'lal';
	        }else{
	        	unset($return[$key]);
	        }
	    }

	    return $return;
	}


	/**
	 * ricerca standard su foowd-username-email: guarda i match su email, username o name
	 * @param  [type] $hook   [description]
	 * @param  [type] $type   [description]
	 * @param  [type] $value  [description]
	 * @param  [type] $params [description]
	 * @return [type]         [description]
	 */
	public static function get_search($hook, $type, $value, $params){

		$db_prefix = elgg_get_config('dbprefix');

		// da url ritorna a normale stringa: ad esempio da url %40 diviene @. Query e' il valore di "q": ?q=...
		$query = sanitise_string($params['query']);

		// Imposto i permessi d'accesso per la lettura di TUTTI gli utenti, admins inclusi
		elgg_set_ignore_access(true);

		// recupero tutti gli utenti, amministratori inclusi
		$admins =elgg_get_admins();
		$users = elgg_get_entities(array('types'=>array('user','admins') , 'limit' => 0) );
		$allUsers = array_merge($admins, $users);
		// \Uoowd\Logger::addError(count($allUsers));

		$entities = array();
		$skip = elgg_get_logged_in_user_entity()->guid;
		foreach ($allUsers as $single) {
			// salto l'utente attuale
			if($skip == $single->guid) continue;
			// \Uoowd\Logger::addError($single->email);
			$str = $single->email.$single->username.$single->name;
			$match = '@' . $query . '@i';
			if(preg_match($match, $str)) $entities[$single->guid] = $single;
		}
		// ripristino l'accesso
		elgg_set_ignore_access(false);
		$count = count($entities);


		// // aggiungo un controllo sulla mail per velocizzare
		// // se cerca per email
		// if(preg_match('|@|', $query) ){
		// 	$entities = get_user_by_email($query);
		// 	// per il goto devono essere definiti $entities e $count
		// 	$count = count($entities);
		// 	/*if($count > 0)*/ goto __FOOWD_FOUND_EMAIL;
		// }

		// $params['joins'] = array(
		// 	"JOIN {$db_prefix}users_entity ue ON e.guid = ue.guid",
		// );
			
		// // username and display name
		// $fields = array('username', 'name');
		// $where = search_get_where_sql('ue', $fields, $params, FALSE);

		// // profile fields
		// $profile_fields = array_keys(elgg_get_config('profile_fields'));
		
		// if (!empty($profile_fields)) {
		// 	$params['joins'][] = "JOIN {$db_prefix}metadata md on e.guid = md.entity_guid";
		// 	$params['joins'][] = "JOIN {$db_prefix}metastrings msv ON n_table.value_id = msv.id";
			
		// 	// get the where clauses for the md names
		// 	// can't use egef_metadata() because the n_table join comes too late.
		// 	$clauses = _elgg_entities_get_metastrings_options('metadata', array(
		// 		'metadata_names' => $profile_fields,
		// 	));
		
		// 	$params['joins'] = array_merge($clauses['joins'], $params['joins']);
		// 	// no fulltext index, can't disable fulltext search in this function.
		// 	// $md_where .= " AND " . search_get_where_sql('msv', array('string'), $params, FALSE);
		// 	$md_where = "(({$clauses['wheres'][0]}) AND msv.string LIKE '%$query%')";
			
		// 	$params['wheres'] = array("(($where) OR ($md_where))");
		// } else {
		// 	$params['wheres'] = array("$where");
		// }
		
		// // override subtype -- All users should be returned regardless of subtype.
		// $params['subtype'] = ELGG_ENTITIES_ANY_VALUE;
		// $params['count'] = true;
		// $count = elgg_get_entities($params);


		// // no need to continue if nothing here.
		// if (!$count) {
		// 	return array('entities' => array(), 'count' => $count);
		// }
		
		// $params['count'] = FALSE;
		// $params['order_by'] = search_get_order_by_sql('e', 'ue', $params['sort'], $params['order']);
		// $entities = elgg_get_entities($params);

		// __FOOWD_FOUND_EMAIL:
		// // \Fprint::r($entities);
		// // \Fprint::r($count);

		// // add the volatile data for why these entities have been returned.
		foreach ($entities as $entity) {
			
			$title = search_get_highlighted_relevant_substrings($entity->name, $query);

			// include the username if it matches but the display name doesn't.
			if (false !== strpos($entity->username, $query)) {
				$username = search_get_highlighted_relevant_substrings($entity->username, $query);
				$title .= " ($username)";
			}

			$entity->setVolatileData('search_matched_title', $title);

			if (!empty($profile_fields)) {
				$matched = '';
				foreach ($profile_fields as $md_name) {
					$metadata = $entity->$md_name;
					if (is_array($metadata)) {
						foreach ($metadata as $text) {
							if (stristr($text, $query)) {
								$matched .= elgg_echo("profile:{$md_name}") . ': '
										. search_get_highlighted_relevant_substrings($text, $query);
							}
						}
					} else {
						if (stristr($metadata, $query)) {
							$matched .= elgg_echo("profile:{$md_name}") . ': '
									. search_get_highlighted_relevant_substrings($metadata, $query);
						}
					}
				}
		
				$entity->setVolatileData('search_matched_description', $matched);
			}
		}


		return array(
			'entities' => $entities,
			'count' => $count,
		);
	} // end function get_search




}



/* PROMEMORIA DI SEARCH ADVANCED */

/*
<!-- wrap plugin advanced - search per le opzioni sulle entita' per le quali effettura la ricerca -->
<!-- ulteriori wrap di questo menu si trovano nello start.php di questo plugin -->
<script>
	$(function(){
		// predispongo gli input affinche' la ricerca avvenga solo sugli utenti, escludendo cos= commenti, gruppi, etc.
		var sType = $('input[name="serach_type"]');
		sType.removeAttr('disabled');
		var eType = $('input[name="entity_type"]');
		eType.removeAttr('disabled');
		eType.attr('val', 'user');
		var Jel = $('ul.search-advanced-type-selection');
		
		// personalizzo la scritta: 
		// cosi' facendo rimuovo il dorp-down menu dal quale sarebbe possibile selezionare l'entita' su cui effettuare la ricerca, come utenti, gruppi, commenti, etc.
		Jel.html('Trova amici');
		Jel.css({
			'background-color': 'rgb(200, 16, 99)',
			'padding':'2px 7px 0px 3px',
			'color' : 'white',
			'width': '73px',
			'font-size': '0.9em'
		})

		// rimuovo la scritta "tutto dal menu"
		// cliccandola effettuerebbe la ricerca su tutte le entita' come i gruppi
		$('li.elgg-menu-item-all').remove();
	});
</script>
*/


/* styl di SEARCH ADVANCED */

/*
// plugin search-advanced
searchHeight = 19px
form.elgg-search
	border 1px solid foowdTopbar
	height (searchHeight + 1px)

.elgg-search-header
	position: relative
	
.search-advanced-type-selection > li > a
	background-color foowdTopbar
	height: searchHeight
	
.elgg-search 
	
	input[type="text"]
		
		&.search-input
			border 0px
			width: 100%
			background-position right -917px // e' uno sprite
			background-color white

		&:focus, &:active
			color foowdTopbar
			border 1px solid foowdTopbar
			
	
	
.search-advanced-type-selection-dropdown
	border 0px
	
	a	
		&:hover
			background-color foowdTopbar
	
 */


/*** tutorial di sviluppo

da elenco plugin: rimossa parte relativa a advanced search

*/