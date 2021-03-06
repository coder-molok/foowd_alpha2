define(function(require){
	//var utils = require('Utils');
	var Handlebars = require('handlebars.runtime');

   /*
	* Ho registrato un helper handlebars, per modificare la classe del cuore sulla preferenza
	* in base ai dati che arrivano decido se applicare la classe oppure no
	*/
	Handlebars.registerHelper('prefer', function(object) {
		var result = "";
		if(object.data.root.prefer.length != 0){
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
	        "*": (lvalue * rvalue).toFixed(2),
	        "/": (lvalue / rvalue).toFixed(2),
	        "%": lvalue % rvalue
	    }[operator];
	});
	

	Handlebars.registerHelper('if', function(conditional, options) {
	  if(conditional) {
	    return options.fn(this);
	  } else {
	    return options.inverse(this);
	  }
	});

	Handlebars.registerHelper('unless', function(conditional, options) {
  		if(!conditional) {
    		return options.fn(this);
    	}
	});

	Handlebars.registerHelper('each', function(context, options) {
	  var ret = "";

	  for(var i=0, j=context.length; i<j; i++) {
	    ret = ret + options.fn(context[i]);
	  }

	  return ret;
	});

	Handlebars.registerHelper('canbuy', function(qt, tot, options) {
		if(qt >= tot){
			return options.fn(this);
		}
	});

	Handlebars.registerHelper('desc1', function(text) {
		var result = "";
		if(utils.isValid(text) && text !== ""){
			var words = text.split(' ');
			result = words.splice(250, words.length).join(' ');
		}
		return new Handlebars.SafeString(result);
	});

	Handlebars.registerHelper('desc2', function(text) {
		var result = "";
		if(utils.isValid(text) && text !== ""){
			var words = text.split(' ');
			result = words.splice(0, 250).join(' ');
		}
		return new Handlebars.SafeString(result);
	});

	Handlebars.registerHelper('shortDesc', function(text){
		//clean from html tags
		var realText = $(text).text();
		var words = realText.split(' ');
		return words.splice(0, 30).join(' ').concat("...");
	});

	/* scrivo i tags separandoli da underscore e rinchiudendoli in span utili per la combo con la ricerca */
	Handlebars.registerHelper('listTags', function(tags){
		tags = (tags == '' || typeof tags == 'undefined' ) ? 'foowd' : tags ;
		var words = tags.replace(/[\s,]+/g, ',').split(',');
		var body = ''
		for(var i in words){
			//<wbr> serve per consentire di andare a capo in quel punto, qualora ve ne sia la necessita'
			body = body + '<wbr><span data-tag="' + words[i] + '">_' + words[i] + '</span>';
		}
		return body;
	});

	Handlebars.registerPartial('carouselItem', function(slide){
		var context = {"slide" : slide};
		return Handlebars.templates.carouselItem(context);
	});

});	