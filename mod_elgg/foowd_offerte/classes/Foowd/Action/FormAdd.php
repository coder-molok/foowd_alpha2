<?php

namespace Foowd\Action;

	class FormAdd extends \Uoowd\Action{
		
		/**
		 * variabile per associare a ogni input il messaggio predefinito e 
		 * riprodurre la struttura dello schema
		 *
		 * I nomi dei campi DEVONO essere i metodi utilizzati via propel
		 * 
		 * @var array
		 */
		private $par = array(
			'Id'			=> '',		
			'Name' 			=> 'immetti titolo...',
			'Description'	=> 'inserire descrizione',
			'Publisher'		=> '',		
			'Price'			=> '',
			'Minqt'			=> '',
			'Maxqt'			=> '',
			'Created'		=> '',
			'Modified'		=> '',
			'State'			=> '',
			'Unit'			=> '',
			'UnitExtra'		=> '',
			'Quota'			=> '',
			'Expiration'	=> '',
			'Tag'			=> '', 	// extra non appartenente alla tabella sql,
									// ma in ogni caso necessario nella compilazione del form

		);


		private $needle = array(
			"Price",
			"Tag",
			"Minqt",
			"Maxqt"
		);

		/**
		 * variabile per associare a ogni input il messaggio di errore
		 * @var array
		 */
		private $errors = array(
			'Name' 			=> 'foowd:name:error',
			'Description'	=> 'foowd:description:error',
			'Price'			=> 'foowd:price:error',
			// 'Tag'			=> 'i tags possono essere solo singole parole separate da virgola...',
			'Tag'			=> 'foowd:tag:error',
			'Minqt'			=> 'foowd:minqt:error',
			'Maxqt'			=> 'foowd:maxqt:error',
			'Quota'			=> 'foowd:quota:error',
		);

		/**
		 * variabile per associare a ogni input la corrispettiva funzione di check
		 * @var array
		 */
		private $check = array(
			'Price'		=> 'isCash',
			'Tag'		=> 'isTag',
			'Minqt'		=> 'isQt',
			// 'Maxqt'		=> 'isMax',
			'Quota'		=> 'isQt',
			'Expiration'=> 'isDateTime'
		);

		
		public function __construct(array $ar = null){
			// passo i parametri al padre
			 parent::__construct(get_object_vars($this), $ar);
		}

		/**
		 * NB: 	lo usavo per svolgere una premodifica sui dati
		 * 		da inviare alle API. 
		 * 		E' utile tenerlo come promemoria.
		 * 
		 * metodo per convertire i campi di spinner in un unica cifra razionale
		 * @param  [type] $sticky_form [description]
		 * @return [type]              [description]
		 */
		// public function hookManage($sticky_form){
		// 	$numeric = array("Price", "Minqt","Maxqt");
		// 	// \Uoowd\Logger::addNotice('$quantity');
		// 	foreach($numeric as $key){
		// 		$set = 1; // as true; 0 as false
		// 		if(get_input($key.'-integer')===""){
		// 			$set *= 0;
		// 		}
		// 		if($set){
		// 			// imposto i valori di input
		// 			if(get_input($key.'-decimal') === "") set_input($key.'-decimal', 0);
		// 			$quantity = get_input($key.'-integer').'.'.get_input($key.'-decimal');
		// 			set_input($key,  $quantity);
		// 			// imposto i valori dello sticky form
		// 			$this->manageSticky(array($key=>$quantity), $sticky_form);
		// 		}
		// 	}
		// }

		public function hookCreateTag($type , $input){
			// echo '<div '.elgg_format_attributes($input['attributes']).' >';
			// foreach ($input['inputs'] as $val){
			// 	echo elgg_view($type, $val);
			// }
			// echo '</div>';
			
			$css_url = 'mod/foowd_utility/bower_components/chosen/chosen.min.css';
			elgg_register_css('chosen-css', $css_url);
			elgg_load_css('chosen-css');


			// set the path, define its dependencies, and what value it returns
			// elgg_define_js('jquery.chosen', [
			//     'src' => '/mod/foowd_utility/bower_components/chosen/chosen.jquery.min.js',
			//     'deps' => array('jquery'),
			//     'exports' => 'jQuery.fn.chosen',
			// ]);
			// elgg_require_js('jquery.chosen');

			// elgg_define_js('jquery.chosen');
			elgg_register_js('jquery.chosen', '/mod/foowd_utility/bower_components/chosen/chosen.jquery.min.js');
			elgg_load_js('jquery.chosen');
			// echo '<script src="/mod/foowd_utility/bower_components/chosen/chosen.jquery.min.js"></script>';
			// $tags = elgg_get_plugin_setting('tags', \Uoowd\Param::uid());
			// $tags = json_decode($tags);
			// var_dump($tags);

			// eventualmente al posto di chosen
			// 		http://travistidwell.com/jquery.treeselect.js/
			echo '<div id="chosen-container">';
			echo '<select data-placeholder="Seleziona i Tag che vuoi inserire" style="width:350px;" class="chosen-select" multiple tabindex="6">'
			      .'<option value=""></option>';

			$i = 0;
			$tags = $input['inputs'];
			$hiddenInput= array();
			foreach($tags as $category => $obj){
			    // echo "$category \n";
			    echo '<optgroup label="'.$category.'">';

			    foreach ($obj as $single) {
			        if($single['checked']){
			        	$attr ='selected';
			        	$field = '<input type="hidden" name="Tag[]" chosen-hook="'.$i.'" value="'.$single['tag'].'"/>';
			        	array_push($hiddenInput, $field);
			        }else{
			        	$attr = '';
			        }

			        echo '<option value="'.$i.'" '.$attr.'>'.$single['tag'].'</option>';


			        $i++;
			    }
			    echo '</optgroup>';
			}
			  echo '</select>';

			  // stampo gli input hidden
			  foreach($hiddenInput as $field){
			  	echo $field;
			  }

			  echo '</div>';
			?>

			<script>
			  // jQuery('body').css('background-color', 'red');
			  $(".chosen-select").chosen();

			  $('.chosen-select').on('change', function(evt, params) {
			      // do_something(evt, params);
			      $(this).css({'background-color': 'red'});
			      // alert($(this).val());
			      // console.log(JSON.stringify(evt, null, 4));
			      // console.log(JSON.stringify(params, null, 4))
			      // console.log(params.selected);
			      // se selezionato, aggiungo un input col suo valore
			      if(params.selected){
			        var Jel = $('.chosen-select option[value="'+params.selected+'"]')
			        Jel.css('background-color','green')
			        var tag = Jel.text();
			        $('<input/>',{
			            'name':'Tag[]',
			            'chosen-hook': params.selected, // inventato da me
			            'value': tag,
			            'type': 'hidden'
			        }).appendTo('#chosen-container');

			      } 

			      if(params.deselected){
			        var Jel = $('#chosen-container input[chosen-hook="'+params.deselected+'"]');
			        Jel.remove();
			      } 

			    // per collaborare con gli altri plugin
        		$( document ).trigger( "foowd:update:tag" );

			    });

			</script>
			<?php

		}

		/**
		 * Schema per il timedatepicker, ovvero per la scadenza
		 * @param  [type] $a    [description]
		 * @param  [type] $vars [description]
		 * @return [type]       [description]
		 */
		public function hookCreateExpiration($a, $vars){
			// \Fprint::r(func_get_args());
			elgg_load_css('jquery.datetimepicker');
			elgg_require_js('jquery.datetimepicker');
			?>
			<script>
			    requirejs(['jquery.datetimepicker'], function(){
			        var Gdate = {} ; // oggetto per memorizzare i parametri di mio interesse
			        var Gdiv = $('[name="Expiration"]');
			        var Gdt = new Date();
			        Gdt.setTime(Gdt.getTime() + (24 * 60 * 60 * 1000));
			        // inserisco lo zero davanti alle cifre a una unita'
			        var sanitize_Date = function(obj){
			        	for(var i in obj){
			        		if(i !== 'Y') obj[i] = ('0' + obj[i]).slice(-2);
			        	}
			        }

			        // con afterInject riesco sempre a ottenere l'istanza, 
			        $('#datepicker').datetimepicker({
			            timeFormat: "HH:mm",
			            currentText: 'adesso',
			            separator: ' @ ',
			            dateFormat: 'dd MM yy',
			            showButtonPanel: true, 
			            defaultValue: Gdiv.val(),
			            parse: 'loose',
			            minDate: Gdt, // la scadenza e' almeno al giorno dopo!
			            // quando chiudo la finestra aggiorno i dati nel div
			            onSelect: function(datetimeText, datepickerInstance){
			                var inst = datepickerInstance;
			                Gdate.Y = inst.selectedYear || Gdate.Y
			                Gdate.M = inst.selectedMonth || Gdate.M // incremento perche' il plugin fa partire i mesi da zero
			                Gdate.D = inst.selectedDay || Gdate.D
			                Gdate.h = inst.hour || Gdate.h
			                Gdate.m = inst.minute || Gdate.m
			                Gdate.s = inst.second || Gdate.s
			                sanitize_Date(Gdate);
			                var M = parseInt(Gdate.M) + 1;
			                M = ('0' + M).slice(-2);
			                var str =  Gdate.Y + "-" + M + "-" + Gdate.D + " " + Gdate.h + ':' + Gdate.m + ":" + Gdate.s;
			                Gdiv.val(str);        
			            },
			            // memorizzo i dati presenti quanto apro la finestra
			            afterInject: function(){
			                var t = this, i = t.inst;
			                Gdate.Y = i.selectedYear, Gdate.M = i.selectedMonth, Gdate.D = i.selectedDay;
			                Gdate.h = t.hour, Gdate.m = t.minute, Gdate.s = '00';
			            }
			        });
			    })
			</script>
			<input type="text" id="datepicker" value="<?php echo $vars['Expiration']; ?>"/>
			<input type="hidden" name="Expiration" value="<?php echo $vars['Expiration']; ?>"/>
			<?php
		}


	}


