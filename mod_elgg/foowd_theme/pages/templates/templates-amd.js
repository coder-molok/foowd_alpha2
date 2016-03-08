define(['handlebars.runtime'], function(Handlebars) {
  Handlebars = Handlebars["default"];  var template = Handlebars.template, templates = Handlebars.templates = Handlebars.templates || {};
templates['carouselItem'] = template({"compiler":[7,">= 4.0.0"],"main":function(container,depth0,helpers,partials,data) {
    var helper;

  return "<figure class=\"tint\">\r\n	<div class=\"item\">\r\n		<img class = \"owl-lazy\" data-src = \""
    + container.escapeExpression(((helper = (helper = helpers.slide || (depth0 != null ? depth0.slide : depth0)) != null ? helper : helpers.helperMissing),(typeof helper === "function" ? helper.call(depth0 != null ? depth0 : {},{"name":"slide","hash":{},"data":data}) : helper)))
    + "\">\r\n	</div>\r\n</figure>";
},"useData":true});
templates['commonHtml'] = template({"compiler":[7,">= 4.0.0"],"main":function(container,depth0,helpers,partials,data) {
    return "\r\n<!-- Pezzo che deve essere comune a tutte le pagine -->\r\n\r\n<div id=\"close-overlay\" class=\"overlay overlay-hugeinc\">\r\n  <div class=\"reverse foowd-navbar\">\r\n  </div>\r\n  <nav>\r\n    <ul>\r\n      <li><a target=\"_blank\" href=\"http://www.tiny.cc/guida_foowd_1\">Guida all'uso</a></li>\r\n      <li><a target=\"foowd_site\" href=\"http://www.foowd.it\">Sito web</a></li>\r\n      <li><a target=\"foowd_site\" href=\"http://www.foowd.it/about.html\">Su di noi</a></li>\r\n      <li><a target=\"foowd_site\" href=\"http://www.foowd.it/investors.html\">Investitori</a></li>\r\n      <li><a target=\"foowd_site\" href=\"https://www.smore.com/pcm5x\">Produttori</a></li>\r\n      <li><a target=\"foowd_site\" href=\"http://www.foowd.it/#contatti\">Contatti</a></li>\r\n    </ul>\r\n  </nav>\r\n</div>\r\n\r\n<!-- ############################################### -->\r\n\r\n<!-- Pezzo che deve essere presente se viene aggiuntao tolta un preferenza -->\r\n\r\n<div class=\"foowd-alert\" role=\"alert\" id=\"foowd-success\"></div>\r\n<div class=\"foowd-alert\" role=\"alert\" id=\"foowd-error\"></div>\r\n";
},"useData":true});
templates['navbar'] = template({"1":function(container,depth0,helpers,partials,data) {
    var helper;

  return "  <div class=\"navbar-section reverse logo\" >\r\n      <div class=\"foowd-brand reverse\">\r\n       <span onClick=\"utils.goTo('')\">foowd_</span>\r\n      </div>\r\n  </div>\r\n  <div class = \"navbar-section reverse logo\" id=\"user-menu-section\">\r\n      <div id = \"user-menu\">\r\n          <span onClick=\"utils.goTo('board')\" \r\n                class=\"foowd-icons foowd-icon-heart-edge fw-menu-icon preferences-link reverse\">\r\n          </span>\r\n          <a href=\""
    + container.escapeExpression(((helper = (helper = helpers.panelUri || (depth0 != null ? depth0.panelUri : depth0)) != null ? helper : helpers.helperMissing),(typeof helper === "function" ? helper.call(depth0 != null ? depth0 : {},{"name":"panelUri","hash":{},"data":data}) : helper)))
    + "\">\r\n            <span class=\"foowd-icons foowd-icon-user fw-menu-icon profile-link\">\r\n            </span>\r\n          </a>\r\n          <span id=\"trigger-overlay\" \r\n                class=\"foowd-icons foowd-icon-menu fw-menu-icon menu-link reverse\">\r\n          </span>\r\n      </div>\r\n  </div>\r\n";
},"3":function(container,depth0,helpers,partials,data) {
    var stack1, helper, alias1=depth0 != null ? depth0 : {}, alias2=helpers.helperMissing, alias3="function", alias4=container.escapeExpression;

  return "  <a id=\"foowd-brand-home-uri\" href=\""
    + alias4(((helper = (helper = helpers.homeUri || (depth0 != null ? depth0.homeUri : depth0)) != null ? helper : alias2),(typeof helper === alias3 ? helper.call(alias1,{"name":"homeUri","hash":{},"data":data}) : helper)))
    + "\">\r\n  <div class=\"navbar-section logo\" >\r\n      <div class=\"foowd-brand\">\r\n        <input id=\"search-input-phantom\" style=\"position:fixed; left: 70%; visibility: hidden;\"/>\r\n       <div class=\"foowd-brand-pre-search\">foowd<span class=\"foowd-pulsate underscore-search\">_</span></div>\r\n"
    + ((stack1 = helpers["if"].call(alias1,(depth0 != null ? depth0.search : depth0),{"name":"if","hash":{},"fn":container.program(4, data, 0),"inverse":container.noop,"data":data})) != null ? stack1 : "")
    + "      </div>\r\n  </div>\r\n  </a>\r\n  <div class=\"navbar-section\" id=\"user-menu-section\">\r\n      <div id=\"user-menu\">\r\n"
    + ((stack1 = helpers["if"].call(alias1,(depth0 != null ? depth0.logged : depth0),{"name":"if","hash":{},"fn":container.program(6, data, 0),"inverse":container.noop,"data":data})) != null ? stack1 : "")
    + "            <a href=\""
    + alias4(((helper = (helper = helpers.boardUri || (depth0 != null ? depth0.boardUri : depth0)) != null ? helper : alias2),(typeof helper === alias3 ? helper.call(alias1,{"name":"boardUri","hash":{},"data":data}) : helper)))
    + "\">\r\n              <span class=\"foowd-icons foowd-icon-heart-edge fw-menu-icon preferences-link\">\r\n              </span>\r\n            </a>\r\n            <a href=\""
    + alias4(((helper = (helper = helpers.panelUri || (depth0 != null ? depth0.panelUri : depth0)) != null ? helper : alias2),(typeof helper === alias3 ? helper.call(alias1,{"name":"panelUri","hash":{},"data":data}) : helper)))
    + "\">\r\n              <span class=\"foowd-icons foowd-icon-user fw-menu-icon profile-link\">\r\n              </span>\r\n            </a>\r\n            <span id=\"trigger-overlay\" \r\n                class=\"foowd-icons foowd-icon-menu fw-menu-icon menu-link\">\r\n            </span>\r\n      </div>\r\n  </div>\r\n";
},"4":function(container,depth0,helpers,partials,data) {
    return "        <div id=\"searchText-loom\">\r\n          <span id=\"search-dots\"></span>\r\n          <div id=\"searchText\" onkeypress=\"window.searchProductsKey(event)\"></div>\r\n        </div>\r\n";
},"6":function(container,depth0,helpers,partials,data) {
    return "	      	 <span id=\"groupBtn\" onClick=\"window.toggleGroup()\" \r\n	                class=\"foowd-icons icon-black foowd-icon-group fw-menu-icon preferences-link\">\r\n	          </span>\r\n";
},"compiler":[7,">= 4.0.0"],"main":function(container,depth0,helpers,partials,data) {
    var stack1, alias1=depth0 != null ? depth0 : {};

  return ((stack1 = helpers["if"].call(alias1,(depth0 != null ? depth0.reverse : depth0),{"name":"if","hash":{},"fn":container.program(1, data, 0),"inverse":container.noop,"data":data})) != null ? stack1 : "")
    + "\r\n"
    + ((stack1 = helpers["if"].call(alias1,(depth0 != null ? depth0.regular : depth0),{"name":"if","hash":{},"fn":container.program(3, data, 0),"inverse":container.noop,"data":data})) != null ? stack1 : "");
},"useData":true});
templates['preferenceAccountDetails'] = template({"1":function(container,depth0,helpers,partials,data) {
    var stack1;

  return "    <img src=\""
    + container.escapeExpression(container.lambda(((stack1 = (depth0 != null ? depth0.user : depth0)) != null ? stack1.avatar : stack1), depth0))
    + "\" id = \"user-avatar\">\r\n";
},"3":function(container,depth0,helpers,partials,data) {
    return "    <img src=\"mod/foowd_theme/img/placeholder-user.jpg\" id = \"user-avatar\">\r\n";
},"5":function(container,depth0,helpers,partials,data) {
    var stack1;

  return "    <ul class=\"number-block account-info-section\">\r\n        <li>"
    + container.escapeExpression(container.lambda(((stack1 = (depth0 != null ? depth0.user : depth0)) != null ? stack1.followers : stack1), depth0))
    + "</li>\r\n        <li><span class =\"number-description\">followers</span></li>\r\n    </ul>\r\n";
},"7":function(container,depth0,helpers,partials,data) {
    var stack1;

  return "    <ul class=\"number-block account-info-section\">\r\n        <li>"
    + container.escapeExpression(container.lambda(((stack1 = (depth0 != null ? depth0.user : depth0)) != null ? stack1.following : stack1), depth0))
    + "</li>\r\n        <li><span class =\"number-description\">following</span></li>\r\n    </ul>\r\n";
},"compiler":[7,">= 4.0.0"],"main":function(container,depth0,helpers,partials,data) {
    var stack1, alias1=depth0 != null ? depth0 : {}, alias2=container.lambda, alias3=container.escapeExpression;

  return "<div id = \"user-details\">\r\n"
    + ((stack1 = helpers["if"].call(alias1,((stack1 = (depth0 != null ? depth0.user : depth0)) != null ? stack1.avatar : stack1),{"name":"if","hash":{},"fn":container.program(1, data, 0),"inverse":container.noop,"data":data})) != null ? stack1 : "")
    + ((stack1 = helpers.unless.call(alias1,((stack1 = (depth0 != null ? depth0.user : depth0)) != null ? stack1.avatar : stack1),{"name":"unless","hash":{},"fn":container.program(3, data, 0),"inverse":container.noop,"data":data})) != null ? stack1 : "")
    + "    <div id=\"user-info\">\r\n        <div id=\"username\">\r\n            "
    + alias3(alias2(((stack1 = (depth0 != null ? depth0.user : depth0)) != null ? stack1.name : stack1), depth0))
    + "\r\n        </div>\r\n        <div id = \"board\">\r\n            la mia tavola\r\n        </div>\r\n    </div>\r\n</div>\r\n<div id=\"account-info\">\r\n"
    + ((stack1 = helpers["if"].call(alias1,((stack1 = (depth0 != null ? depth0.user : depth0)) != null ? stack1.followers : stack1),{"name":"if","hash":{},"fn":container.program(5, data, 0),"inverse":container.noop,"data":data})) != null ? stack1 : "")
    + ((stack1 = helpers["if"].call(alias1,((stack1 = (depth0 != null ? depth0.user : depth0)) != null ? stack1.following : stack1),{"name":"if","hash":{},"fn":container.program(7, data, 0),"inverse":container.noop,"data":data})) != null ? stack1 : "")
    + "    <ul class=\"number-block account-info-section\">\r\n        <li>"
    + alias3(alias2(((stack1 = (depth0 != null ? depth0.user : depth0)) != null ? stack1.likes : stack1), depth0))
    + "</li>\r\n        <li><span class =\"number-description\">prodotti</span></li>\r\n    </ul>\r\n</div>";
},"useData":true});
templates['producerProfile'] = template({"1":function(container,depth0,helpers,partials,data) {
    var stack1;

  return ((stack1 = container.invokePartial(partials.carouselItem,(depth0 != null ? depth0.slide : depth0),{"name":"carouselItem","data":data,"indent":"\t\t","helpers":helpers,"partials":partials,"decorators":container.decorators})) != null ? stack1 : "");
},"compiler":[7,">= 4.0.0"],"main":function(container,depth0,helpers,partials,data) {
    var stack1, helper, alias1=depth0 != null ? depth0 : {}, alias2=helpers.helperMissing, alias3="function", alias4=container.escapeExpression;

  return "<div id = \"producer-carousel\" class=\"owl-carousel\">\r\n"
    + ((stack1 = helpers.each.call(alias1,(depth0 != null ? depth0.slides : depth0),{"name":"each","hash":{},"fn":container.program(1, data, 0),"inverse":container.noop,"data":data})) != null ? stack1 : "")
    + "</div>\r\n<div id = \"producer-header\">\r\n	<div id = \"producer-name\">\r\n		"
    + alias4(((helper = (helper = helpers.Name || (depth0 != null ? depth0.Name : depth0)) != null ? helper : alias2),(typeof helper === alias3 ? helper.call(alias1,{"name":"Name","hash":{},"data":data}) : helper)))
    + "\r\n		<span>"
    + alias4(((helper = (helper = helpers.Site || (depth0 != null ? depth0.Site : depth0)) != null ? helper : alias2),(typeof helper === alias3 ? helper.call(alias1,{"name":"Site","hash":{},"data":data}) : helper)))
    + "</span>\r\n	</div>\r\n</div>\r\n<div id = \"producer-description\">\r\n	<div class = \"about\">\r\n		About us\r\n	</div>\r\n	<div class = \"about-description\">\r\n		"
    + alias4((helpers.desc1 || (depth0 && depth0.desc1) || alias2).call(alias1,(depth0 != null ? depth0.Description : depth0),{"name":"desc1","hash":{},"data":data}))
    + "\r\n	</div>\r\n	<div class = \"about-description\">\r\n		"
    + alias4((helpers.desc2 || (depth0 && depth0.desc2) || alias2).call(alias1,(depth0 != null ? depth0.Description : depth0),{"name":"desc2","hash":{},"data":data}))
    + "\r\n	</div>\r\n	<div>&nbsp;</div>\r\n</div>\r\n";
},"usePartial":true,"useData":true});
templates['productDetail'] = template({"1":function(container,depth0,helpers,partials,data) {
    var helper, alias1=depth0 != null ? depth0 : {}, alias2=helpers.helperMissing, alias3="function", alias4=container.escapeExpression;

  return "        <span class = \"detail-progress-bar\">\r\n            <span class = \"progress\" \r\n              data-unit = \"1\"\r\n              data-progress = \""
    + alias4(((helper = (helper = helpers.totalQt || (depth0 != null ? depth0.totalQt : depth0)) != null ? helper : alias2),(typeof helper === alias3 ? helper.call(alias1,{"name":"totalQt","hash":{},"data":data}) : helper)))
    + "\" \r\n              data-total = \""
    + alias4(((helper = (helper = helpers.Minqt || (depth0 != null ? depth0.Minqt : depth0)) != null ? helper : alias2),(typeof helper === alias3 ? helper.call(alias1,{"name":"Minqt","hash":{},"data":data}) : helper)))
    + "\">\r\n            </span>    \r\n        </span>\r\n        <span class = \" detail-progress-bar preview-bar\">\r\n            <span class = \"progress\" \r\n              data-unit = \"1\"\r\n              data-progress = \""
    + alias4(((helper = (helper = helpers.totalQt || (depth0 != null ? depth0.totalQt : depth0)) != null ? helper : alias2),(typeof helper === alias3 ? helper.call(alias1,{"name":"totalQt","hash":{},"data":data}) : helper)))
    + "\" \r\n              data-total = \""
    + alias4(((helper = (helper = helpers.Minqt || (depth0 != null ? depth0.Minqt : depth0)) != null ? helper : alias2),(typeof helper === alias3 ? helper.call(alias1,{"name":"Minqt","hash":{},"data":data}) : helper)))
    + "\">\r\n            </span>    \r\n        </span>\r\n";
},"3":function(container,depth0,helpers,partials,data) {
    var helper, alias1=depth0 != null ? depth0 : {}, alias2=helpers.helperMissing, alias3="function", alias4=container.escapeExpression;

  return "            \r\n             <ul class=\"action-icons menu-section\" id = \"preference-action\">\r\n                <li class=\"action-heart\" id=\"action-heart\">\r\n                    <i  class=\"foowd-icons foowd-icon-plus\" \r\n                        onClick = \"ProductDetailController.addPreference("
    + alias4(((helper = (helper = helpers.Id || (depth0 != null ? depth0.Id : depth0)) != null ? helper : alias2),(typeof helper === alias3 ? helper.call(alias1,{"name":"Id","hash":{},"data":data}) : helper)))
    + ", 1)\"></i>\r\n                </li>\r\n                <li class=\"action-minus\">\r\n                    <i class=\"foowd-icons foowd-icon-minus fw-menu-icon\" \r\n                       onClick = \"ProductDetailController.addPreference("
    + alias4(((helper = (helper = helpers.Id || (depth0 != null ? depth0.Id : depth0)) != null ? helper : alias2),(typeof helper === alias3 ? helper.call(alias1,{"name":"Id","hash":{},"data":data}) : helper)))
    + ", -1)\"></i>\r\n                </li>\r\n            </ul>\r\n            \r\n";
},"5":function(container,depth0,helpers,partials,data) {
    return "\r\n            <ul class=\"action-icons menu-section\" id = \"preference-action\">\r\n                <li class=\"action-heart\" id=\"action-heart\">\r\n                    <i  class=\"foowd-icons foowd-icon-plus\"  onClick = \"utils.goTo('login');\"></i>\r\n                </li>\r\n                <li class=\"action-minus\">\r\n                    <i class=\"foowd-icons foowd-icon-minus fw-menu-icon\"  onClick = \"utils.goTo('login')\"></i>\r\n                </li>\r\n            </ul>\r\n            \r\n";
},"7":function(container,depth0,helpers,partials,data) {
    var helper, alias1=depth0 != null ? depth0 : {}, alias2=helpers.helperMissing, alias3="function", alias4=container.escapeExpression;

  return "                    <div class = \"commercial-menu-item\">\r\n                        <ul class=\"action-icons menu-section\" id = \"preference-action\">\r\n                            <li id=\"action-buy\">\r\n                                <i id=\"action-buy-icon\" class=\"foowd-icons foowd-icon-cart\" \r\n                                onClick = \"ProductDetailController.purchase("
    + alias4(((helper = (helper = helpers.Id || (depth0 != null ? depth0.Id : depth0)) != null ? helper : alias2),(typeof helper === alias3 ? helper.call(alias1,{"name":"Id","hash":{},"data":data}) : helper)))
    + ",'"
    + alias4(((helper = (helper = helpers.prefers || (depth0 != null ? depth0.prefers : depth0)) != null ? helper : alias2),(typeof helper === alias3 ? helper.call(alias1,{"name":"prefers","hash":{},"data":data}) : helper)))
    + "')\"></i>\r\n                            </li>\r\n                        </ul>\r\n                    </div>\r\n";
},"compiler":[7,">= 4.0.0"],"main":function(container,depth0,helpers,partials,data) {
    var stack1, helper, alias1=depth0 != null ? depth0 : {}, alias2=helpers.helperMissing, alias3="function", alias4=container.escapeExpression;

  return "<div id=\"detail-menu\">\r\n    <div class=\"detail-menu-section\" id=\"price-section\">\r\n"
    + ((stack1 = helpers["if"].call(alias1,(depth0 != null ? depth0.logged : depth0),{"name":"if","hash":{},"fn":container.program(1, data, 0),"inverse":container.noop,"data":data})) != null ? stack1 : "")
    + "        <div class=\"price-detail\">\r\n            <ul class=\"number-block\" id=\"unit-price\">\r\n                <li>"
    + alias4(((helper = (helper = helpers.Price || (depth0 != null ? depth0.Price : depth0)) != null ? helper : alias2),(typeof helper === alias3 ? helper.call(alias1,{"name":"Price","hash":{},"data":data}) : helper)))
    + "<span class=\"apex\">€</span></li>\r\n                <li><span class =\"number-description\">cadauno</span></li>\r\n            </ul>\r\n        </div>\r\n        <div class=\"price-detail\">\r\n            <ul class=\"number-block\" id=\"min-order-price\">\r\n                <li>"
    + alias4((helpers.math || (depth0 && depth0.math) || alias2).call(alias1,(depth0 != null ? depth0.Price : depth0),"*",(depth0 != null ? depth0.Minqt : depth0),{"name":"math","hash":{},"data":data}))
    + "<span class=\"apex\">€</span></li>\r\n                <li><span class =\"number-description\">ordine minimo</span></li>\r\n            </ul>\r\n        </div>\r\n    </div>\r\n    <div class=\"detail-menu-section\" id =\"menu-close\">\r\n        <span class=\"foowd-icons foowd-icon-close\" id =\"close-detail\" onClick=\"utils.goTo('')\"></span>\r\n    </div>\r\n</div>\r\n<div id=\"product-menu\">\r\n    <div class=\"product-menu-section\" id=\"product-info\">\r\n        <div class=\"product-info-menu\" id = \"section1\">\r\n\r\n            <div class=\"menu-section\" id=\"product-name\">\r\n                "
    + alias4(((helper = (helper = helpers.Name || (depth0 != null ? depth0.Name : depth0)) != null ? helper : alias2),(typeof helper === alias3 ? helper.call(alias1,{"name":"Name","hash":{},"data":data}) : helper)))
    + "\r\n            </div>\r\n            <div class=\"menu-section\" id=\"product-company\">\r\n                "
    + ((stack1 = ((helper = (helper = helpers.Company || (depth0 != null ? depth0.Company : depth0)) != null ? helper : alias2),(typeof helper === alias3 ? helper.call(alias1,{"name":"Company","hash":{},"data":data}) : helper))) != null ? stack1 : "")
    + "\r\n            </div>\r\n"
    + ((stack1 = helpers["if"].call(alias1,(depth0 != null ? depth0.logged : depth0),{"name":"if","hash":{},"fn":container.program(3, data, 0),"inverse":container.program(5, data, 0),"data":data})) != null ? stack1 : "")
    + "            \r\n\r\n        </div>\r\n        <div class=\"product-info-menu\" id = \"section2\">\r\n            <div id=\"product-description\">\r\n               "
    + ((stack1 = ((helper = (helper = helpers.Description || (depth0 != null ? depth0.Description : depth0)) != null ? helper : alias2),(typeof helper === alias3 ? helper.call(alias1,{"name":"Description","hash":{},"data":data}) : helper))) != null ? stack1 : "")
    + " \r\n            </div>\r\n            <div id=\"commercial-menu\">\r\n                <div class = \"commercial-menu-item\">\r\n                    <div class=\"item-container\">\r\n                        <span class=\"foowd-icons item-icon\"></span>\r\n                        <span class = \"item-data\">\r\n                            "
    + alias4(((helper = (helper = helpers.Quota || (depth0 != null ? depth0.Quota : depth0)) != null ? helper : alias2),(typeof helper === alias3 ? helper.call(alias1,{"name":"Quota","hash":{},"data":data}) : helper)))
    + " "
    + alias4(((helper = (helper = helpers.Unit || (depth0 != null ? depth0.Unit : depth0)) != null ? helper : alias2),(typeof helper === alias3 ? helper.call(alias1,{"name":"Unit","hash":{},"data":data}) : helper)))
    + " "
    + alias4(((helper = (helper = helpers.UnitExtra || (depth0 != null ? depth0.UnitExtra : depth0)) != null ? helper : alias2),(typeof helper === alias3 ? helper.call(alias1,{"name":"UnitExtra","hash":{},"data":data}) : helper)))
    + "                \r\n                        </span>\r\n                    </div>\r\n                </div>\r\n                <div class = \"commercial-menu-item\">\r\n                    <div class=\"item-container\">\r\n                        <span class=\"foowd-icons foowd-icon-heart-edge item-icon\">\r\n                        </span>\r\n                        <span class = \"item-title\">Quote</span>\r\n                        <span class = \"item-data\">x"
    + alias4(((helper = (helper = helpers.totalQt || (depth0 != null ? depth0.totalQt : depth0)) != null ? helper : alias2),(typeof helper === alias3 ? helper.call(alias1,{"name":"totalQt","hash":{},"data":data}) : helper)))
    + "</span>\r\n                    </div>\r\n                </div>\r\n                <div class = \"commercial-menu-item\">\r\n                    <div class=\"item-container\">\r\n                        <span class=\"foowd-icons foowd-icon-cart item-icon\"></span>\r\n                        <span class = \"item-title\">Carrello</span>\r\n                        <span class = \"item-data\">\r\n                            "
    + alias4((helpers.math || (depth0 && depth0.math) || alias2).call(alias1,(depth0 != null ? depth0.totalQt : depth0),"*",(depth0 != null ? depth0.Price : depth0),{"name":"math","hash":{},"data":data}))
    + "\r\n                            <span class = \"apex\">€</span>\r\n                        </span>\r\n                    </div>\r\n                </div>\r\n                \r\n"
    + ((stack1 = (helpers.canbuy || (depth0 && depth0.canbuy) || alias2).call(alias1,(depth0 != null ? depth0.totalQt : depth0),(depth0 != null ? depth0.Minqt : depth0),{"name":"canbuy","hash":{},"fn":container.program(7, data, 0),"inverse":container.noop,"data":data})) != null ? stack1 : "")
    + "\r\n            </div>\r\n        </div>\r\n    </div>\r\n    <div class = \"product-menu-section\" id=\"product-picture\">\r\n       <img src=\""
    + alias4(((helper = (helper = helpers.picture || (depth0 != null ? depth0.picture : depth0)) != null ? helper : alias2),(typeof helper === alias3 ? helper.call(alias1,{"name":"picture","hash":{},"data":data}) : helper)))
    + "\"/>\r\n    </div>\r\n</div>\r\n";
},"useData":true});
templates['productPost'] = template({"1":function(container,depth0,helpers,partials,data) {
    return "                <div class=\"product-post-progress-price\">\r\n                   "
    + container.escapeExpression((helpers.math || (depth0 && depth0.math) || helpers.helperMissing).call(depth0 != null ? depth0 : {},(depth0 != null ? depth0.totalQt : depth0),"*",(depth0 != null ? depth0.Price : depth0),{"name":"math","hash":{},"data":data}))
    + "\r\n                   <span class=\"apex\">€</span>\r\n                </div>\r\n";
},"3":function(container,depth0,helpers,partials,data) {
    var helper, alias1=depth0 != null ? depth0 : {}, alias2=helpers.helperMissing, alias3="function", alias4=container.escapeExpression;

  return "                <span class=\"mini-progress-bar\">\r\n                    <span class=\"mini-progress\" data-unit=\"1\" data-progress=\""
    + alias4(((helper = (helper = helpers.totalQt || (depth0 != null ? depth0.totalQt : depth0)) != null ? helper : alias2),(typeof helper === alias3 ? helper.call(alias1,{"name":"totalQt","hash":{},"data":data}) : helper)))
    + "\" data-total=\""
    + alias4(((helper = (helper = helpers.Minqt || (depth0 != null ? depth0.Minqt : depth0)) != null ? helper : alias2),(typeof helper === alias3 ? helper.call(alias1,{"name":"Minqt","hash":{},"data":data}) : helper)))
    + "\"></span>\r\n                </span>\r\n";
},"compiler":[7,">= 4.0.0"],"main":function(container,depth0,helpers,partials,data) {
    var stack1, helper, alias1=depth0 != null ? depth0 : {}, alias2=helpers.helperMissing, alias3="function", alias4=container.escapeExpression;

  return "<li class=\"product-post\">\r\n    <div class=\"post-container\" data-product-id=\""
    + alias4(((helper = (helper = helpers.Id || (depth0 != null ? depth0.Id : depth0)) != null ? helper : alias2),(typeof helper === alias3 ? helper.call(alias1,{"name":"Id","hash":{},"data":data}) : helper)))
    + "\">\r\n        <a class=\"product-post-main-frame\" href=\""
    + alias4(((helper = (helper = helpers.productDetailUri || (depth0 != null ? depth0.productDetailUri : depth0)) != null ? helper : alias2),(typeof helper === alias3 ? helper.call(alias1,{"name":"productDetailUri","hash":{},"data":data}) : helper)))
    + "\">\r\n            <div class=\"product-post-image-thumbnail\">\r\n                <figure class=\"tint\" >\r\n                    <img src=\""
    + alias4(((helper = (helper = helpers.picture || (depth0 != null ? depth0.picture : depth0)) != null ? helper : alias2),(typeof helper === alias3 ? helper.call(alias1,{"name":"picture","hash":{},"data":data}) : helper)))
    + "\">\r\n                </figure>\r\n                <!-- Questo span permette ai contenuti di allinearsi con la larghezza dell'immagine\r\n                In pratica detta quanto sia la larghezza del div contenitore (vedi WallController.adjustOverlays--> \r\n                  <span class=\"heart-overlay\">\r\n                </span>\r\n            </div>\r\n            <div class=\"product-post-header\">\r\n                <div class=\"product-post-name\">\r\n                    <span class=\"product-post-title\">"
    + alias4(((helper = (helper = helpers.Name || (depth0 != null ? depth0.Name : depth0)) != null ? helper : alias2),(typeof helper === alias3 ? helper.call(alias1,{"name":"Name","hash":{},"data":data}) : helper)))
    + "</span>\r\n                    <br/>\r\n                    <br/>\r\n                    <span class=\"product-post-unit-price\">\r\n                        "
    + alias4(((helper = (helper = helpers.Quota || (depth0 != null ? depth0.Quota : depth0)) != null ? helper : alias2),(typeof helper === alias3 ? helper.call(alias1,{"name":"Quota","hash":{},"data":data}) : helper)))
    + " "
    + alias4(((helper = (helper = helpers.Unit || (depth0 != null ? depth0.Unit : depth0)) != null ? helper : alias2),(typeof helper === alias3 ? helper.call(alias1,{"name":"Unit","hash":{},"data":data}) : helper)))
    + " "
    + alias4(((helper = (helper = helpers.UnitExtra || (depth0 != null ? depth0.UnitExtra : depth0)) != null ? helper : alias2),(typeof helper === alias3 ? helper.call(alias1,{"name":"UnitExtra","hash":{},"data":data}) : helper)))
    + "\r\n                        <br/>\r\n                        "
    + alias4(((helper = (helper = helpers.Price || (depth0 != null ? depth0.Price : depth0)) != null ? helper : alias2),(typeof helper === alias3 ? helper.call(alias1,{"name":"Price","hash":{},"data":data}) : helper)))
    + "€\r\n                    </span>\r\n                </div>\r\n"
    + ((stack1 = helpers["if"].call(alias1,(depth0 != null ? depth0.logged : depth0),{"name":"if","hash":{},"fn":container.program(1, data, 0),"inverse":container.noop,"data":data})) != null ? stack1 : "")
    + "            </div>\r\n            <div class=\"product-post-footer\">\r\n                <div class=\"product-post-company\" onclick=\"window.go2ProducerSite("
    + alias4(((helper = (helper = helpers.Publisher || (depth0 != null ? depth0.Publisher : depth0)) != null ? helper : alias2),(typeof helper === alias3 ? helper.call(alias1,{"name":"Publisher","hash":{},"data":data}) : helper)))
    + ",event)\">\r\n                       <span  class=\"foowd-icons foowd-icon-len\">\r\n                       </span>\r\n                       <span class=\"product-company-name\">\r\n                        "
    + ((stack1 = ((helper = (helper = helpers.Company || (depth0 != null ? depth0.Company : depth0)) != null ? helper : alias2),(typeof helper === alias3 ? helper.call(alias1,{"name":"Company","hash":{},"data":data}) : helper))) != null ? stack1 : "")
    + "\r\n                       </span>\r\n                </div>\r\n"
    + ((stack1 = helpers["if"].call(alias1,(depth0 != null ? depth0.logged : depth0),{"name":"if","hash":{},"fn":container.program(3, data, 0),"inverse":container.noop,"data":data})) != null ? stack1 : "")
    + "            </div>\r\n        </a>\r\n        <div class=\"product-post-description\">\r\n            "
    + ((stack1 = ((helper = (helper = helpers.Description || (depth0 != null ? depth0.Description : depth0)) != null ? helper : alias2),(typeof helper === alias3 ? helper.call(alias1,{"name":"Description","hash":{},"data":data}) : helper))) != null ? stack1 : "")
    + "\r\n        </div>\r\n        <div class=\"product-post-tags\">\r\n                "
    + ((stack1 = (helpers.listTags || (depth0 && depth0.listTags) || alias2).call(alias1,(depth0 != null ? depth0.Tag : depth0),{"name":"listTags","hash":{},"data":data})) != null ? stack1 : "")
    + "\r\n        </div>\r\n    </div>\r\n</li>";
},"useData":true});
templates['userPreference'] = template({"1":function(container,depth0,helpers,partials,data) {
    var stack1, helper, alias1=depth0 != null ? depth0 : {}, alias2=helpers.helperMissing, alias3="function", alias4=container.escapeExpression, alias5=container.lambda;

  return "    <div class=\"preference\">\r\n       <span class = \"preference-progress-bar\">\r\n            <span class = \"progress\" \r\n              data-unit = \"1\"\r\n              data-progress = \""
    + alias4(((helper = (helper = helpers.totalQt || (depth0 != null ? depth0.totalQt : depth0)) != null ? helper : alias2),(typeof helper === alias3 ? helper.call(alias1,{"name":"totalQt","hash":{},"data":data}) : helper)))
    + "\" \r\n              data-total = \""
    + alias4(alias5(((stack1 = (depth0 != null ? depth0.offer : depth0)) != null ? stack1.Minqt : stack1), depth0))
    + "\">\r\n            </span>    \r\n        </span>\r\n        <div class=\"user-preference\">\r\n            <div class=\"user-preference-section\">\r\n                <a href=\""
    + alias4(alias5(((stack1 = (depth0 != null ? depth0.offer : depth0)) != null ? stack1.detailUri : stack1), depth0))
    + "\">\r\n                <img src=\""
    + alias4(alias5(((stack1 = (depth0 != null ? depth0.offer : depth0)) != null ? stack1.picture : stack1), depth0))
    + "\" class = \"user-preference-image\" \r\n                     onclick=\"utils.go2('detail', 'productId',"
    + alias4(alias5(((stack1 = (depth0 != null ? depth0.offer : depth0)) != null ? stack1.Id : stack1), depth0))
    + ",event)\"> \r\n                     </a>\r\n            </div>\r\n            <div class=\"user-preference-name user-preference-section\">\r\n                <ul class=\"number-block\">\r\n                    <li>"
    + alias4(alias5(((stack1 = (depth0 != null ? depth0.offer : depth0)) != null ? stack1.Name : stack1), depth0))
    + "</li>\r\n                    <li><span class=\"product-company\">"
    + ((stack1 = alias5(((stack1 = (depth0 != null ? depth0.offer : depth0)) != null ? stack1.Company : stack1), depth0)) != null ? stack1 : "")
    + "</span></li>\r\n                </ul>\r\n            </div>\r\n            <div class=\"user-preference-details user-preference-section\">\r\n                <ul class=\"number-block preference-detail\">\r\n                    <li>"
    + alias4(alias5(((stack1 = (depth0 != null ? depth0.offer : depth0)) != null ? stack1.Price : stack1), depth0))
    + "€</li>\r\n                    <li><span class =\"number-description\">cadauno</span></li>\r\n                </ul>\r\n                <ul class=\"number-block preference-detail\">\r\n                    <li>x"
    + alias4(((helper = (helper = helpers.totalQt || (depth0 != null ? depth0.totalQt : depth0)) != null ? helper : alias2),(typeof helper === alias3 ? helper.call(alias1,{"name":"totalQt","hash":{},"data":data}) : helper)))
    + "</li>\r\n                    <li><span class =\"number-description\">carrello</span></li>\r\n                </ul>\r\n                <ul class=\"number-block preference-detail\">\r\n                    <li>"
    + alias4((helpers.math || (depth0 && depth0.math) || alias2).call(alias1,(depth0 != null ? depth0.totalQt : depth0),"*",((stack1 = (depth0 != null ? depth0.offer : depth0)) != null ? stack1.Price : stack1),{"name":"math","hash":{},"data":data}))
    + "€</li>\r\n                    <li><span class =\"number-description\">tot.spesa</span></li>\r\n                </ul>\r\n            </div>\r\n            <div class=\"user-preference-actions user-preference-section\">\r\n                <ul class=\"action-icons menu-section\" id = \"preference-action\">\r\n"
    + ((stack1 = (helpers.canbuy || (depth0 && depth0.canbuy) || alias2).call(alias1,(depth0 != null ? depth0.totalQt : depth0),((stack1 = (depth0 != null ? depth0.offer : depth0)) != null ? stack1.Minqt : stack1),{"name":"canbuy","hash":{},"fn":container.program(2, data, 0),"inverse":container.noop,"data":data})) != null ? stack1 : "")
    + "                \r\n                    <li class=\"action-heart-border\">\r\n                        <i  class=\"foowd-icons foowd-icon-plus\" \r\n                            onClick = \"UserBoardController.addPreference("
    + alias4(alias5(((stack1 = (depth0 != null ? depth0.offer : depth0)) != null ? stack1.Id : stack1), depth0))
    + ", 1)\"></i>\r\n                    </li>\r\n                    <li class=\"action-minus\">\r\n                        <i class=\"foowd-icons foowd-icon-minus fw-menu-icon\" \r\n                           onClick = \"UserBoardController.addPreference("
    + alias4(alias5(((stack1 = (depth0 != null ? depth0.offer : depth0)) != null ? stack1.Id : stack1), depth0))
    + ", -1)\"></i>\r\n                    </li>\r\n                </ul>\r\n            </div>\r\n        </div>\r\n    </div>\r\n";
},"2":function(container,depth0,helpers,partials,data) {
    var stack1, alias1=container.lambda, alias2=container.escapeExpression;

  return "                    <li class=\"btn-buy\" data-offer-id=\""
    + alias2(alias1(((stack1 = (depth0 != null ? depth0.offer : depth0)) != null ? stack1.Id : stack1), depth0))
    + "\" data-offer-prefers=\""
    + alias2(alias1(((stack1 = (depth0 != null ? depth0.offer : depth0)) != null ? stack1.prefers : stack1), depth0))
    + "\">\r\n                        <i class=\"btn-buy-icon foowd-icons foowd-icon-cart\"></i>\r\n                    </li>\r\n";
},"compiler":[7,">= 4.0.0"],"main":function(container,depth0,helpers,partials,data) {
    var stack1;

  return ((stack1 = helpers["if"].call(depth0 != null ? depth0 : {},(depth0 != null ? depth0.totalQt : depth0),{"name":"if","hash":{},"fn":container.program(1, data, 0),"inverse":container.noop,"data":data})) != null ? stack1 : "");
},"useData":true});
return templates;
});