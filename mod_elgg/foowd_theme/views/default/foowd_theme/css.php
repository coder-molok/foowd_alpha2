.elgg-page-header {
	background: none;
	background-color: #ffffff;
    border-width: 0;
    margin-top: 10px;
    border: 0px;
}

<?php
if(elgg_is_admin_logged_in()){
	echo ".navbar-fixed-top{
			top:22px !important;
		  }";		
}
?>