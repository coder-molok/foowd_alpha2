<?php
		
		$title = "Foowd";

		// start building the main column of the page

		$content = "Prova"

		$body = elgg_view_layout('one_column', array(
   			'content' => $content
		));
		
		echo elgg_view_page('cazz',$body);
?>