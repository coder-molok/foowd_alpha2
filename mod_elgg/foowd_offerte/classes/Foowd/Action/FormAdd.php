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

			'Tag'			=> '', 	// extra non appartenente alla tabella sql,
									// ma in ogni caso necessario nella compilazione del form

		);


		private $needle = array(
			"Price",
			"Tag",
			"Minqt"
		);

		/**
		 * variabile per associare a ogni input il messaggio di errore
		 * @var array
		 */
		private $errors = array(
			'Name' 			=> 'errore nell\' immisione del titolo',
			'Description'	=> 'errore nell\' immisione della descrizione',
			'Price'			=> 'massimo 8 cifre + 2 decimali...',
			// 'Tag'			=> 'i tags possono essere solo singole parole separate da virgola...',
			'Tag'			=> 'devi selezionare almeno un tag',
			'Maxqt'			=> 'la quantita\' massima deve superare o eguagliare quella minima.<br/>Se non vuoi inserire un massimo, cancella i numeri dal campo sottostante. ',
		);

		/**
		 * variabile per associare a ogni input la corrispettiva funzione di check
		 * @var array
		 */
		private $check = array(
			'Price'		=> 'isCash',
			'Tag'		=> 'isTag',
			'Maxqt'		=> 'isMax'
		);

		
		public function __construct(array $ar = null){
			// passo i parametri al padre
			 parent::__construct(get_object_vars($this), $ar);
		}

		/**
		 * metodo per convertire i campi di spinner in un unica cifra razionale
		 * @param  [type] $sticky_form [description]
		 * @return [type]              [description]
		 */
		public function manageInput($sticky_form){
			$numeric = array("Price", "Minqt","Maxqt");
			// \Uoowd\Logger::addNotice('$quantity');
			foreach($numeric as $key){
				$set = 1; // as true; 0 as false
				if(get_input($key.'-integer')===""){
					$set *= 0;
				}
				if($set){
					// imposto i valori di input
					if(get_input($key.'-decimal') === "") set_input($key.'-decimal', 0);
					$quantity = get_input($key.'-integer').'.'.get_input($key.'-decimal');
					set_input($key,  $quantity);
					// imposto i valori dello sticky form
					$this->manageSticky(array($key=>$quantity), $sticky_form);
				}
			}
		}

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
			      console.log(params.selected);
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

	}


