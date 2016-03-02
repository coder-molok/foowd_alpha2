// in user-register ho creato un comando che inibisce tutti gli eventi sugli elementi del form,
// pertanto se voglio apppendere degli eventi agli elementi, devo essere sicuro di farlo DOPO l'inibizione. Per questo ho caricato user-register
define(['jquery', 'elgg', 'foowdServices', 'foowd_utenti/foowd-user-settings-edit'], function($, elgg, _service){

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
		update_fields(_addss);
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


	function update_fields(obj){
		var $hook = $('[name="cityValueHook"]');
		if($hook.length == 0) return false;
		var city = $hook.val();
		var o = {code: city};

		// estraggo i dati che mi occorrono
		var ret = true;
		$.each(obj, function(idx, val){
			var _reg = idx;
			$.each(val, function(id,va){
				var _prov = id;
				$.each(va, function(i, v){
					if(v.code == o.code){
						o.reg = _reg;
						o.prov = _prov;
						o.city = v.name;
						ret = false; 
						return ret;
					}
				});
				return ret;
			});
			return ret;
		});

		$region.find('option[value="'+o.reg+'"]').prop('selected', 'true').trigger('change');
		$province.find('option[value="'+o.prov+'"]').prop('selected', 'true').trigger('change');
		$city.find('option[value="'+o.code+'"]').prop('selected', 'true').trigger('change');

	}

});