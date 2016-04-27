<?php
ob_start();

$url = elgg_get_site_url() . \Uoowd\Param::page()->foowdStorage;
$file = $url . 'Public/Files/Condizioni_di_uso-foowd.it.pdf';

// echo $file;

?>

<!-- <iframe src="http://docs.google.com/gview?url=<?php echo $file; ?>&embedded=true" 
style="width:600px; height:500px;" frameborder="0"></iframe> -->



<center>
<embed src="<?php echo $file; ?>" width="100%" height="500" alt="Condizioni di Utilizzo PDF" />
</center>
<!-- width="600" height="500"  pluginspage="http://www.adobe.com/products/acrobat/readstep2.html" -->



<?php

$body = ob_get_contents();

ob_end_clean();

$body = '<div class="foowd-page-legal">'.$body.'</div>';

echo elgg_view_page('Condizioni',$body);