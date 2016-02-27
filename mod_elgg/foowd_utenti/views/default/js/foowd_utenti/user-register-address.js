// in user-register ho creato un comando che inibisce tutti gli eventi sugli elementi del form,
// pertanto se voglio apppendere degli eventi agli elementi, devo essere sicuro di farlo DOPO l'inibizione. Per questo ho caricato user-register
define(['jquery', 'elgg', 'foowdServices', 'foowd_utenti/user-register'], function($, elgg, _service){

	// var _baseUrl = elgg.get_site_url() + _page.utility.addresses;

	// riempio
	function write_opt(val, display){
		display = display || val ;
		return '<option value="' + val + '">' + display + '</option>';	
	}



	var _addss = '';
	var $region = $('select[name="Region"]');
	var $province = $('select[name="Province"]');
	var $city = $('select[name="City"]');

	var req = { method: 'foowd.mixed.territory'	};

	_service.getRequest(req).done(function(data){
		_addss = data.result;
		$.each(_addss, function(idx,val){
			$region.append(write_opt(idx));
		});

	});


	// quando seleziono una regione, riempio le province
	$region.on('change',  function(){
		// svuoto nel caso vi fossero valori
		$province.html(write_opt('_none_', 'seleziona provincia'));
		$city.html(write_opt('_none_', 'seleziona comune'));
		var actual = $(this).val();
		$.each(_addss[actual], function(idx, val){
			$province.append(write_opt(idx));
		});
	});

	// quando seleziono una regione, riempio le province
	$province.on('change',  function(){
		$city.html(write_opt('_none_', 'seleziona comune'));
		var actual = $(this).val();
		$.each(_addss[$region.val()][actual], function(idx, val){
			$city.append(write_opt(val.code, val.name));
		});
	});

});