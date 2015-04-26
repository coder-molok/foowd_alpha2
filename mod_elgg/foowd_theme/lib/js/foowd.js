/**
 * Created by predo1 on 22/04/15.
 */



var foowd = (function(){

    /*
     *  Questo è un modulo che contiene tutte le funzionalità del client foowd
     */

    /*
     * L'oggeto offers contiene gli URL a cui fare le chiamate alle API.
     * E' organizzato in modo tale da essere di facile lettura e comprensione, e facilmente espandibile.
     */
    var offers;
    offers = {
        all: "http://127.0.0.1/foowd_alpha2/api_foowd/public_html/api/offer?type=search",
        filterby: {
            views: "",
            price: "",
            date: ""
        }
    };
    //qui è contenuta la versione compilata del template del prodotto
    var productTemplate = Handlebars.templates.product;
    //tag html dove andiamo a mettere il template compilato
    var wallId = ".wall";

    /*
     * Funzione che riempe il tag html con i template dei prodotti complilati
     */
    function fillWall(content){
        $(wallId).append(content);
    }
    /*
     * Funzione che applica il template ripetutamente ai dati di contesto
     */
    function applyProductContext(context){
        var result = "";
        context.map(function(el){
            result += productTemplate(el);
        });
        return result;
    }
    return{
        /*
         * Funzione che prende i dati da remoto e li trasforma nei prodotti del wall
         */
        getProducts : function(){

            $.get(offers.all,function(data){
                var rawProducts = $.parseJSON(data);
                var parsedProducts = applyProductContext(rawProducts.body);
                fillWall(parsedProducts);
            });


        }
    }

})();