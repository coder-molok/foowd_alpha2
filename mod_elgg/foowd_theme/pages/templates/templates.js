(function() {
  var template = Handlebars.template, templates = Handlebars.templates = Handlebars.templates || {};
templates['productNoLogged'] = template({"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
    var helper, alias1=helpers.helperMissing, alias2="function", alias3=this.escapeExpression;

  return "<div class=\"col-md-3\">\n    <div class=\"panel panel-default\">\n        <div class=\"panel-heading\">\n            <div class=\"pull-left\">"
    + alias3(((helper = (helper = helpers.Name || (depth0 != null ? depth0.Name : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Name","hash":{},"data":data}) : helper)))
    + "</div>  \n            <div class=\"pull-right\">x/"
    + alias3(((helper = (helper = helpers.Minqt || (depth0 != null ? depth0.Minqt : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Minqt","hash":{},"data":data}) : helper)))
    + " - "
    + alias3(((helper = (helper = helpers.Price || (depth0 != null ? depth0.Price : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Price","hash":{},"data":data}) : helper)))
    + "<span class=\"apex\">€</span></div>\n        </div>\n        <div class=\"panel-thumbnail\">\n            <img src=\"http://lorempixel.com/320/270/food\" class=\"img-responsive\">\n            <div class=\"product-menu\">\n                \n            </div>\n        </div>\n        <div class=\"panel-body\">\n            <p>\n                "
    + alias3(((helper = (helper = helpers.Description || (depth0 != null ? depth0.Description : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Description","hash":{},"data":data}) : helper)))
    + "\n            </p>\n        </div>\n    </div>\n</div>";
},"useData":true});
templates['productLogged'] = template({"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
    var helper, alias1=helpers.helperMissing, alias2="function", alias3=this.escapeExpression;

  return "<div class=\"col-md-3\">\n    <div class=\"panel panel-default\">\n        <div class=\"panel-heading\">\n            <div class=\"pull-left\">"
    + alias3(((helper = (helper = helpers.Name || (depth0 != null ? depth0.Name : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Name","hash":{},"data":data}) : helper)))
    + "</div>  \n            <div class=\"pull-right\">"
    + alias3(((helper = (helper = helpers.totalQt || (depth0 != null ? depth0.totalQt : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"totalQt","hash":{},"data":data}) : helper)))
    + "/"
    + alias3(((helper = (helper = helpers.Minqt || (depth0 != null ? depth0.Minqt : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Minqt","hash":{},"data":data}) : helper)))
    + " - "
    + alias3(((helper = (helper = helpers.Price || (depth0 != null ? depth0.Price : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Price","hash":{},"data":data}) : helper)))
    + "<span class=\"apex\">€</span></div>\n        </div>\n        <div class=\"panel-thumbnail\">\n            <img src=\"http://lorempixel.com/320/270/food\" class=\"img-responsive\">\n            <div class=\"product-menu\">\n                <ul class=\"product-menu-options\">\n                    <li>\n                        <a onclick=\"foowd.addPreference("
    + alias3(((helper = (helper = helpers.Id || (depth0 != null ? depth0.Id : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Id","hash":{},"data":data}) : helper)))
    + ","
    + alias3(((helper = (helper = helpers.Minqt || (depth0 != null ? depth0.Minqt : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Minqt","hash":{},"data":data}) : helper)))
    + ",this)\">\n                            <i id = \"like\" class=\"glyphicon glyphicon-heart fw-menu-icon "
    + alias3(((helper = (helper = helpers.prefer || (depth0 != null ? depth0.prefer : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"prefer","hash":{},"data":data}) : helper)))
    + "\"></i>\n                        </a>\n                    </li>\n                </ul>\n            </div>\n        </div>\n        <div class=\"panel-body\">\n            <p>\n                "
    + alias3(((helper = (helper = helpers.Description || (depth0 != null ? depth0.Description : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Description","hash":{},"data":data}) : helper)))
    + "\n            </p>\n        </div>\n    </div>\n</div>";
},"useData":true});
})();