define(['handlebars.runtime'], function(Handlebars) {
  Handlebars = Handlebars["default"];  var template = Handlebars.template, templates = Handlebars.templates = Handlebars.templates || {};
templates['carouselItem'] = template({"compiler":[7,">= 4.0.0"],"main":function(container,depth0,helpers,partials,data) {
    var helper;

  return "<figure class=\"tint\">\n	<div class=\"item\">\n		<img class = \"owl-lazy\" data-src = \""
    + container.escapeExpression(((helper = (helper = helpers.slide || (depth0 != null ? depth0.slide : depth0)) != null ? helper : helpers.helperMissing),(typeof helper === "function" ? helper.call(depth0 != null ? depth0 : {},{"name":"slide","hash":{},"data":data}) : helper)))
    + "\">\n	</div>\n</figure>";
},"useData":true});
templates['navbar'] = template({"1":function(container,depth0,helpers,partials,data) {
    return "  <div class=\"navbar-section reverse\" id = \"logo\">\n      <div class=\"foowd-brand reverse\">\n       <span onClick=\"utils.goTo('')\">foowd_</span>\n      </div>\n  </div>\n  <div class = \"navbar-section reverse\" id=\"user-menu-section\">\n      <div id = \"user-menu\">\n          <span onClick=\"utils.goTo('board')\" \n                class=\"foowd-icons foowd-icon-heart-edge fw-menu-icon preferences-link reverse\">\n          </span>\n          <span onClick = \"utils.goTo('panel')\"\n                class=\"foowd-icons foowd-icon-user fw-menu-icon profile-link reverse\">\n          </span>\n          <span id=\"close-overlay\" \n                class=\"foowd-icons foowd-icon-menu fw-menu-icon menu-link reverse\">\n          </span>\n      </div>\n  </div>\n";
},"3":function(container,depth0,helpers,partials,data) {
    var stack1;

  return "  <div class=\"navbar-section\" id = \"logo\">\n      <div class=\"foowd-brand\">\n       <span onClick=\"utils.goTo('')\">foowd_</span>\n"
    + ((stack1 = helpers["if"].call(depth0 != null ? depth0 : {},(depth0 != null ? depth0.search : depth0),{"name":"if","hash":{},"fn":container.program(4, data, 0),"inverse":container.noop,"data":data})) != null ? stack1 : "")
    + "      </div>\n  </div>\n  <div class = \"navbar-section\" id=\"user-menu-section\">\n      <div id = \"user-menu\">\n          <span onClick=\"utils.goTo('board')\" \n                class=\"foowd-icons foowd-icon-heart-edge fw-menu-icon preferences-link\">\n          </span>\n          <span onClick = \"utils.goTo('panel')\"\n                class=\"foowd-icons foowd-icon-user fw-menu-icon profile-link\">\n          </span>\n          <span id=\"trigger-overlay\" \n                class=\"foowd-icons foowd-icon-menu fw-menu-icon menu-link\">\n          </span>\n      </div>\n  </div>\n";
},"4":function(container,depth0,helpers,partials,data) {
    return "        <input type=\"text\" id=\"searchText\" onkeypress=\"window.searchProducts(event)\">\n";
},"compiler":[7,">= 4.0.0"],"main":function(container,depth0,helpers,partials,data) {
    var stack1, alias1=depth0 != null ? depth0 : {};

  return ((stack1 = helpers["if"].call(alias1,(depth0 != null ? depth0.reverse : depth0),{"name":"if","hash":{},"fn":container.program(1, data, 0),"inverse":container.noop,"data":data})) != null ? stack1 : "")
    + "\n"
    + ((stack1 = helpers["if"].call(alias1,(depth0 != null ? depth0.regular : depth0),{"name":"if","hash":{},"fn":container.program(3, data, 0),"inverse":container.noop,"data":data})) != null ? stack1 : "");
},"useData":true});
templates['preferenceAccountDetails'] = template({"1":function(container,depth0,helpers,partials,data) {
    var stack1;

  return "    <img src=\""
    + container.escapeExpression(container.lambda(((stack1 = (depth0 != null ? depth0.user : depth0)) != null ? stack1.avatar : stack1), depth0))
    + "\" id = \"user-avatar\">\n";
},"3":function(container,depth0,helpers,partials,data) {
    return "    <img src=\"mod/foowd_theme/img/placeholder-user.jpg\" id = \"user-avatar\">\n";
},"5":function(container,depth0,helpers,partials,data) {
    var stack1;

  return "    <ul class=\"number-block account-info-section\">\n        <li>"
    + container.escapeExpression(container.lambda(((stack1 = (depth0 != null ? depth0.user : depth0)) != null ? stack1.followers : stack1), depth0))
    + "</li>\n        <li><span class =\"number-description\">followers</span></li>\n    </ul>\n";
},"7":function(container,depth0,helpers,partials,data) {
    var stack1;

  return "    <ul class=\"number-block account-info-section\">\n        <li>"
    + container.escapeExpression(container.lambda(((stack1 = (depth0 != null ? depth0.user : depth0)) != null ? stack1.following : stack1), depth0))
    + "</li>\n        <li><span class =\"number-description\">following</span></li>\n    </ul>\n";
},"compiler":[7,">= 4.0.0"],"main":function(container,depth0,helpers,partials,data) {
    var stack1, alias1=depth0 != null ? depth0 : {}, alias2=container.lambda, alias3=container.escapeExpression;

  return "<div id = \"user-details\">\n"
    + ((stack1 = helpers["if"].call(alias1,((stack1 = (depth0 != null ? depth0.user : depth0)) != null ? stack1.avatar : stack1),{"name":"if","hash":{},"fn":container.program(1, data, 0),"inverse":container.noop,"data":data})) != null ? stack1 : "")
    + ((stack1 = helpers.unless.call(alias1,((stack1 = (depth0 != null ? depth0.user : depth0)) != null ? stack1.avatar : stack1),{"name":"unless","hash":{},"fn":container.program(3, data, 0),"inverse":container.noop,"data":data})) != null ? stack1 : "")
    + "    <div id=\"user-info\">\n        <div id=\"username\">\n            "
    + alias3(alias2(((stack1 = (depth0 != null ? depth0.user : depth0)) != null ? stack1.name : stack1), depth0))
    + "\n        </div>\n        <div id = \"board\">\n            my board\n        </div>\n    </div>\n</div>\n<div id=\"account-info\">\n"
    + ((stack1 = helpers["if"].call(alias1,((stack1 = (depth0 != null ? depth0.user : depth0)) != null ? stack1.followers : stack1),{"name":"if","hash":{},"fn":container.program(5, data, 0),"inverse":container.noop,"data":data})) != null ? stack1 : "")
    + ((stack1 = helpers["if"].call(alias1,((stack1 = (depth0 != null ? depth0.user : depth0)) != null ? stack1.following : stack1),{"name":"if","hash":{},"fn":container.program(7, data, 0),"inverse":container.noop,"data":data})) != null ? stack1 : "")
    + "    <ul class=\"number-block account-info-section\">\n        <li>"
    + alias3(alias2(((stack1 = (depth0 != null ? depth0.user : depth0)) != null ? stack1.likes : stack1), depth0))
    + "</li>\n        <li><span class =\"number-description\">products</span></li>\n    </ul>\n</div>";
},"useData":true});
templates['producerProfile'] = template({"1":function(container,depth0,helpers,partials,data) {
    var stack1;

  return ((stack1 = container.invokePartial(partials.carouselItem,(depth0 != null ? depth0.slide : depth0),{"name":"carouselItem","data":data,"indent":"\t\t","helpers":helpers,"partials":partials,"decorators":container.decorators})) != null ? stack1 : "");
},"compiler":[7,">= 4.0.0"],"main":function(container,depth0,helpers,partials,data) {
    var stack1, helper, alias1=depth0 != null ? depth0 : {}, alias2=helpers.helperMissing, alias3="function", alias4=container.escapeExpression;

  return "<div id = \"producer-carousel\" class=\"owl-carousel\">\n"
    + ((stack1 = helpers.each.call(alias1,(depth0 != null ? depth0.slides : depth0),{"name":"each","hash":{},"fn":container.program(1, data, 0),"inverse":container.noop,"data":data})) != null ? stack1 : "")
    + "</div>\n<div id = \"producer-header\">\n	<div id = \"producer-name\">\n		"
    + alias4(((helper = (helper = helpers.Name || (depth0 != null ? depth0.Name : depth0)) != null ? helper : alias2),(typeof helper === alias3 ? helper.call(alias1,{"name":"Name","hash":{},"data":data}) : helper)))
    + "\n		<span>"
    + alias4(((helper = (helper = helpers.Site || (depth0 != null ? depth0.Site : depth0)) != null ? helper : alias2),(typeof helper === alias3 ? helper.call(alias1,{"name":"Site","hash":{},"data":data}) : helper)))
    + "</span>\n	</div>\n</div>\n<div id = \"producer-description\">\n	<div class = \"about\">\n		About us\n	</div>\n	<div class = \"about-description\">\n		"
    + alias4((helpers.desc1 || (depth0 && depth0.desc1) || alias2).call(alias1,(depth0 != null ? depth0.Description : depth0),{"name":"desc1","hash":{},"data":data}))
    + "\n	</div>\n	<div class = \"about-description\">\n		"
    + alias4((helpers.desc2 || (depth0 && depth0.desc2) || alias2).call(alias1,(depth0 != null ? depth0.Description : depth0),{"name":"desc2","hash":{},"data":data}))
    + "\n	</div>\n	<div>&nbsp;</div>\n</div>";
},"usePartial":true,"useData":true});
templates['productDetail'] = template({"1":function(container,depth0,helpers,partials,data) {
    var helper, alias1=depth0 != null ? depth0 : {}, alias2=helpers.helperMissing, alias3="function", alias4=container.escapeExpression;

  return "        <span class = \"detail-progress-bar\">\n            <span class = \"progress\" \n              data-unit = \"1\"\n              data-progress = \""
    + alias4(((helper = (helper = helpers.totalQt || (depth0 != null ? depth0.totalQt : depth0)) != null ? helper : alias2),(typeof helper === alias3 ? helper.call(alias1,{"name":"totalQt","hash":{},"data":data}) : helper)))
    + "\" \n              data-total = \""
    + alias4(((helper = (helper = helpers.Minqt || (depth0 != null ? depth0.Minqt : depth0)) != null ? helper : alias2),(typeof helper === alias3 ? helper.call(alias1,{"name":"Minqt","hash":{},"data":data}) : helper)))
    + "\">\n            </span>    \n        </span>\n        <span class = \"detail-progress-bar preview-bar\">\n            <span class = \"progress\" \n              data-unit = \"1\"\n              data-progress = \""
    + alias4(((helper = (helper = helpers.totalQt || (depth0 != null ? depth0.totalQt : depth0)) != null ? helper : alias2),(typeof helper === alias3 ? helper.call(alias1,{"name":"totalQt","hash":{},"data":data}) : helper)))
    + "\" \n              data-total = \""
    + alias4(((helper = (helper = helpers.Minqt || (depth0 != null ? depth0.Minqt : depth0)) != null ? helper : alias2),(typeof helper === alias3 ? helper.call(alias1,{"name":"Minqt","hash":{},"data":data}) : helper)))
    + "\">\n            </span>    \n        </span>\n";
},"3":function(container,depth0,helpers,partials,data) {
    var helper, alias1=depth0 != null ? depth0 : {}, alias2=helpers.helperMissing, alias3="function", alias4=container.escapeExpression;

  return "            <ul class=\"action-icons menu-section\" id = \"preference-action\">\n                <li class=\"action-heart\">\n                    <i  class=\"foowd-icons foowd-icon-heart-edge\" \n                        onClick = \"ProductDetailController.addPreference("
    + alias4(((helper = (helper = helpers.Id || (depth0 != null ? depth0.Id : depth0)) != null ? helper : alias2),(typeof helper === alias3 ? helper.call(alias1,{"name":"Id","hash":{},"data":data}) : helper)))
    + ", 1)\"></i>\n                </li>\n                <li class=\"action-minus\">\n                    <i class=\"foowd-icons foowd-icon-minus fw-menu-icon\" \n                       onClick = \"ProductDetailController.addPreference("
    + alias4(((helper = (helper = helpers.Id || (depth0 != null ? depth0.Id : depth0)) != null ? helper : alias2),(typeof helper === alias3 ? helper.call(alias1,{"name":"Id","hash":{},"data":data}) : helper)))
    + ", -1)\"></i>\n                </li>\n            </ul>\n";
},"5":function(container,depth0,helpers,partials,data) {
    var stack1, helper, alias1=depth0 != null ? depth0 : {}, alias2=helpers.helperMissing, alias3=container.escapeExpression;

  return "                <div class = \"commercial-menu-item\">\n                    <div class=\"item-container\">\n                        <span class=\"foowd-icons foowd-icon-heart-edge item-icon\">\n                        </span>\n                        <span class = \"item-title\">carrello</span>\n                        <span class = \"item-data\">x"
    + alias3(((helper = (helper = helpers.totalQt || (depth0 != null ? depth0.totalQt : depth0)) != null ? helper : alias2),(typeof helper === "function" ? helper.call(alias1,{"name":"totalQt","hash":{},"data":data}) : helper)))
    + "</span>\n                    </div>\n                </div>\n                <div class = \"commercial-menu-item\">\n                    <div class=\"item-container\">\n                        <span class=\"foowd-icons foowd-icon-cart item-icon\"></span>\n                        <span class = \"item-title\">tot</span>\n                        <span class = \"item-data\">\n                            "
    + alias3((helpers.math || (depth0 && depth0.math) || alias2).call(alias1,(depth0 != null ? depth0.totalQt : depth0),"*",(depth0 != null ? depth0.Price : depth0),{"name":"math","hash":{},"data":data}))
    + "\n                            <span class = \"apex\">€</span>\n                        </span>\n                    </div>\n                </div>\n"
    + ((stack1 = (helpers.canbuy || (depth0 && depth0.canbuy) || alias2).call(alias1,(depth0 != null ? depth0.totalQt : depth0),(depth0 != null ? depth0.Minqt : depth0),{"name":"canbuy","hash":{},"fn":container.program(6, data, 0),"inverse":container.noop,"data":data})) != null ? stack1 : "")
    + "\n";
},"6":function(container,depth0,helpers,partials,data) {
    return "                    <div class = \"commercial-menu-item\">\n                        <ul class=\"action-icons menu-section\" id = \"preference-action\">\n                            <li class=\"action-buy\">\n                                <i class=\"foowd-icons foowd-icon-plus\"></i>\n                            </li>\n                        </ul>\n                    </div>\n";
},"compiler":[7,">= 4.0.0"],"main":function(container,depth0,helpers,partials,data) {
    var stack1, helper, alias1=depth0 != null ? depth0 : {}, alias2=helpers.helperMissing, alias3="function", alias4=container.escapeExpression;

  return "<div id=\"detail-menu\">\n    <div class=\"detail-menu-section\" id=\"price-section\">\n"
    + ((stack1 = helpers["if"].call(alias1,(depth0 != null ? depth0.logged : depth0),{"name":"if","hash":{},"fn":container.program(1, data, 0),"inverse":container.noop,"data":data})) != null ? stack1 : "")
    + "        <div class=\"price-detail\">\n            <ul class=\"number-block\" id=\"unit-price\">\n                <li>"
    + alias4(((helper = (helper = helpers.Price || (depth0 != null ? depth0.Price : depth0)) != null ? helper : alias2),(typeof helper === alias3 ? helper.call(alias1,{"name":"Price","hash":{},"data":data}) : helper)))
    + "<span class=\"apex\">€</span></li>\n                <li><span class =\"number-description\">prezzo unità</span></li>\n            </ul>\n        </div>\n        <div class=\"price-detail\">\n            <ul class=\"number-block\" id=\"min-order-price\">\n                <li>"
    + alias4((helpers.math || (depth0 && depth0.math) || alias2).call(alias1,(depth0 != null ? depth0.Price : depth0),"*",(depth0 != null ? depth0.Minqt : depth0),{"name":"math","hash":{},"data":data}))
    + "<span class=\"apex\">€</span></li>\n                <li><span class =\"number-description\">ordine minimo</span></li>\n            </ul>\n        </div>\n    </div>\n    <div class=\"detail-menu-section\" id =\"menu-close\">\n        <span class=\"foowd-icons foowd-icon-close\" id =\"close-detail\" onClick=\"utils.goTo('')\"></span>\n    </div>\n</div>\n<div id=\"product-menu\">\n    <div class=\"product-menu-section\" id=\"product-info\">\n        <div class=\"product-info-menu\" id = \"section1\">\n\n            <div class=\"menu-section\" id=\"product-name\">\n                "
    + alias4(((helper = (helper = helpers.Name || (depth0 != null ? depth0.Name : depth0)) != null ? helper : alias2),(typeof helper === alias3 ? helper.call(alias1,{"name":"Name","hash":{},"data":data}) : helper)))
    + "\n            </div>\n            \n"
    + ((stack1 = helpers["if"].call(alias1,(depth0 != null ? depth0.logged : depth0),{"name":"if","hash":{},"fn":container.program(3, data, 0),"inverse":container.noop,"data":data})) != null ? stack1 : "")
    + "\n        </div>\n        <div class=\"product-info-menu\" id = \"section2\">\n            <div id=\"product-description\">\n                "
    + ((stack1 = ((helper = (helper = helpers.Description || (depth0 != null ? depth0.Description : depth0)) != null ? helper : alias2),(typeof helper === alias3 ? helper.call(alias1,{"name":"Description","hash":{},"data":data}) : helper))) != null ? stack1 : "")
    + "\n            </div>\n            <div id=\"commercial-menu\">\n"
    + ((stack1 = helpers["if"].call(alias1,(depth0 != null ? depth0.logged : depth0),{"name":"if","hash":{},"fn":container.program(5, data, 0),"inverse":container.noop,"data":data})) != null ? stack1 : "")
    + "            </div>\n        </div>\n    </div>\n    <div class = \"product-menu-section\" id=\"product-picture\">\n       <img src=\""
    + alias4(((helper = (helper = helpers.picture || (depth0 != null ? depth0.picture : depth0)) != null ? helper : alias2),(typeof helper === alias3 ? helper.call(alias1,{"name":"picture","hash":{},"data":data}) : helper)))
    + "\"/>\n    </div>\n</div>\n";
},"useData":true});
templates['productPost'] = template({"1":function(container,depth0,helpers,partials,data) {
    var stack1, helper, alias1=container.escapeExpression, alias2=depth0 != null ? depth0 : {}, alias3=helpers.helperMissing;

  return "                <span class=\"mini-progress-bar\">\n                    <span class=\"mini-progress\" data-unit=\"1\" data-progress=\""
    + alias1(container.lambda(((stack1 = (depth0 != null ? depth0.prefer : depth0)) != null ? stack1.Qt : stack1), depth0))
    + "\" data-total=\""
    + alias1(((helper = (helper = helpers.Minqt || (depth0 != null ? depth0.Minqt : depth0)) != null ? helper : alias3),(typeof helper === "function" ? helper.call(alias2,{"name":"Minqt","hash":{},"data":data}) : helper)))
    + "\"></span>\n                </span>  \n                <div class=\"product-post-progress-price\">\n                "
    + alias1((helpers.math || (depth0 && depth0.math) || alias3).call(alias2,(depth0 != null ? depth0.totalQt : depth0),"*",(depth0 != null ? depth0.Price : depth0),{"name":"math","hash":{},"data":data}))
    + "\n                    <span class=\"apex\">€</span>\n                </div>\n";
},"compiler":[7,">= 4.0.0"],"main":function(container,depth0,helpers,partials,data) {
    var stack1, helper, alias1=depth0 != null ? depth0 : {}, alias2=helpers.helperMissing, alias3="function", alias4=container.escapeExpression;

  return "<li class=\"product-post\">\n    <div class=\"post-container\">\n        <div class=\"product-post-image-thumbnail\">\n            <figure class=\"tint\">\n                <img src=\""
    + alias4(((helper = (helper = helpers.picture || (depth0 != null ? depth0.picture : depth0)) != null ? helper : alias2),(typeof helper === alias3 ? helper.call(alias1,{"name":"picture","hash":{},"data":data}) : helper)))
    + "\">\n            </figure>\n            <span class=\"heart-overlay foowd-icons foowd-icon-heart-full\"\n                  onclick=\"window.addPreference("
    + alias4(((helper = (helper = helpers.Id || (depth0 != null ? depth0.Id : depth0)) != null ? helper : alias2),(typeof helper === alias3 ? helper.call(alias1,{"name":"Id","hash":{},"data":data}) : helper)))
    + ",1)\">\n            </span>\n            <div class=\"product-post-menu\">\n                <div onclick=\"utils.go2('detail', 'productId', "
    + alias4(((helper = (helper = helpers.Id || (depth0 != null ? depth0.Id : depth0)) != null ? helper : alias2),(typeof helper === alias3 ? helper.call(alias1,{"name":"Id","hash":{},"data":data}) : helper)))
    + ")\">\n                   <span class = \"foowd-icons foowd-icon-len\">\n                   </span>\n                </div>\n                <div onclick=\"utils.go2('producer', 'producerId', "
    + alias4(((helper = (helper = helpers.Publisher || (depth0 != null ? depth0.Publisher : depth0)) != null ? helper : alias2),(typeof helper === alias3 ? helper.call(alias1,{"name":"Publisher","hash":{},"data":data}) : helper)))
    + ")\">\n                   <span class = \"foowd-icons foowd-icon-blade\">\n                   </span>\n                </div>\n            </div>\n        </div>\n        <div class=\"product-post-header\">\n            <div class=\"product-post-name\">\n                "
    + alias4(((helper = (helper = helpers.Name || (depth0 != null ? depth0.Name : depth0)) != null ? helper : alias2),(typeof helper === alias3 ? helper.call(alias1,{"name":"Name","hash":{},"data":data}) : helper)))
    + "\n                <br/>\n                <br/>\n                <span class=\"product-post-unit-price\">\n                    "
    + alias4(((helper = (helper = helpers.Quota || (depth0 != null ? depth0.Quota : depth0)) != null ? helper : alias2),(typeof helper === alias3 ? helper.call(alias1,{"name":"Quota","hash":{},"data":data}) : helper)))
    + " "
    + alias4(((helper = (helper = helpers.Unit || (depth0 != null ? depth0.Unit : depth0)) != null ? helper : alias2),(typeof helper === alias3 ? helper.call(alias1,{"name":"Unit","hash":{},"data":data}) : helper)))
    + " "
    + alias4(((helper = (helper = helpers.UnitExtra || (depth0 != null ? depth0.UnitExtra : depth0)) != null ? helper : alias2),(typeof helper === alias3 ? helper.call(alias1,{"name":"UnitExtra","hash":{},"data":data}) : helper)))
    + "\n                    <br/>\n                    "
    + alias4(((helper = (helper = helpers.Price || (depth0 != null ? depth0.Price : depth0)) != null ? helper : alias2),(typeof helper === alias3 ? helper.call(alias1,{"name":"Price","hash":{},"data":data}) : helper)))
    + "€\n                </span>\n            </div>\n"
    + ((stack1 = helpers["if"].call(alias1,(depth0 != null ? depth0.logged : depth0),{"name":"if","hash":{},"fn":container.program(1, data, 0),"inverse":container.noop,"data":data})) != null ? stack1 : "")
    + "        </div>\n        <div class=\"product-post-body\">\n                "
    + ((stack1 = (helpers.shortDesc || (depth0 && depth0.shortDesc) || alias2).call(alias1,(depth0 != null ? depth0.Description : depth0),{"name":"shortDesc","hash":{},"data":data})) != null ? stack1 : "")
    + "\n        </div>\n    </div>\n</li>";
},"useData":true});
templates['userPreference'] = template({"1":function(container,depth0,helpers,partials,data) {
    var stack1, helper, alias1=depth0 != null ? depth0 : {}, alias2=helpers.helperMissing, alias3="function", alias4=container.escapeExpression, alias5=container.lambda;

  return "    <div class=\"preference\">\n       <span class = \"preference-progress-bar\">\n            <span class = \"progress\" \n              data-unit = \"1\"\n              data-progress = \""
    + alias4(((helper = (helper = helpers.Qt || (depth0 != null ? depth0.Qt : depth0)) != null ? helper : alias2),(typeof helper === alias3 ? helper.call(alias1,{"name":"Qt","hash":{},"data":data}) : helper)))
    + "\" \n              data-total = \""
    + alias4(alias5(((stack1 = (depth0 != null ? depth0.Offer : depth0)) != null ? stack1.Minqt : stack1), depth0))
    + "\">\n            </span>    \n        </span>\n        <div class=\"user-preference\">\n            <div class=\"user-preference-section\">\n                <img src=\""
    + alias4(alias5(((stack1 = (depth0 != null ? depth0.Offer : depth0)) != null ? stack1.picture : stack1), depth0))
    + "\" class = \"user-preference-image\" \n                     onclick=\"utils.go2('detail', 'productId',"
    + alias4(alias5(((stack1 = (depth0 != null ? depth0.Offer : depth0)) != null ? stack1.Id : stack1), depth0))
    + ")\">    \n            </div>\n            <div class=\"user-preference-name user-preference-section\">\n                <ul class=\"number-block\">\n                    <li>"
    + alias4(alias5(((stack1 = (depth0 != null ? depth0.Offer : depth0)) != null ? stack1.Name : stack1), depth0))
    + "</li>\n                </ul>\n            </div>\n            <div class=\"user-preference-details user-preference-section\">\n                <ul class=\"number-block preference-detail\">\n                    <li>"
    + alias4(alias5(((stack1 = (depth0 != null ? depth0.Offer : depth0)) != null ? stack1.Price : stack1), depth0))
    + "€</li>\n                    <li><span class =\"number-description\">Prezzo unità</span></li>\n                </ul>\n                <ul class=\"number-block preference-detail\">\n                    <li>x"
    + alias4(((helper = (helper = helpers.Qt || (depth0 != null ? depth0.Qt : depth0)) != null ? helper : alias2),(typeof helper === alias3 ? helper.call(alias1,{"name":"Qt","hash":{},"data":data}) : helper)))
    + "</li>\n                    <li><span class =\"number-description\">carrello</span></li>\n                </ul>\n                <ul class=\"number-block preference-detail\">\n                    <li>"
    + alias4((helpers.math || (depth0 && depth0.math) || alias2).call(alias1,(depth0 != null ? depth0.Qt : depth0),"*",((stack1 = (depth0 != null ? depth0.Offer : depth0)) != null ? stack1.Price : stack1),{"name":"math","hash":{},"data":data}))
    + "€</li>\n                    <li><span class =\"number-description\">tot.spesa</span></li>\n                </ul>\n            </div>\n            <div class=\"user-preference-actions user-preference-section\">\n                <ul class=\"action-icons menu-section\" id = \"preference-action\">\n"
    + ((stack1 = (helpers.canbuy || (depth0 && depth0.canbuy) || alias2).call(alias1,(depth0 != null ? depth0.Qt : depth0),((stack1 = (depth0 != null ? depth0.Offer : depth0)) != null ? stack1.Minqt : stack1),{"name":"canbuy","hash":{},"fn":container.program(2, data, 0),"inverse":container.noop,"data":data})) != null ? stack1 : "")
    + "                    <li class=\"action-heart\">\n                        <i  class=\"foowd-icons foowd-icon-heart-edge\" \n                            onClick = \"UserBoardController.addPreference("
    + alias4(alias5(((stack1 = (depth0 != null ? depth0.Offer : depth0)) != null ? stack1.Id : stack1), depth0))
    + ", 1)\"></i>\n                    </li>\n                    <li class=\"action-minus\">\n                        <i class=\"foowd-icons foowd-icon-minus fw-menu-icon\" \n                           onClick = \"UserBoardController.addPreference("
    + alias4(alias5(((stack1 = (depth0 != null ? depth0.Offer : depth0)) != null ? stack1.Id : stack1), depth0))
    + ", -1)\"></i>\n                    </li>\n                </ul>\n            </div>\n        </div>\n    </div>\n";
},"2":function(container,depth0,helpers,partials,data) {
    return "                    <li class=\"action-buy\">\n                        <i class=\"foowd-icons foowd-icon-plus\"></i>\n                    </li>\n";
},"compiler":[7,">= 4.0.0"],"main":function(container,depth0,helpers,partials,data) {
    var stack1;

  return ((stack1 = helpers["if"].call(depth0 != null ? depth0 : {},(depth0 != null ? depth0.Qt : depth0),{"name":"if","hash":{},"fn":container.program(1, data, 0),"inverse":container.noop,"data":data})) != null ? stack1 : "");
},"useData":true});
return templates;
});