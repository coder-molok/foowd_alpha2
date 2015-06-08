define(function(require){
	var Handlebars = require('handlebars');

   /*
	* Ho registrato un helper handlebars, per modificare la classe del cuore sulla preferenza
	* in base ai dati che arrivano decido se applicare la classe oppure no
	*/
	
	Handlebars.registerHelper('prefer', function(object) {
		var result = "";
		if(object.data.root.prefer != null){
			result = "red-heart";
		}
		return new Handlebars.SafeString(result);
	});
});