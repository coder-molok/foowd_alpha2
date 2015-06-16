define(['handlebars.runtime'], function(Handlebars) {
  Handlebars = Handlebars["default"];  var template = Handlebars.template, templates = Handlebars.templates = Handlebars.templates || {};
templates['productDetail'] = template({"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
    var stack1, helper, alias1=helpers.helperMissing, alias2="function", alias3=this.escapeExpression;

  return "<div class = \"row\" id=\"detail-menu\">\n    <div class=\"col-xs-10 col-sm-4 col-md-6\">\n        <ul class=\"number-block\">\n            <li>"
    + alias3(((helper = (helper = helpers.Price || (depth0 != null ? depth0.Price : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Price","hash":{},"data":data}) : helper)))
    + "<span class=\"apex\">€</span></li>\n            <li><span class =\"number-description\">Prezzo Unità</span></li>\n        </ul>\n    </div>\n    <div class=\"col-xs-10 col-sm-4 col-md-6\">\n        <ul class=\"number-block\">\n            <li>"
    + alias3((helpers.math || (depth0 && depth0.math) || alias1).call(depth0,(depth0 != null ? depth0.Price : depth0),"*",(depth0 != null ? depth0.Minqt : depth0),{"name":"math","hash":{},"data":data}))
    + "<span class=\"apex\">€</span></li>\n            <li><span class =\"number-description\">Ordine Minimo</span></li>\n        </ul>\n    </div>\n</div>\n<div class = \"row\" id=\"detail-info\">\n    <div class=\"col-xs-10 col-sm-4 col-md-6\">\n        <div class=\"row\" id=\"detail-info-row1\">\n            <div class=\"col-xs-6 col-md-6 product-name\">\n                "
    + alias3(((helper = (helper = helpers.Name || (depth0 != null ? depth0.Name : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Name","hash":{},"data":data}) : helper)))
    + "\n            </div>\n            <div class=\"col-xs-6 col-md-6 product-info\">\n                <ul class=\"number-block\">\n                    <li>4</li>\n                    <li><span class =\"number-description\">Followers</span></li>\n                </ul>\n                <ul class=\"number-block\">\n                    <li>12</li>\n                    <li><span class =\"number-description\">Share</span></li>\n                </ul>\n                <ul class=\"action-icons\">\n                    <li id=\"action-heart\">\n                        <i  class=\"glyphicon glyphicon-heart fw-menu-icon\"></i>\n                    </li>\n                    <li id=\"action-minus\">\n                        <i class=\"glyphicon glyphicon-minus fw-menu-icon\"></i>\n                    </li>\n                </ul>\n            </div>\n        </div>\n        <div class=\"row\" id=\"detail-info-row1\">\n            <div class=\"col-xs-6 col-md-6 product-description\">\n                "
    + ((stack1 = ((helper = (helper = helpers.Description || (depth0 != null ? depth0.Description : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Description","hash":{},"data":data}) : helper))) != null ? stack1 : "")
    + "\n            </div>\n            <div class=\"col-xs-6 col-md-6 product-commercial-info\">\n                <ul class=\"commercial-menu\">\n                    <li>\n                        <i id=\"\" class=\"glyphicon glyphicon-heart fw-menu-icon\"></i>\n                        carrello\n                    </li>\n                    <li>\n                        <i id=\"\" class=\"glyphicon glyphicon-unchecked fw-menu-icon\"></i>\n                        tot\n                    </li>\n                    <li>\n                        <i id=\"\" class=\"glyphicon glyphicon-unchecked fw-menu-icon\"></i>\n                        spesa comune raggiunta\n                    </li>\n                </ul>\n            </div>\n        </div>\n    </div>\n    <div class=\"col-xs-10 col-sm-4 col-md-6 product-picture\">\n       <img src=\""
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
return templates;
});