define(function(require){

	var $ = require('jquery');
	var elgg = require('elgg');

	var NavbarSearch = function(){

	}

	NavbarSearch.prototype.init = function(){
		this.manageSearchText();
	}


	/**
	 * funzione che realizza l'effetto sul campo di ricerca:
	 * rimpiazza il concetto di campo input, perche' su tal tag non e' possibile inserire elementi html, ma solo testuali
	 * @return {[type]} [description]
	 */
	// se il plugin viene caricato piu volte, c'e' il rischio che gli eventi $(document).on si accumulino, ripetendosi piu volte per singola pressione
	__countManageSearch = 0;
	NavbarSearch.prototype.manageSearchText = function(){

		// per il momento la uso solo nel wall
		if( elgg.get_site_url() != window.location.toString() ) return;
		// garantisco che venga caricata una sola volta per istanza
		if(__countManageSearch >0) return;
		__countManageSearch++;

		// mi serve perche' da esso rimuovo la classe "pulsate" per l'effetto sull'underscore
		var $box = $('.foowd-brand');
		// scritta foowd_ : triggera anche il click per andare alla homepage
		var $pre = $('.foowd-brand-pre-search');
		// campo search
		var $search = $('#searchText').first();
		var $searchLoom = $('#searchText-loom').first();
		var tags = '[data-tag]';
		var pulsationSpan = 'foowd-pulsate';
		var underscoreSpan = '<span class="underscore-search">_</span>';
		var pointer = '<span class="pointer-search">|</span>';

		$(document).on('keydown', function(e){
			if ($(e.target).is('input, textarea')) {
			    return;   
			}
			var code = (e.keyCode) ? e.keyCode : e.which ;
			// 8 e' il codice del backspace: devo impedire che avvenga il back della history del browser
			// 32 e' il codice dello space: quando lo si clicca avviene lo scroll, che non serve
			if( code == 8 || code == 32 ) e.preventDefault() ;
		});

		var _newTag = 0;
		$(document).on('keyup', function(e){
			if ($(e.target).is('input, textarea')) {
			    return;   
			}
			// valore rilasciato
			var code = (e.keyCode) ? e.keyCode : e.which ;
			var c = String.fromCharCode(code).replace(/[^a-zA-Z0-9]+/g, "").toLowerCase();
			//************ settaggi iniziali
			// rimuovo la classe per poi appenderla all'ultimo underscore
			$box.find('.foowd-pulsate').removeClass('foowd-pulsate');
			$search.find('.underscore-search').remove();
			$('.underscore-search').removeClass(pulsationSpan);
			// azzero i tag nel wall
			$(tags).css({'background': 'transparent'});

			//****** per prima cosa creo un box, se non ve ne sono
			if($search.find('span[data-tag-navsearch]').length == 0){
				var random = "#"+((1<<24)*Math.random()|0).toString(16);
				// creo assegnandogli il puntatore
        		$('<span/>').css({'color':random} ).attr('data-tag-navsearch','').appendTo($search);
			}

			//***** dove eseguiro le operazioni di managing, cancellazione e aggiunta
			$focus = null;
			var src = $search.find('span[data-tag-navsearch]');
			var srclen = src.length;
			src.each(function(idx, el){
				$focus = $(this);
				var pt = $(this).find('.pointer-search');
				if( pt.length > 0){
					// rimuovo tutti tranne il presente
					$('.pointer-search').not(pt).remove();
					return false;
				}else if(idx == srclen-1){
					$(this).append(pointer);
				}
			});

			//***** se e' uno spazio allora se l'ultimo e' vuoto lo lascio cosi', altrimenti lo creo
			//      in ogni caso l'ultimo diverra' il focus
        	if(code == 32){
        		// rimuovo i pointer, cosi' nella prossima interazione il focus finira' automaticamente su di lui e gli verra' appeso il pointer
        		$('.pointer-search').remove();
        		var n = $search.find('span[data-tag-navsearch]').length;
        		// se non ce ne sono, allora ritorno: verra' automaticamente creato sopra 
        		if(n == 0) return ;
 				// li elimino perche' porto automaticamente il focus alla fine
        		$('.pointer-search').remove();
        		// se l'ultimo e' vuoto, allora puo' essere utilizzato
        		if($search.find('span[data-tag-navsearch]').last().attr('data-tag-navsearch') == ''){
        			$focus = $('[data-tag-navsearch]').last().html(pointer).appendTo($search);
        		}else{
					var random = "#"+((1<<24)*Math.random()|0).toString(16);
					$('.pointer-search').remove();
        			$focus = $('<span/>').css({'color':random} ).attr('data-tag-navsearch','').html(pointer).appendTo($search);
        		}
        	}


			//***** se e' il backspace allora cancello a sinistra del focus
			if(code == 8){
				// se l'ultimo e' vuoto, allora lo elimino, a meno che non sia l'unico!
				var len = $search.find('span[data-tag-navsearch]').last().length == 0;
				if($focus.attr('data-tag-navsearch') == '' && len > 0){
					$focus.remove();
					// rimpiazzo il focus
					$focus = $search.find('span[data-tag-navsearch]').last();
					$focus.append(pointer);
				}
				// testo puro
				var tagT = $focus.attr('data-tag-navsearch');
				// testo html: mi serve per via del focus
				var tagH = $focus.html();
				var part = tagH.split(pointer);
				// elimino un carattere e riformo il tutto
				tagT = part[0].slice(0, -1) + part[1];
				tagH = part[0].slice(0, -1) + pointer + part[1];
				$focus.attr('data-tag-navsearch', tagT).html(tagH);
				// nel caso si sia svuotato tutto, faccio prima a ripristinare da zero,
				// altrimenti aggiorno la ricerca
				c = '';
				if(tagT.length == 0){
					$focus.remove();
					if($search.find('span[data-tag-navsearch]').length  > 0){
						$focus = $search.find('span[data-tag-navsearch]').last();
						$focus.append(pointer);
					}else{
						// visto che non ho piu niente, riattivo l'underscore pulsante di foowd_
						$('.underscore-search').last().addClass(pulsationSpan);
						return;
					}
				}
			}

			//***** se e' la freccia a sx
			if(code == 37){
				// devo fare lo il flip di un carattere a sx
				var html = $focus.html();
				var n = html.indexOf(pointer);
				// se l'indice e' zero, vuol dire che devo passare al gruppo precedente
				if(n==0){
					// assegno il precedente
					var tmpFocus = $focus;
					$search.find('span[data-tag-navsearch]').each(function(){
						if( $(this).is($focus) ) return false; 
						tmpFocus = $(this);
					});
					$focus = tmpFocus;
					// metto il puntatore prima dell'underscore
					$('.pointer-search').remove();
					$focus/*.find('.underscore-search').before*/.append(pointer);
				}else{
					// escape per regular expression
					function escapeRegExp(str) {
					  return str.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");
					}
					var re = new RegExp('(.*)(.)('+escapeRegExp(pointer)+')(.*)');
					// permutazione: scambio il puntatore col carattere
					html = html.replace(re, function(match,p1,p2,p3,p4,off,str){
						return p1 + p3 + p2 + p4 ;
					});

					$focus.html(html)
				}
				c = '';
			}

			//***** se e' la freccia a dx
			if(code == 39){
				function escapeRegExp(str) {
				  return str.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");
				}
				// devo fare lo il flip di un carattere a sx
				var html = $focus.html();
				var n = html.indexOf(pointer);
				var re = new RegExp(escapeRegExp(pointer));
				var pos = html.replace(re, '').length;

				// console.log($($focus.html()).remove($(pointer)).text().length)
				// se l'indice e' zero, vuol dire che devo passare al gruppo precedente
				if(n==pos){
					// assegno il precedente
					var tmpFocus = $focus;
					var next = false;
					$search.find('span[data-tag-navsearch]').each(function(){
						if(next){
							tmpFocus = $(this);
							return false;	
						} 
						if( $(this).is($focus) ) next = true;
					});
					$focus = tmpFocus;
					// metto il puntatore prima dell'underscore
					$('.pointer-search').remove();
					$focus/*.find('.underscore-search').before*/.prepend(pointer);
				}else{
					// escape per regular expression
					var re = new RegExp('(.*)('+escapeRegExp(pointer)+')(.)(.*)');
					// permutazione: scambio il puntatore col carattere
					html = html.replace(re, function(match,p1,p2,p3,p4,off,str){
						return p1 + p3 + p2 + p4 ;
					});

					$focus.html(html)
				}
				c = '';
			}

    		//***** aggiornamento dei dati: sia attributi che html
        	var $cursor = $focus.find('.pointer-search');//.closest('span[data-tag-navsearch]');
        	$cursor.before(c);
        	// faccio una copia per estrapolare il testo normale da associare al tag
        	var clone = $focus.clone();
        	clone.find('.pointer-search').each(function(){$(this).html('')});
        	var tag = clone.text();
        	$focus.attr('data-tag-navsearch', tag);
        	
        	//***** gestione della ricerca e aggiornamento
        	// di default tolgo i colori
        	$('[data-tag]').css({'background-color': 'transparent'});
        	// loop per aggiungere gli underscore e aggiornare il wall
        	$search.find('span[data-tag-navsearch]').each(function(){
        		// console.log($(this).html())
        		var tag = $(this).attr('data-tag-navsearch');
        		var color = $(this).css('color');
        		// $(this).html( $(this).html() + underscoreSpan );
        		$(this).after(underscoreSpan);
        		$(this).next().css({'color': color});
        		// devono matchare almeno 3 lettere
        		if(tag.length > 2) $('[data-tag*="'+tag+'"]').css({'background-color': color});
        	});



       		// aggiungo all'ultimo underscore disponibile
        	$('.underscore-search').last().addClass(pulsationSpan);

        	//*** gestione dell'overflow
			// NB: uso scroll e non width: la width  di searchText e di searchText-loom coincidono sempre!
        	var sc = $('#searchText').first().prop('scrollWidth');
        	var sw = $('#searchText-loom').first().width();
        	var delta;
        	// se il box lo supera allora devo recuperare il posizionamento
        	if( sc > sw){
        		// posiziono dal SX, quindi mi serve un valore negativo
        		delta = sw - sc;
        		$('#search-dots').addClass('search-dots');
        	}else{
        		delta = 0;
        		$('#search-dots').removeClass('search-dots');
        	}
        	
        	$search.css({'left': delta+'px'});

		});
		
		// devo fare attenzione perche' la larghezza dell'elemento non coincide con quella da calcolare
		// in quanto al momento del click ho anche il pointer search, che occupa spazio orizzontale!
		// NB: altrimenti per fare piu in fretta dovrei prendere ciascuna lettera e circondarla da uno span da utilizzare come fuoco, ma mi sembra esagerato
		$search.on('click', '[data-tag-navsearch]', function(e){
			var $cl = $(this).clone();
			// click width
			var cw = $(this).width(); 
			// evaluated width
			var ew = cw - $('.pointer-search').width(); 
			// se e' presente lo rimuovo per ottenere la stringa corretta
			$cl.find('.pointer-search').remove();
			var text = $cl.text();
			var len = text.length;
			$cl.remove();
			// fattore correttivo
			var ratio = ew/cw;
			console.log(ratio);
			// posizionamnto del click
			var parentOffset = $(this)/*.parent()*/.offset(); 
			var x = e.pageX - parentOffset.left;

			// estrapolo la posizione: per difetto perche' poi taglio da quell'indice
			var pos = Math.floor( len * ratio * (x/cw) );
			var output = [text.slice(0, pos), pointer, text.slice(pos)].join('');

			console.log(x + " " + cw + " " + ew + " " + pos)
			$('.pointer-search').remove();

			$(this).html(output);





		});

	}

	var ret = new NavbarSearch();

	return ret ;
});