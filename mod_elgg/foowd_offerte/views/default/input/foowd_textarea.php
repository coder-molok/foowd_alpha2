<?php
/**
 * Elgg long text input
 * Displays a long text input field that can use WYSIWYG editor
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['value']    The current value, if any - will be html encoded
 * @uses $vars['disabled'] Is the input field disabled?
 * @uses $vars['class']    Additional CSS class
 */
// \Fprint::r($vars);
if (isset($vars['class'])) {
	$vars['class'] = "elgg-input-longtext {$vars['class']}";
} else {
	$vars['class'] = "elgg-input-longtext";
}

$defaults = array(
	'value' => '',
	'rows' => '10',
	'cols' => '50',
	'id' => 'elgg-input-' . rand(), //@todo make this more robust
);

$vars = array_merge($defaults, $vars);

$value = $vars['value'];
unset($vars['value']);

// echo elgg_view_menu('longtext', array(
// 	'sort_by' => 'priority',
// 	'class' => 'elgg-menu-hz',
// 	'id' => $vars['id'],
// ));
?>
<!-- 
	di default mostro il texto visuale, ovvero come dovrebbe apparire nella pagina Html
	e offro l'opzione di visualizzarlo come entita' pre-codifica html
-->

<table class="foowd-editor-table">
	<tr>
		<td class="foowd-editor-actual"><span></span></td>
		<td class="foowd-editor-toggle"><a href=""></a></td>
	</tr>
	<tr>
		<td colspan="2">
		<textarea <?php /*echo elgg_format_attributes($vars);*/ echo $vars['name']; ?>>
		<?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?>
		</textarea>
		</td>
	</tr>
</table>

<?php
	echo elgg_view('input/hidden', array('name' => $vars['name'], 'class' => 'foowd-hidden-textarea-input'));
?>

<script type="text/javascript">
	
require([ 
	'jquery',
  ],function($){

  	// # Encode/decode htmlentities
  	krEncodeEntities = function(s){
  	    return $("<div/>").text(s).html();
  	}

  	// # da codice la trasforma in visualizzazione
  	krDecodeEntities = function(s){
  	    return $("<div/>").html(s).text();
  	}

  	var inpt = $('.foowd-hidden-textarea-input');

  	var check = $('table.foowd-editor-table')
  	var actual ={ 'el' : $('.foowd-editor-actual span'), 'toggle': ['Visualizzazione testo PIANO', 'Preview'] }
  	var toggle ={ 'el': $('.foowd-editor-toggle a'), 'toggle': ['Visualizza preview', 'Passa alla modalit&agrave; inserimento'] }
  	
  	var textarea = {'el' : $('table.foowd-editor-table textarea'), 'toggle': { 'disabled' : [false, true]},
  						'parent': $('table.foowd-editor-table textarea').parent()}
  	
  	var status = ['plain', 'html'];
  	var chgs = [actual, toggle];
  	
  	for(var i in chgs){
  		chgs[i].el.html(chgs[i].toggle[0]);
  		chgs[i].el.attr('data-toggle', 0 );
  		check.attr('status', 0);
  	}

  	// inizializzazioni
  	inpt.val(textarea.el.val().trim());
  	textarea.el.val(textarea.el.val().trim());

  	var chgEditor = $('table.foowd-editor-table .foowd-editor-toggle a')

  	chgEditor.on('click', function(e){
  		for(var i in chgs){
  			var obj = chgs[i];
  			var txt = obj.el.text();
  			var idx = (obj.el.attr('data-toggle') == 0) ? 1 : 0;
  			var txt = obj.toggle[idx];
  			obj.el.attr('data-toggle', idx);
  			obj.el.html(txt) 
  			check.attr('status', idx);
  			textarea.el.get(0).disabled = textarea.toggle.disabled[idx]
  		}
  		adaptContent(idx);
  		e.preventDefault();
  	});

  	textarea.el.on('keyup', function(e){ sanitizeInput(e, $(this));} );

  	textarea.el.on('paste', function(e){ 
  		(function(e, Jel){
  			setTimeout(function(){
	  			sanitizeInput(e, $(this))
	  		}, 100);
  		})(e, $(this));
  	});

  	// var sanitizeAr = [
  	// 	['<', '&lt;'],
  	// 	['>', '&gt;'],
  	// ];

  	var recursiveReplace =  function(str, path, replace){
  		var rx = new RegExp(path, 'gi');
  		return str.replace(rx, replace);
  	}

  	var sanitizeInput = function(e, Jel){
  		if(check.attr('status') != 0){

  		}else{
	  		if(e.ctrlKey || e.shiftKey) return;
	  		var txt = Jel.val();
	  		// txt = txt.trim();
	  		Jel.val(txt);
	  		inpt.val(txt);
  		}
  	}
  	

  	// inpt.val('bella zio')
  	// inpt.html('bella <br/><b>zio</b>')		
  	// inpt.text('bel<br/>la <br/><b>zio</b>') 

  	// alert(inpt.val())		
  	// alert(inpt.html())		// visualizza testo encoded tipo &lgt;
  	// alert(inpt.text())		// visualizza cosi' com'e'
  	
  	var adaptContent = function(status){

  		var Jel = textarea.el;

  		if(status == 0) {
  			textarea.parent.html(textarea.el)
  		}else{
  			// alert(Jel.val())
  			// Jel.html(Jel.val())
  			var tmpTxt = textarea.el.val();
  			textarea['tmpTxt'] = tmpTxt;
  			textarea.el.detach();
  			var el = $('<div/>', {'class':'div_textarea'}).html(tmpTxt);
  			textarea.parent.html(el);
  		}


  	}

  	
	
});


</script>
