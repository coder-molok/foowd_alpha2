# Pagine Custom

questo piccolo tutorial cerca di riassumere le tecnologie utilizzate per sviluppare le pagine custom: intese come pagine
che utilizzano Elgg solo come backend per i dati. In pratica le pagine:

- wall
- board utente
- dettaglio prodotto
-

## Tecnologia Handlebars

Per velocizzare le svlippo del layout html è stata utilizzata la libreria Javascript [Handlebar](http://handlebarsjs.com/).
Questa tecnologia a sua volta si appoggia alla tecnologia [Mustache Template](https://mustache.github.io/).

Lo scopo è quello di autogenerare la pagina html dinamicamente lato client applicando un template `Mustache` con i dati provenienti dalle chiamate REST.

Ad esempio la pagina del wall viene creata utilizzando il template [productPost.handlebar](https://github.com/coder-molok/foowd_alpha2/blob/master/mod_elgg/foowd_theme/pages/templates/productDetail.handlebars) 

In fase di build dai file `*.handlebars` viene generato [template-amd.js](https://github.com/coder-molok/foowd_alpha2/blob/master/mod_elgg/foowd_theme/pages/templates/templates-amd.js) un script javascript che contiene i metodi per la generazione dell'html.
Vedi linea 3 di [script/update](https://github.com/coder-molok/foowd_alpha2/blob/master/mod_elgg/foowd_theme/scripts/update)

I metodi dello script vengono chiamati all'interno dei controller. Tornando l'esempio del wall e del template product post, il punto in cui vengono applicati al template i dati ricefuti dalla chiamata rest `API.getProducts` è nel [WallController](https://github.com/coder-molok/foowd_alpha2/blob/master/mod_elgg/foowd_theme/lib/js/foowd/WallController.js#L129).

	//In context ci sono i dati ritornati dalla chiamata
	function _applyProductContext(context) {
			var result = "";
			var userId = utils.getUserId();
			context.map(function(el) {
				//aggiungo altri dati facendo altre chiamate
				el = utils.addPicture(el, utils.randomPictureSize(el.Id));
				//se l'utente è loggato aggiungo un dato al contesto
				el = utils.setLoggedFlag(el, userId);
				//templated è lo script generato dagli handlerbars e registrato tramite sistema amd di ELGG
				result += templates.productPost(el);

			});

			//Il risultato viene aggiunto al container con id #wall
			return result;
		}
