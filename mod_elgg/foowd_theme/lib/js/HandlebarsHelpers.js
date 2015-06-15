define(function(require){
	var Handlebars = require('handlebars.runtime');

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

	Handlebars.registerHelper("math", function(lvalue, operator, rvalue) {
    	lvalue = parseFloat(lvalue);
    	rvalue = parseFloat(rvalue);
        
	    return {
	        "+": lvalue + rvalue,
	        "-": lvalue - rvalue,
	        "*": lvalue * rvalue,
	        "/": lvalue / rvalue,
	        "%": lvalue % rvalue
	    }[operator];
	});

});