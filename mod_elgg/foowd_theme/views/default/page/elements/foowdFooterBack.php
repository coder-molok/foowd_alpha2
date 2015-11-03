<?php

?>

<div class="foowd-elements-footer">

<table class="foowd-footer">
<tr>
	<td><a href="<?php echo elgg_get_site_url();?>">Home</a></td>
	<td><a href="http://www.foowd.it/disclaimer.html">Cookie-Policy</a></td>
</tr>
</table>

</div>



<script type="text/javascript">
require([ 
    'NavbarController', 'Modernizr'
  ],function(){
    Modernizr = require('Modernizr');
    console.log(Modernizr)

    require('NavbarController').loadNavbar();

});
</script>
<style>
.foowd-navbar{
  position: relative;
  z-index:0;
}
</style>



<script type="text/javascript">


require([ 
	'jquery', 'foowd-main'
  ],function($, m){

    $('.foowd-alert-disabled').on('click', function(e){
      e.preventDefault();
      window.foowdAlert( 'Questa funzione sarà attivata a breve' , 'Inattiva')
    });

  	// var $ = require('jquery');
   //  $(document).ready( function(){

   //  	// azioni di default
   //  	// elimino dal menu 
   //  	// $('.elgg-menu-item-profile').remove();


   //  	// personalizzo la pagina settings/user
   //      // vedi C:\wamp\www\ElggProject\elgg-1.10.5\views\default\forms\user\changepassword.php 
   //  	var url = $('.elgg-menu-item-usersettings a').attr('href');
   //  	if(url == window.location.href ){
   //  		$('div').removeClass( "elgg-layout-one-sidebar" );
   //  		$('div.elgg-sidebar').remove();
   //  		$('.elgg-page-body').css({'max-width':'400px'});
   //  		$('.elgg-module-info > .elgg-head, .elgg-module-info > .elgg-head *')
   //  				.css({'background':'none'})
   //  				.addClass('foowd-back-soft');
   //  	}

    	
   //  });

});
</script>


