define(['handlebars.runtime'], function(Handlebars) {
  Handlebars = Handlebars["default"];  var template = Handlebars.template, templates = Handlebars.templates = Handlebars.templates || {};
templates['navbar'] = template({"1":function(depth0,helpers,partials,data) {
    return "          <input type=\"text\" id=\"searchText\" onkeypress=\"WallController.searchProducts(event)\">\n";
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
    var stack1;

  return "<div class=\"foowd-navbar\">\n    <div class=\"navbar-section\" id = \"logo\">\n        <div id=\"foowd-brand\">\n         <span onClick=\"utils.goTo('')\">foowd_</span>\n"
    + ((stack1 = helpers['if'].call(depth0,(depth0 != null ? depth0.search : depth0),{"name":"if","hash":{},"fn":this.program(1, data, 0),"inverse":this.noop,"data":data})) != null ? stack1 : "")
    + "        </div>\n    </div>\n    <div class = \"navbar-section\" id=\"user-menu-section\">\n        <div id = \"user-menu\">\n            <span onClick=\"utils.goTo('board')\" \n                  class=\"foowd-icons foowd-icon-heart-edge fw-menu-icon preferences-link\">\n            </span>\n            <span onClick = \"NavbarController.goToUserProfile()\"\n                  class=\"foowd-icons foowd-icon-user fw-menu-icon profile-link\">\n            </span>\n            <span id=\"menu\" \n                  class=\"foowd-icons foowd-icon-menu fw-menu-icon menu-link\">\n            </span>\n        </div>\n    </div>\n</div>";
},"useData":true});
templates['preferenceAccountDetails'] = template({"1":function(depth0,helpers,partials,data) {
    var stack1;

  return "    <ul class=\"number-block account-info-section\">\n        <li>"
    + this.escapeExpression(this.lambda(((stack1 = (depth0 != null ? depth0.user : depth0)) != null ? stack1.followers : stack1), depth0))
    + "</li>\n        <li><span class =\"number-description\">followers</span></li>\n    </ul>\n";
},"3":function(depth0,helpers,partials,data) {
    var stack1;

  return "    <ul class=\"number-block account-info-section\">\n        <li>"
    + this.escapeExpression(this.lambda(((stack1 = (depth0 != null ? depth0.user : depth0)) != null ? stack1.following : stack1), depth0))
    + "</li>\n        <li><span class =\"number-description\">following</span></li>\n    </ul>\n";
},"5":function(depth0,helpers,partials,data) {
    var stack1;

  return "    <ul class=\"number-block account-info-section\">\n        <li>"
    + this.escapeExpression(this.lambda(((stack1 = (depth0 != null ? depth0.user : depth0)) != null ? stack1.likes : stack1), depth0))
    + "</li>\n        <li><span class =\"number-description\">products</span></li>\n    </ul>\n";
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
    var stack1;

  return "<div id = \"user-details\">\n    <img src=\"../profile.png\" id = \"user-avatar\">\n    <div id=\"user-info\">\n        <div id=\"username\">\n            "
    + this.escapeExpression(this.lambda(((stack1 = (depth0 != null ? depth0.user : depth0)) != null ? stack1.name : stack1), depth0))
    + "\n        </div>\n        <div id = \"board\">\n            my board\n        </div>\n    </div>\n</div>\n<div id=\"account-info\">\n"
    + ((stack1 = helpers['if'].call(depth0,((stack1 = (depth0 != null ? depth0.user : depth0)) != null ? stack1.followers : stack1),{"name":"if","hash":{},"fn":this.program(1, data, 0),"inverse":this.noop,"data":data})) != null ? stack1 : "")
    + ((stack1 = helpers['if'].call(depth0,((stack1 = (depth0 != null ? depth0.user : depth0)) != null ? stack1.following : stack1),{"name":"if","hash":{},"fn":this.program(3, data, 0),"inverse":this.noop,"data":data})) != null ? stack1 : "")
    + ((stack1 = helpers['if'].call(depth0,((stack1 = (depth0 != null ? depth0.user : depth0)) != null ? stack1.likes : stack1),{"name":"if","hash":{},"fn":this.program(5, data, 0),"inverse":this.noop,"data":data})) != null ? stack1 : "")
    + "</div>";
},"useData":true});
templates['producerProfile'] = template({"1":function(depth0,helpers,partials,data) {
    var helper;

  return "	<ul class=\"number-block\">\n        <li>"
    + this.escapeExpression(((helper = (helper = helpers.followers || (depth0 != null ? depth0.followers : depth0)) != null ? helper : helpers.helperMissing),(typeof helper === "function" ? helper.call(depth0,{"name":"followers","hash":{},"data":data}) : helper)))
    + "5</li>\n        <li><span class =\"number-description\">followers</span></li>\n    </ul>\n";
},"3":function(depth0,helpers,partials,data) {
    var helper;

  return "    <ul class=\"number-block\">\n        <li>"
    + this.escapeExpression(((helper = (helper = helpers.views || (depth0 != null ? depth0.views : depth0)) != null ? helper : helpers.helperMissing),(typeof helper === "function" ? helper.call(depth0,{"name":"views","hash":{},"data":data}) : helper)))
    + "240</li>\n        <li><span class =\"number-description\">visualizations</span></li>\n    </ul>\n";
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
    var stack1, helper, alias1=helpers.helperMissing, alias2="function", alias3=this.escapeExpression;

  return "<div id = \"producer-header\">\n	<div id = \"producer-name\">\n		"
    + alias3(((helper = (helper = helpers.Name || (depth0 != null ? depth0.Name : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Name","hash":{},"data":data}) : helper)))
    + "\n		<span>"
    + alias3(((helper = (helper = helpers.Site || (depth0 != null ? depth0.Site : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Site","hash":{},"data":data}) : helper)))
    + "</span>\n	</div>\n</div>\n<div id = \"producer-info\">\n"
    + ((stack1 = helpers['if'].call(depth0,(depth0 != null ? depth0.followers : depth0),{"name":"if","hash":{},"fn":this.program(1, data, 0),"inverse":this.noop,"data":data})) != null ? stack1 : "")
    + ((stack1 = helpers['if'].call(depth0,(depth0 != null ? depth0.views : depth0),{"name":"if","hash":{},"fn":this.program(3, data, 0),"inverse":this.noop,"data":data})) != null ? stack1 : "")
    + "</div>\n<div id = \"producer-description\">\n	<div class = \"about\">\n		About us\n	</div>\n	<div class = \"about-description\">\n		"
    + alias3(((helper = (helper = helpers.Description || (depth0 != null ? depth0.Description : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Description","hash":{},"data":data}) : helper)))
    + "\n	</div>\n	<div class = \"about-description\">\n	Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum non pulvinar risus. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Maecenas ullamcorper rutrum lorem, ac consectetur tortor rhoncus eu. Vestibulum lobortis felis in ultricies dignissim. Vestibulum a neque quis dolor venenatis ultricies sed nec nisi. Vestibulum suscipit elementum efficitur. Cras in odio bibendum, tempor libero eget, elementum elit. Mauris interdum nisi vitae sem gravida condimentum. Nullam quis nunc id arcu euismod imperdiet. Praesent accumsan tellus eget ullamcorper eleifend. Etiam sed iaculis nisi. Aliquam feugiat nibh orci, in scelerisque magna gravida at. Aenean placerat, erat non fermentum vulputate, sem nunc efficitur erat, et convallis lacus metus sed arcu. Morbi nunc nisi, fringilla molestie luctus et, fermentum efficitur ex. Vestibulum convallis, ex quis dictum fringilla, tortor sapien eleifend erat, sit amet facilisis metus ipsum id mauris. Lorem ipsum dolor sit amet, consectetur adipiscing elit.\n	</div>\n	<div>&nbsp;</div>\n</div>";
},"useData":true});
templates['productDetail'] = template({"1":function(depth0,helpers,partials,data) {
    var helper, alias1=helpers.helperMissing, alias2="function", alias3=this.escapeExpression;

  return "        <span class = \"detail-progress-bar\">\n            <span class = \"progress\" \n              data-unit = \"1\"\n              data-progress = \""
    + alias3(((helper = (helper = helpers.totalQt || (depth0 != null ? depth0.totalQt : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"totalQt","hash":{},"data":data}) : helper)))
    + "\" \n              data-total = \""
    + alias3(((helper = (helper = helpers.Minqt || (depth0 != null ? depth0.Minqt : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Minqt","hash":{},"data":data}) : helper)))
    + "\">\n            </span>    \n        </span>\n        <span class = \"detail-progress-bar preview-bar\">\n            <span class = \"progress\" \n              data-unit = \"1\"\n              data-progress = \""
    + alias3(((helper = (helper = helpers.totalQt || (depth0 != null ? depth0.totalQt : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"totalQt","hash":{},"data":data}) : helper)))
    + "\" \n              data-total = \""
    + alias3(((helper = (helper = helpers.Minqt || (depth0 != null ? depth0.Minqt : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Minqt","hash":{},"data":data}) : helper)))
    + "\">\n            </span>    \n        </span>\n";
},"3":function(depth0,helpers,partials,data) {
    var helper, alias1=helpers.helperMissing, alias2="function", alias3=this.escapeExpression;

  return "            <ul class=\"action-icons menu-section\" id = \"preference-action\">\n                <li class=\"action-heart\">\n                    <i  class=\"foowd-icons foowd-icon-heart-edge\" \n                        onClick = \"ProductDetailController.addPreference("
    + alias3(((helper = (helper = helpers.Id || (depth0 != null ? depth0.Id : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Id","hash":{},"data":data}) : helper)))
    + ", 1)\"></i>\n                </li>\n                <li class=\"action-minus\">\n                    <i class=\"foowd-icons foowd-icon-minus fw-menu-icon\" \n                       onClick = \"ProductDetailController.addPreference("
    + alias3(((helper = (helper = helpers.Id || (depth0 != null ? depth0.Id : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Id","hash":{},"data":data}) : helper)))
    + ", -1)\"></i>\n                </li>\n            </ul>\n";
},"5":function(depth0,helpers,partials,data) {
    var helper, alias1=helpers.helperMissing, alias2=this.escapeExpression;

  return "                <div class = \"commercial-menu-item\">\n                    <div class=\"item-container\">\n                        <span class=\"foowd-icons foowd-icon-heart-edge item-icon\">\n                        </span>\n                        <span class = \"item-title\">carrello</span>\n                        <span class = \"item-data\">x"
    + alias2(((helper = (helper = helpers.totalQt || (depth0 != null ? depth0.totalQt : depth0)) != null ? helper : alias1),(typeof helper === "function" ? helper.call(depth0,{"name":"totalQt","hash":{},"data":data}) : helper)))
    + "</span>\n                    </div>\n                </div>\n                <div class = \"commercial-menu-item\">\n                    <div class=\"item-container\">\n                        <span class=\"foowd-icons foowd-icon-cart item-icon\"></span>\n                        <span class = \"item-title\">tot</span>\n                        <span class = \"item-data\">\n                            "
    + alias2((helpers.math || (depth0 && depth0.math) || alias1).call(depth0,(depth0 != null ? depth0.totalQt : depth0),"*",(depth0 != null ? depth0.Price : depth0),{"name":"math","hash":{},"data":data}))
    + "\n                            <span class = \"apex\">€</span>\n                        </span>\n                    </div>\n                </div>\n";
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
    var stack1, helper, alias1=helpers.helperMissing, alias2="function", alias3=this.escapeExpression;

  return "<div id=\"detail-menu\">\n    <div class=\"detail-menu-section\" id=\"price-section\">\n"
    + ((stack1 = helpers['if'].call(depth0,(depth0 != null ? depth0.logged : depth0),{"name":"if","hash":{},"fn":this.program(1, data, 0),"inverse":this.noop,"data":data})) != null ? stack1 : "")
    + "        <div class=\"price-detail\">\n            <ul class=\"number-block\" id=\"unit-price\">\n                <li>"
    + alias3(((helper = (helper = helpers.Price || (depth0 != null ? depth0.Price : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Price","hash":{},"data":data}) : helper)))
    + "<span class=\"apex\">€</span></li>\n                <li><span class =\"number-description\">prezzo unità</span></li>\n            </ul>\n        </div>\n        <div class=\"price-detail\">\n            <ul class=\"number-block\" id=\"min-order-price\">\n                <li>"
    + alias3((helpers.math || (depth0 && depth0.math) || alias1).call(depth0,(depth0 != null ? depth0.Price : depth0),"*",(depth0 != null ? depth0.Minqt : depth0),{"name":"math","hash":{},"data":data}))
    + "<span class=\"apex\">€</span></li>\n                <li><span class =\"number-description\">ordine minimo</span></li>\n            </ul>\n        </div>\n    </div>\n    <div class=\"detail-menu-section\" id =\"menu-close\">\n        <span class=\"foowd-icons foowd-icon-close\" id =\"close-detail\" onClick=\"utils.goTo('')\"></span>\n    </div>\n</div>\n<div id=\"product-menu\">\n    <div class=\"product-menu-section\" id=\"product-info\">\n        <div class=\"product-info-menu\" id = \"section1\">\n\n            <div class=\"menu-section\" id=\"product-name\">\n                "
    + alias3(((helper = (helper = helpers.Name || (depth0 != null ? depth0.Name : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Name","hash":{},"data":data}) : helper)))
    + "\n            </div>\n            \n"
    + ((stack1 = helpers['if'].call(depth0,(depth0 != null ? depth0.logged : depth0),{"name":"if","hash":{},"fn":this.program(3, data, 0),"inverse":this.noop,"data":data})) != null ? stack1 : "")
    + "\n        </div>\n        <div class=\"product-info-menu\" id = \"section2\">\n            <div id=\"product-description\">\n                "
    + ((stack1 = ((helper = (helper = helpers.Description || (depth0 != null ? depth0.Description : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Description","hash":{},"data":data}) : helper))) != null ? stack1 : "")
    + "\n            </div>\n            <div id=\"commercial-menu\">\n"
    + ((stack1 = helpers['if'].call(depth0,(depth0 != null ? depth0.logged : depth0),{"name":"if","hash":{},"fn":this.program(5, data, 0),"inverse":this.noop,"data":data})) != null ? stack1 : "")
    + "            </div>\n        </div>\n    </div>\n    <div class = \"product-menu-section\" id=\"product-picture\">\n       <img src=\""
    + alias3(((helper = (helper = helpers.picture || (depth0 != null ? depth0.picture : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"picture","hash":{},"data":data}) : helper)))
    + "\"/>\n    </div>\n</div>\n";
},"useData":true});
templates['productPost'] = template({"1":function(depth0,helpers,partials,data) {
    var stack1, helper, alias1=this.escapeExpression, alias2=helpers.helperMissing;

  return "            <span class=\"mini-progress-bar\">\n                <span class=\"mini-progress\" data-unit=\"1\" data-progress=\""
    + alias1(this.lambda(((stack1 = (depth0 != null ? depth0.prefer : depth0)) != null ? stack1.Qt : stack1), depth0))
    + "\" data-total=\""
    + alias1(((helper = (helper = helpers.Minqt || (depth0 != null ? depth0.Minqt : depth0)) != null ? helper : alias2),(typeof helper === "function" ? helper.call(depth0,{"name":"Minqt","hash":{},"data":data}) : helper)))
    + "\"></span>\n            </span>  \n            <div class=\"product-post-progress-price\">\n            "
    + alias1((helpers.math || (depth0 && depth0.math) || alias2).call(depth0,(depth0 != null ? depth0.totalQt : depth0),"*",(depth0 != null ? depth0.Price : depth0),{"name":"math","hash":{},"data":data}))
    + "\n                <span class=\"apex\">€</span>\n            </div>\n";
},"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
    var stack1, helper, alias1=helpers.helperMissing, alias2="function", alias3=this.escapeExpression;

  return "<li class=\"product-post\">\n    <div class=\"product-post-image-thumbnail\">\n        <figure class=\"tint\">\n            <img src=\""
    + alias3(((helper = (helper = helpers.picture || (depth0 != null ? depth0.picture : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"picture","hash":{},"data":data}) : helper)))
    + "\">\n        </figure>\n        <span class=\"heart-overlay foowd-icons foowd-icon-heart-full\"\n              onclick=\"window.addPreference("
    + alias3(((helper = (helper = helpers.Id || (depth0 != null ? depth0.Id : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Id","hash":{},"data":data}) : helper)))
    + ",1)\">\n        </span>\n        <div class=\"product-post-menu\">\n            <div onclick=\"utils.go2('detail', 'productId', "
    + alias3(((helper = (helper = helpers.Id || (depth0 != null ? depth0.Id : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Id","hash":{},"data":data}) : helper)))
    + ")\">\n               <span class = \"foowd-icons foowd-icon-len\">\n               </span>\n            </div>\n            <div onclick=\"utils.go2('producer', 'producerId', "
    + alias3(((helper = (helper = helpers.Publisher || (depth0 != null ? depth0.Publisher : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Publisher","hash":{},"data":data}) : helper)))
    + ")\">\n               <span class = \"foowd-icons foowd-icon-blade\">\n               </span>\n            </div>\n        </div>\n    </div>\n    <div class=\"product-post-header\">\n        <div class=\"product-post-name\">\n            "
    + alias3(((helper = (helper = helpers.Name || (depth0 != null ? depth0.Name : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Name","hash":{},"data":data}) : helper)))
    + "\n            <br/>\n            <br/>\n            <span class=\"product-post-unit-price\">\n                "
    + alias3(((helper = (helper = helpers.Quota || (depth0 != null ? depth0.Quota : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Quota","hash":{},"data":data}) : helper)))
    + " "
    + alias3(((helper = (helper = helpers.Unit || (depth0 != null ? depth0.Unit : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Unit","hash":{},"data":data}) : helper)))
    + " "
    + alias3(((helper = (helper = helpers.UnitExtra || (depth0 != null ? depth0.UnitExtra : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"UnitExtra","hash":{},"data":data}) : helper)))
    + "\n                <br/>\n                "
    + alias3(((helper = (helper = helpers.Price || (depth0 != null ? depth0.Price : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Price","hash":{},"data":data}) : helper)))
    + "€\n            </span>\n        </div>\n"
    + ((stack1 = helpers['if'].call(depth0,(depth0 != null ? depth0.logged : depth0),{"name":"if","hash":{},"fn":this.program(1, data, 0),"inverse":this.noop,"data":data})) != null ? stack1 : "")
    + "    </div>\n    <div class=\"product-post-body\">\n            "
    + ((stack1 = ((helper = (helper = helpers.Description || (depth0 != null ? depth0.Description : depth0)) != null ? helper : alias1),(typeof helper === alias2 ? helper.call(depth0,{"name":"Description","hash":{},"data":data}) : helper))) != null ? stack1 : "")
    + "\n    </div>\n</li>";
},"useData":true});
templates['userPreference'] = template({"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
    var stack1, helper, alias1=this.lambda, alias2=this.escapeExpression, alias3=helpers.helperMissing;

  return "<div class=\"preference\">\n   <span class = \"preference-progress-bar\">\n        <span class = \"progress\" \n          data-unit = \"1\"\n          data-progress = \""
    + alias2(alias1(((stack1 = (depth0 != null ? depth0.Offer : depth0)) != null ? stack1.totalQt : stack1), depth0))
    + "\" \n          data-total = \""
    + alias2(alias1(((stack1 = (depth0 != null ? depth0.Offer : depth0)) != null ? stack1.Minqt : stack1), depth0))
    + "\">\n        </span>    \n    </span>\n    <div class=\"user-preference\">\n        <div class=\"user-preference-section\">\n            <img src=\""
    + alias2(alias1(((stack1 = (depth0 != null ? depth0.Offer : depth0)) != null ? stack1.picture : stack1), depth0))
    + "\" class = \"user-preference-image\" \n                 onclick=\"utils.go2('detail', 'productId',"
    + alias2(alias1(((stack1 = (depth0 != null ? depth0.Offer : depth0)) != null ? stack1.Id : stack1), depth0))
    + ")\">    \n        </div>\n        <div class=\"user-preference-name user-preference-section\">\n            <ul class=\"number-block\">\n                <li>"
    + alias2(alias1(((stack1 = (depth0 != null ? depth0.Offer : depth0)) != null ? stack1.Name : stack1), depth0))
    + "</li>\n            </ul>\n        </div>\n        <div class=\"user-preference-details user-preference-section\">\n            <ul class=\"number-block preference-detail\">\n                <li>"
    + alias2(alias1(((stack1 = (depth0 != null ? depth0.Offer : depth0)) != null ? stack1.Price : stack1), depth0))
    + "€</li>\n                <li><span class =\"number-description\">Prezzo unità</span></li>\n            </ul>\n            <ul class=\"number-block preference-detail\">\n                <li>x"
    + alias2(((helper = (helper = helpers.Qt || (depth0 != null ? depth0.Qt : depth0)) != null ? helper : alias3),(typeof helper === "function" ? helper.call(depth0,{"name":"Qt","hash":{},"data":data}) : helper)))
    + "</li>\n                <li><span class =\"number-description\">carrello</span></li>\n            </ul>\n            <ul class=\"number-block preference-detail\">\n                <li>"
    + alias2((helpers.math || (depth0 && depth0.math) || alias3).call(depth0,(depth0 != null ? depth0.Qt : depth0),"*",((stack1 = (depth0 != null ? depth0.Offer : depth0)) != null ? stack1.Price : stack1),{"name":"math","hash":{},"data":data}))
    + "€</li>\n                <li><span class =\"number-description\">tot.spesa</span></li>\n            </ul>\n        </div>\n        <div class=\"user-preference-actions user-preference-section\">\n            <ul class=\"action-icons menu-section\" id = \"preference-action\">\n                <li class=\"action-heart\">\n                    <i  class=\"foowd-icons foowd-icon-heart-edge\" \n                        onClick = \"UserBoardController.addPreference("
    + alias2(alias1(((stack1 = (depth0 != null ? depth0.Offer : depth0)) != null ? stack1.Id : stack1), depth0))
    + ", 1)\"></i>\n                </li>\n                <li class=\"action-minus\">\n                    <i class=\"foowd-icons foowd-icon-minus fw-menu-icon\" \n                       onClick = \"UserBoardController.addPreference("
    + alias2(alias1(((stack1 = (depth0 != null ? depth0.Offer : depth0)) != null ? stack1.Id : stack1), depth0))
    + ", -1)\"></i>\n                </li>\n            </ul>\n        </div>\n    </div>\n</div>";
},"useData":true});
return templates;
});