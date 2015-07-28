define(function(require){

	var utils = require('Utils');
	var templates = require('templates');

	var NavbarController = (function(){

		var navbarContainer = ".foowd-navbar";
		var userId = null;

		function _init(search){

			userId = utils.getUserId();

            search = utils.isValid(search) ? search : false;
            
            var context = {
                "search" : search,
            };

            $(navbarContainer).html(templates.navbar(context));
        }

        function goToUserProfile(){
        	if(utils.isValid(userId)){
        		utils.goTo("profile");
        	}else{
        		utils.goTo("login");
        	}
        }

		return{
			loadNavbar : 	 _init,
			goToUserProfile: goToUserProfile,
		};
	})();
	window.NavbarController = NavbarController;
	return NavbarController;
});