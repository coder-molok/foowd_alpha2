<style>
.spinner{
	background-color: red;
	width: 1em;
	display: inline-block;
}
</style>
<?php

// spinner in HTML5

// if (isset($vars['class'])) {
// 	$vars['class'] = "elgg-input-plaintext {$vars['class']}";
// } else {
// 	$vars['class'] = "elgg-input-plaintext";
// }

// input parte intera
// $input['class'] .= " spinner";

$number = preg_split("@(\.|,)@", strval($vars['value']));
// var_dump($vars);
$input['type'] = "number";
$input['min']=0;
$input['value']=$number[0];
$input['max']=pow(10, $vars['integer']) - 1;
$input['name'] = $vars['name'].'-integer';
$input['id'] = $input['name'];
$input['style']="width:".($vars['integer']+1)."em; display: inline-block;";

echo '<input ';
foreach($input as $opt => $val){
	echo " $opt=\"$val\" ";
}
echo '/>';

echo ' , ';


// input parte decimale
$input['value']=$number[1];
$input['max']=pow(10, $vars['decimal']) - 1;
$input['name'] = $vars['name'].'-decimal';
$input['id'] = $input['name'];
$input['style']="width:".($vars['decimal']+1)."em; display: inline-block;";
//$input['style']="padding-right:10px; display: inline-block;";

echo '<input ';
foreach($input as $opt => $val){
	echo " $opt=\"$val\" ";
}
echo '/>';
