define(['handlebars.runtime'], function(Handlebars) {
  Handlebars = Handlebars["default"];  var template = Handlebars.template, templates = Handlebars.templates = Handlebars.templates || {};
templates['productDetail'] = template({"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
    var stack1, helper, alias1=helpers.helperMissing, alias2="function", alias3=this.escapeExpression;

  return "<div id=\"detail-menu\">\n    <div class=\"detail-menu-section\" id=\"price-section\">\n        <span id=\"bar\"></span>\n        <div class=\"price-detail\">\n            <ul class=\"number-block\" id=\"unit-price\">\n                <li>"
    + alias3(((helper = (helper = helpers.Price || (depth0 != null ? depth0.Price : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Price","hash":{},"data":data}) : helper)))
    + "<span class=\"apex\">€</span></li>\n                <li><span class =\"number-description\">prezzo unità</span></li>\n            </ul>\n        </div>\n        <div class=\"price-detail\">\n            <ul class=\"number-block\" id=\"min-order-price\">\n                <li>"
    + alias3((helpers.math || (depth0 && depth0.math) || alias1).call(depth0,(depth0 != null ? depth0.Price : depth0),"*",(depth0 != null ? depth0.Minqt : depth0),{"name":"math","hash":{},"data":data}))
    + "<span class=\"apex\">€</span></li>\n                <li><span class =\"number-description\">ordine minimo</span></li>\n            </ul>\n        </div>\n    </div>\n    <div class=\"detail-menu-section\" id =\"menu-close\">\n        <span class=\"glyphicon glyphicon-remove-circle fw-menu-icon\" id =\"close-detail\" onClick=\"utils.goTo('')\"></span>\n    </div>\n</div>\n<div id=\"product-menu\">\n    <div class=\"product-menu-section\" id=\"product-info\">\n        <div class=\"product-info-menu\" id = \"section1\">\n\n            <div class=\"menu-section\" id=\"product-name\">\n                "
    + alias3(((helper = (helper = helpers.Name || (depth0 != null ? depth0.Name : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Name","hash":{},"data":data}) : helper)))
    + "\n            </div>\n            \n            <ul class=\"action-icons menu-section\" id = \"preference-action\">\n                <li id=\"action-heart\">\n                    <i  class=\"glyphicon glyphicon-heart fw-menu-icon\"></i>\n                </li>\n                <li id=\"action-minus\">\n                    <i class=\"glyphicon glyphicon-minus fw-menu-icon\"></i>\n                </li>\n            </ul>\n\n        </div>\n        <div class=\"product-info-menu\" id = \"section2\">\n            <div class = \"menu-section\" id=\"product-description\">\n                "
    + ((stack1 = ((helper = (helper = helpers.Description || (depth0 != null ? depth0.Description : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Description","hash":{},"data":data}) : helper))) != null ? stack1 : "")
    + "\n            </div>\n            <div class = \"menu-section\" id=\"product-commercial-info\">\n                <ul id=\"commercial-menu\">\n                    <li>\n                        <i id=\"\" class=\"glyphicon glyphicon-heart fw-menu-icon\"></i>\n                        carrello\n                    </li>\n                    <li>\n                        <i id=\"\" class=\"glyphicon glyphicon-unchecked fw-menu-icon\"></i>\n                        tot\n                    </li>\n                </ul>\n            </div>\n        </div>\n    </div>\n    <div class = \"product-menu-section\" id=\"product-picture\">\n       <img src=\""
    + alias3(((helper = (helper = helpers.picture || (depth0 != null ? depth0.picture : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"picture","hash":{},"data":data}) : helper)))
    + "\"/>\n    </div>\n</div>\n";
},"useData":true});
templates['productLogged'] = template({"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
    var stack1, helper, alias1=helpers.helperMissing, alias2="function", alias3=this.escapeExpression;

  return "\n<div class=\"product-post\">\n    <div class=\"product-post-header\">\n        <div class=\"pull-left\"  id=\"name\">"
    + alias3(((helper = (helper = helpers.Name || (depth0 != null ? depth0.Name : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Name","hash":{},"data":data}) : helper)))
    + "</div>  \n        <div class=\"pull-right\" id=\"price\">"
    + alias3(((helper = (helper = helpers.Price || (depth0 != null ? depth0.Price : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Price","hash":{},"data":data}) : helper)))
    + "<span class=\"apex\">€</span></div>\n        <div class=\"pull-right\" id=\"qt-progress\">"
    + alias3(((helper = (helper = helpers.totalQt || (depth0 != null ? depth0.totalQt : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"totalQt","hash":{},"data":data}) : helper)))
    + "/"
    + alias3(((helper = (helper = helpers.Minqt || (depth0 != null ? depth0.Minqt : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Minqt","hash":{},"data":data}) : helper)))
    + " - </div>\n    </div>\n    <a onclick=\"utils.goProductDetail("
    + alias3(((helper = (helper = helpers.Id || (depth0 != null ? depth0.Id : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Id","hash":{},"data":data}) : helper)))
    + ")\">\n        <div class=\"product-post-image-thumbnail\">\n            <img src=\""
    + alias3(((helper = (helper = helpers.picture || (depth0 != null ? depth0.picture : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"picture","hash":{},"data":data}) : helper)))
    + "\" >\n        </div>\n    </a>\n    <div class=\"product-post-menu\">\n        <ul class=\"product-post-menu-options\">\n            <li>\n                <a onclick=\"WallController.addPreference("
    + alias3(((helper = (helper = helpers.Id || (depth0 != null ? depth0.Id : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Id","hash":{},"data":data}) : helper)))
    + ","
    + alias3(((helper = (helper = helpers.Minqt || (depth0 != null ? depth0.Minqt : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Minqt","hash":{},"data":data}) : helper)))
    + ",this)\">\n                    <i id = \"like\" class=\"glyphicon glyphicon-heart fw-menu-icon "
    + alias3(((helper = (helper = helpers.prefer || (depth0 != null ? depth0.prefer : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"prefer","hash":{},"data":data}) : helper)))
    + "\"></i>\n                </a>\n            </li>\n        </ul>\n    </div>\n    <div class=\"product-post-body\">\n            "
    + ((stack1 = ((helper = (helper = helpers.Description || (depth0 != null ? depth0.Description : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Description","hash":{},"data":data}) : helper))) != null ? stack1 : "")
    + "\n    </div>\n</div>";
},"useData":true});
templates['productNoLogged'] = template({"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
    var stack1, helper, alias1=helpers.helperMissing, alias2="function", alias3=this.escapeExpression;

  return "<div class=\"product-post\">\n    <div class=\"product-post-header\">\n        <div class=\"pull-left\"  id=\"name\">"
    + alias3(((helper = (helper = helpers.Name || (depth0 != null ? depth0.Name : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Name","hash":{},"data":data}) : helper)))
    + "</div>  \n        <div class=\"pull-right\" id=\"price\">"
    + alias3(((helper = (helper = helpers.Price || (depth0 != null ? depth0.Price : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Price","hash":{},"data":data}) : helper)))
    + "<span class=\"apex\">€</span></div>\n        <div class=\"pull-right\" id=\"qt-progress\">"
    + alias3(((helper = (helper = helpers.totalQt || (depth0 != null ? depth0.totalQt : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"totalQt","hash":{},"data":data}) : helper)))
    + "/"
    + alias3(((helper = (helper = helpers.Minqt || (depth0 != null ? depth0.Minqt : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Minqt","hash":{},"data":data}) : helper)))
    + " - </div>\n    </div>\n    <a onclick=\"utils.goProductDetail("
    + alias3(((helper = (helper = helpers.Id || (depth0 != null ? depth0.Id : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Id","hash":{},"data":data}) : helper)))
    + ")\">\n        <div class=\"product-post-image-thumbnail\">\n            <img src=\""
    + alias3(((helper = (helper = helpers.picture || (depth0 != null ? depth0.picture : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"picture","hash":{},"data":data}) : helper)))
    + "\" >\n        </div>\n    </a>\n    <div class=\"product-post-body\">\n            "
    + ((stack1 = ((helper = (helper = helpers.Description || (depth0 != null ? depth0.Description : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Description","hash":{},"data":data}) : helper))) != null ? stack1 : "")
    + "\n    </div>\n</div>";
},"useData":true});
templates['searchNavbar'] = template({"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
    return "<div class=\"foowd-navbar\">\n    <div class=\"navbar-section\" id=\"foowd-brand\">\n         <a class = \"foowd-brand\" onClick=\"utils.goTo()\">\n         foowd_</a>\n        <div id=\"search-section\">\n            <input type=\"text\" id=\"searchText\" onkeypress=\"WallController.searchProducts(event)\">\n        </div>\n    </div>\n    <div class = \"navbar-section\" id=\"user-menu-section\">\n        <div id = \"user-menu\" onClick=\"utils.goTo('board')\">\n            <a id=\"heart\">\n                <i class=\"glyphicon glyphicon-heart fw-menu-icon\">\n                </i>\n            </a>\n            <a id=\"userButton\"  onClick = \"utils.goToUserProfile()\">\n                <i class=\"glyphicon glyphicon-user fw-menu-icon\">\n                </i>\n            </a>\n            <a id=\"menu\">\n                <i class=\"glyphicon glyphicon-th-large fw-menu-icon\">\n                </i>\n            </a>\n        </div>\n    </div>\n</div>";
},"useData":true});
templates['simpleNavbar'] = template({"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
    return "<div class=\"foowd-navbar\">\n    <div class=\"navbar-section\" id=\"foowd-brand\">\n        <a class = \"foowd-brand\" onClick=\"utils.goTo()\">foowd_</a>\n    </div>\n    <!-- <div class=\"navbar-section\" id=\"search-section\">\n        <input type=\"text\" class=\"form-control\" id=\"searchText\" size = \"30\">\n    </div> -->\n    <div class = \"navbar-section\" id=\"user-menu-section\">\n        <div id = \"user-menu\" onClick=\"utils.goTo('board')\">\n            <a id=\"heart\">\n                <i class=\"glyphicon glyphicon-heart fw-menu-icon\">\n                </i>\n            </a>\n            <a id=\"userButton\"  onClick = \"utils.goToUserProfile()\">\n                <i class=\"glyphicon glyphicon-user fw-menu-icon\">\n                </i>\n            </a>\n            <a id=\"menu\">\n                <i class=\"glyphicon glyphicon-th-large fw-menu-icon\">\n                </i>\n            </a>\n        </div>\n    </div>\n</div>";
},"useData":true});
return templates;
});