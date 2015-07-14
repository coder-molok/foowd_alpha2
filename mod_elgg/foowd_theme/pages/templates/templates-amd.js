define(['handlebars.runtime'], function(Handlebars) {
  Handlebars = Handlebars["default"];  var template = Handlebars.template, templates = Handlebars.templates = Handlebars.templates || {};
templates['productDetail'] = template({"1":function(depth0,helpers,partials,data) {
    var helper, alias1=helpers.helperMissing, alias2="function", alias3=this.escapeExpression;

  return "            <ul class=\"action-icons menu-section\" id = \"preference-action\">\n                <li id=\"action-heart\">\n                    <i  class=\"glyphicon glyphicon-heart fw-menu-icon\" \n                        onClick = \"ProductDetailController.addPreference("
    + alias3(((helper = (helper = helpers.Id || (depth0 != null ? depth0.Id : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Id","hash":{},"data":data}) : helper)))
    + ", 1)\"></i>\n                </li>\n                <li id=\"action-minus\">\n                    <i class=\"glyphicon glyphicon-minus fw-menu-icon\" \n                       onClick = \"ProductDetailController.addPreference("
    + alias3(((helper = (helper = helpers.Id || (depth0 != null ? depth0.Id : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Id","hash":{},"data":data}) : helper)))
    + ", -1)\"></i>\n                </li>\n            </ul>\n";
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
    var stack1, helper, alias1=helpers.helperMissing, alias2="function", alias3=this.escapeExpression;

  return "<div id=\"detail-menu\">\n    <div class=\"detail-menu-section\" id=\"price-section\">\n        <span class = \"detail-progress-bar\">\n            <span class = \"progress\" \n              data-unit = \"1\"\n              data-progress = \""
    + alias3(((helper = (helper = helpers.totalQt || (depth0 != null ? depth0.totalQt : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"totalQt","hash":{},"data":data}) : helper)))
    + "\" \n              data-total = \""
    + alias3(((helper = (helper = helpers.Minqt || (depth0 != null ? depth0.Minqt : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Minqt","hash":{},"data":data}) : helper)))
    + "\">\n            </span>    \n        </span>\n        <span class = \"detail-progress-bar preview-bar\">\n            <span class = \"progress\" \n              data-unit = \"1\"\n              data-progress = \""
    + alias3(((helper = (helper = helpers.totalQt || (depth0 != null ? depth0.totalQt : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"totalQt","hash":{},"data":data}) : helper)))
    + "\" \n              data-total = \""
    + alias3(((helper = (helper = helpers.Minqt || (depth0 != null ? depth0.Minqt : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Minqt","hash":{},"data":data}) : helper)))
    + "\">\n            </span>    \n        </span>\n        <div class=\"price-detail\">\n            <ul class=\"number-block\" id=\"unit-price\">\n                <li>"
    + alias3(((helper = (helper = helpers.Price || (depth0 != null ? depth0.Price : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Price","hash":{},"data":data}) : helper)))
    + "<span class=\"apex\">€</span></li>\n                <li><span class =\"number-description\">prezzo unità</span></li>\n            </ul>\n        </div>\n        <div class=\"price-detail\">\n            <ul class=\"number-block\" id=\"min-order-price\">\n                <li>"
    + alias3((helpers.math || (depth0 && depth0.math) || alias1).call(depth0,(depth0 != null ? depth0.Price : depth0),"*",(depth0 != null ? depth0.Minqt : depth0),{"name":"math","hash":{},"data":data}))
    + "<span class=\"apex\">€</span></li>\n                <li><span class =\"number-description\">ordine minimo</span></li>\n            </ul>\n        </div>\n    </div>\n    <div class=\"detail-menu-section\" id =\"menu-close\">\n        <span class=\"glyphicon glyphicon-remove-circle fw-menu-icon\" id =\"close-detail\" onClick=\"utils.goTo('')\"></span>\n    </div>\n</div>\n<div id=\"product-menu\">\n    <div class=\"product-menu-section\" id=\"product-info\">\n        <div class=\"product-info-menu\" id = \"section1\">\n\n            <div class=\"menu-section\" id=\"product-name\">\n                "
    + alias3(((helper = (helper = helpers.Name || (depth0 != null ? depth0.Name : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Name","hash":{},"data":data}) : helper)))
    + "\n            </div>\n            \n"
    + ((stack1 = helpers['if'].call(depth0,(depth0 != null ? depth0.logged : depth0),{"name":"if","hash":{},"fn":this.program(1, data, 0),"inverse":this.noop,"data":data})) != null ? stack1 : "")
    + "\n        </div>\n        <div class=\"product-info-menu\" id = \"section2\">\n            <div class = \"menu-section\" id=\"product-description\">\n                "
    + ((stack1 = ((helper = (helper = helpers.Description || (depth0 != null ? depth0.Description : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Description","hash":{},"data":data}) : helper))) != null ? stack1 : "")
    + "\n            </div>\n            <div class = \"menu-section\" id=\"product-commercial-info\">\n                <ul id=\"commercial-menu\">\n                    <li>\n                        <i id=\"\" class=\"glyphicon glyphicon-heart fw-menu-icon\"></i>\n                        carrello\n                    </li>\n                    <li>\n                        <i id=\"\" class=\"glyphicon glyphicon-unchecked fw-menu-icon\"></i>\n                        tot\n                    </li>\n                </ul>\n            </div>\n        </div>\n    </div>\n    <div class = \"product-menu-section\" id=\"product-picture\">\n       <img src=\""
    + alias3(((helper = (helper = helpers.picture || (depth0 != null ? depth0.picture : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"picture","hash":{},"data":data}) : helper)))
    + "\"/>\n    </div>\n</div>\n";
},"useData":true});
templates['productLogged'] = template({"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
    var stack1, helper, alias1=helpers.helperMissing, alias2="function", alias3=this.escapeExpression;

  return "<li class=\"product-post\">\n    <div class=\"product-post-image-thumbnail\" \n         onclick=\"WallController.addPreference("
    + alias3(((helper = (helper = helpers.Id || (depth0 != null ? depth0.Id : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Id","hash":{},"data":data}) : helper)))
    + ","
    + alias3(((helper = (helper = helpers.Quota || (depth0 != null ? depth0.Quota : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Quota","hash":{},"data":data}) : helper)))
    + ")\">\n        <img src=\""
    + alias3(((helper = (helper = helpers.picture || (depth0 != null ? depth0.picture : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"picture","hash":{},"data":data}) : helper)))
    + "\">\n        <span class=\"heart-overlay foowd-icons foowd-icon-heart-full\">\n        </span>\n        <div class=\"product-post-menu\">\n            <div onclick=\"utils.goProductDetail("
    + alias3(((helper = (helper = helpers.Id || (depth0 != null ? depth0.Id : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Id","hash":{},"data":data}) : helper)))
    + ")\">\n               <span class = \"foowd-icons foowd-icon-len\">\n               </span>\n            </div>\n            <div onclick=\"utils.goTo('producer')\">\n               <span class = \"foowd-icons foowd-icon-blade\">\n               </span>\n            </div>\n        </div>\n    </div>\n    <div class=\"product-post-header\">\n        <div class=\"product-post-name\">\n            "
    + alias3(((helper = (helper = helpers.Name || (depth0 != null ? depth0.Name : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Name","hash":{},"data":data}) : helper)))
    + "\n            <br/>\n            <br/>\n            <span class=\"product-post-unit-price\">\n                "
    + alias3(((helper = (helper = helpers.Quota || (depth0 != null ? depth0.Quota : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Quota","hash":{},"data":data}) : helper)))
    + " "
    + alias3(((helper = (helper = helpers.Unit || (depth0 != null ? depth0.Unit : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Unit","hash":{},"data":data}) : helper)))
    + " "
    + alias3(((helper = (helper = helpers.UnitExtra || (depth0 != null ? depth0.UnitExtra : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"UnitExtra","hash":{},"data":data}) : helper)))
    + "\n                <br/>\n                "
    + alias3(((helper = (helper = helpers.Price || (depth0 != null ? depth0.Price : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Price","hash":{},"data":data}) : helper)))
    + "€\n            </span>\n        </div>\n        <span class=\"mini-progress-bar\">\n            <span class=\"mini-progress\" data-unit=\"1\" data-progress=\""
    + alias3(((helper = (helper = helpers.totalQt || (depth0 != null ? depth0.totalQt : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"totalQt","hash":{},"data":data}) : helper)))
    + "\" data-total=\""
    + alias3(((helper = (helper = helpers.Minqt || (depth0 != null ? depth0.Minqt : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Minqt","hash":{},"data":data}) : helper)))
    + "\"></span>\n        </span>  \n        <div class=\"product-post-progress-price\">\n        "
    + alias3((helpers.math || (depth0 && depth0.math) || alias1).call(depth0,(depth0 != null ? depth0.totalQt : depth0),"*",(depth0 != null ? depth0.Price : depth0),{"name":"math","hash":{},"data":data}))
    + "\n            <span class=\"apex\">€</span>\n        </div>\n    </div>\n    <div class=\"product-post-body\">\n            "
    + ((stack1 = ((helper = (helper = helpers.Description || (depth0 != null ? depth0.Description : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Description","hash":{},"data":data}) : helper))) != null ? stack1 : "")
    + "\n    </div>\n</li>";
},"useData":true});
templates['productNoLogged'] = template({"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
    var stack1, helper, alias1=helpers.helperMissing, alias2="function", alias3=this.escapeExpression;

  return "\n<li class=\"product-post\">\n    <div class=\"product-post-image-thumbnail\">\n        <img src=\""
    + alias3(((helper = (helper = helpers.picture || (depth0 != null ? depth0.picture : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"picture","hash":{},"data":data}) : helper)))
    + "\">\n    </div>\n    <div class=\"product-post-header\">\n        <div class=\"product-post-name\">\n            "
    + alias3(((helper = (helper = helpers.Name || (depth0 != null ? depth0.Name : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Name","hash":{},"data":data}) : helper)))
    + "\n            <br/>\n            <br/>\n            <span class=\"product-post-unit-price\">\n                "
    + alias3(((helper = (helper = helpers.Quota || (depth0 != null ? depth0.Quota : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Quota","hash":{},"data":data}) : helper)))
    + " "
    + alias3(((helper = (helper = helpers.Unit || (depth0 != null ? depth0.Unit : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Unit","hash":{},"data":data}) : helper)))
    + " "
    + alias3(((helper = (helper = helpers.UnitExtra || (depth0 != null ? depth0.UnitExtra : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"UnitExtra","hash":{},"data":data}) : helper)))
    + "\n                <br/>\n                "
    + alias3(((helper = (helper = helpers.Price || (depth0 != null ? depth0.Price : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Price","hash":{},"data":data}) : helper)))
    + "€\n            </span>\n        </div>\n        <span class=\"mini-progress-bar\">\n            <span class=\"mini-progress\" data-unit=\"1\" data-progress=\""
    + alias3(((helper = (helper = helpers.totalQt || (depth0 != null ? depth0.totalQt : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"totalQt","hash":{},"data":data}) : helper)))
    + "\" data-total=\""
    + alias3(((helper = (helper = helpers.Minqt || (depth0 != null ? depth0.Minqt : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Minqt","hash":{},"data":data}) : helper)))
    + "\"></span>\n        </span>  \n        <div class=\"product-post-progress-price\">"
    + alias3((helpers.math || (depth0 && depth0.math) || alias1).call(depth0,(depth0 != null ? depth0.totalQt : depth0),"*",(depth0 != null ? depth0.Price : depth0),{"name":"math","hash":{},"data":data}))
    + "\n            <span class=\"apex\">€</span>\n        </div>\n       \n\n    </div>\n    <div class=\"product-post-menu\">\n        <div onclick=\"utils.goProductDetail("
    + alias3(((helper = (helper = helpers.Id || (depth0 != null ? depth0.Id : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Id","hash":{},"data":data}) : helper)))
    + ")\">\n           <span class = \"like foowd-icon icon-foowd-len fw-menu-icon\">\n           </span>\n        </div>\n    </div>\n    <div class=\"product-post-body\">\n            "
    + ((stack1 = ((helper = (helper = helpers.Description || (depth0 != null ? depth0.Description : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Description","hash":{},"data":data}) : helper))) != null ? stack1 : "")
    + "\n    </div>\n</li>";
},"useData":true});
templates['searchNavbar'] = template({"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
    return "<div class=\"foowd-navbar\">\n    <div class=\"navbar-section\" id = \"logo\">\n        <div id=\"foowd-brand\">\n         <span onClick=\"utils.goTo()\">foowd_</span>\n         <input type=\"text\" id=\"searchText\" onkeypress=\"WallController.searchProducts(event)\">\n        </div>\n    </div>\n    <div class = \"navbar-section\" id=\"user-menu-section\">\n        <div id = \"user-menu\">\n            <span onClick=\"utils.goTo('board')\" \n                  class=\"foowd-icons foowd-icon-heart-edge fw-menu-icon preferences-link\">\n            </span>\n            <span onClick = \"utils.goToUserProfile()\"\n                  class=\"foowd-icons foowd-icon-user fw-menu-icon profile-link\">\n            </span>\n            <span id=\"menu\" \n                  class=\"foowd-icons foowd-icon-menu fw-menu-icon menu-link\">\n            </span>\n        </div>\n    </div>\n</div>";
},"useData":true});
templates['simpleNavbar'] = template({"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
    return "<div class=\"foowd-navbar\">\n    <div class=\"navbar-section\" id = \"logo\">\n        <div id=\"foowd-brand\">\n         <span onClick=\"utils.goTo()\">foowd_</span>\n        </div>\n    </div>\n    <div class = \"navbar-section\" id=\"user-menu-section\">\n        <div id = \"user-menu\">\n            <a id=\"heart\" onClick=\"utils.goTo('board')\">\n                <i class=\"glyphicon glyphicon-heart fw-menu-icon\">\n                </i>\n            </a>\n            <a id=\"userButton\"  onClick = \"utils.goToUserProfile()\">\n                <i class=\"glyphicon glyphicon-user fw-menu-icon\">\n                </i>\n            </a>\n            <a id=\"menu\">\n                <i class=\"glyphicon glyphicon-th-large fw-menu-icon\">\n                </i>\n            </a>\n        </div>\n    </div>\n</div>";
},"useData":true});
templates['userPreference'] = template({"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
    var stack1, helper, alias1=this.lambda, alias2=this.escapeExpression, alias3=helpers.helperMissing;

  return "<div class=\"preference\">\n   <span class = \"preference-progress-bar\">\n        <span class = \"progress\" \n          data-unit = \"1\"\n          data-progress = \""
    + alias2(alias1(((stack1 = (depth0 != null ? depth0.Offer : depth0)) != null ? stack1.totalQt : stack1), depth0))
    + "\" \n          data-total = \""
    + alias2(alias1(((stack1 = (depth0 != null ? depth0.Offer : depth0)) != null ? stack1.Minqt : stack1), depth0))
    + "\">\n        </span>    \n    </span>\n    <div class=\"user-preference\">\n        <div class=\"user-preference-section\">\n            <img src=\""
    + alias2(alias1(((stack1 = (depth0 != null ? depth0.Offer : depth0)) != null ? stack1.picture : stack1), depth0))
    + "\" class = \"user-preference-image\">    \n        </div>\n        <div class=\"user-preference-name user-preference-section\">\n            <ul class=\"number-block\">\n                <li>"
    + alias2(alias1(((stack1 = (depth0 != null ? depth0.Offer : depth0)) != null ? stack1.Name : stack1), depth0))
    + "</li>\n            </ul>\n        </div>\n        <div class=\"user-preference-details user-preference-section\">\n            <ul class=\"number-block preference-detail\">\n                <li>"
    + alias2(alias1(((stack1 = (depth0 != null ? depth0.Offer : depth0)) != null ? stack1.Price : stack1), depth0))
    + "€</li>\n                <li><span class =\"number-description\">Prezzo unità</span></li>\n            </ul>\n            <ul class=\"number-block preference-detail\">\n                <li>x"
    + alias2(((helper = (helper = helpers.Qt || (depth0 != null ? depth0.Qt : depth0)) != null ? helper : alias3),(typeof helper === "function" ? helper.call(depth0,{"name":"Qt","hash":{},"data":data}) : helper)))
    + "</li>\n                <li><span class =\"number-description\">carrello</span></li>\n            </ul>\n            <ul class=\"number-block preference-detail\">\n                <li>"
    + alias2((helpers.math || (depth0 && depth0.math) || alias3).call(depth0,(depth0 != null ? depth0.Qt : depth0),"*",((stack1 = (depth0 != null ? depth0.Offer : depth0)) != null ? stack1.Price : stack1),{"name":"math","hash":{},"data":data}))
    + "€</li>\n                <li><span class =\"number-description\">tot.spesa</span></li>\n            </ul>\n        </div>\n        <div class=\"user-preference-actions user-preference-section\">\n            <ul class=\"action-icons\">\n                <li id=\"action-heart\">\n                    <i  class = \"glyphicon glyphicon-heart fw-menu-icon\"\n                        onclick = \"UserBoardController.addPreference("
    + alias2(alias1(((stack1 = (depth0 != null ? depth0.Offer : depth0)) != null ? stack1.Id : stack1), depth0))
    + ", 1)\">\n                    </i>\n                </li>\n                <li id=\"action-minus\">\n                    <i class=\"glyphicon glyphicon-minus fw-menu-icon\"\n                       onclick = \"UserBoardController.addPreference("
    + alias2(alias1(((stack1 = (depth0 != null ? depth0.Offer : depth0)) != null ? stack1.Id : stack1), depth0))
    + ", -1)\">\n                    </i>\n                </li>\n            </ul>\n        </div>\n    </div>\n</div>";
},"useData":true});
return templates;
});