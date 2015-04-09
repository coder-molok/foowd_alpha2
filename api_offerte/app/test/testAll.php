<?php

$dirType = "./type/";
$dirJson = "postman/";

$var = $_SERVER['PHP_SELF'];
$base = implode(array_slice( explode('/',$var) , 0, -3) , "/");
define('URL', $base.'/public_html/api/' );

function method($str){
	echo '<div class="single_method">'.$str.'</div>';
}

function what($str){
	echo '<div class="single_what">'.$str.'</div>';
}

function setField($obj, $param, $val = 'unset'){
	//var_dump($obj->url);
	$obj->url = preg_replace('@ @','', $obj->url);
	if($obj->method == 'GET'){
		$url = explode('?',$obj->url);
		//var_dump($url);
		$par = explode('&', $url[1]);
		$exist = false;
		foreach ($par as $key => $value) {
			$ex = explode('=', $value);

			if($ex[0] === $param){
				$exist = true;
				if($val == 'unset'){
					unset($par[$key]);
				}else{
					$ex[1] = $val;
					$par[$key] = implode($ex, '=');
				} 
			}
		}

		if(!$exist && ($val !== 'unset')){
			//echo "rimuovo $param";
			$par[count($par)] = "$param = $val";
		}
	
		$url[1] = implode($par, '&');
		$obj->url= implode($url, '?');
		//var_dump($obj->url);
	}

	if($obj->method == 'POST'){
		$obj->dataSet[$param] = $val;
		if( $val == 'unset') unset($obj->dataSet[$param]);
	}
}

?>


<html>
<head>
	<style>
		body{background-color: #98474D;}
		form{background-color: tomato; padding: 10px;}
		div{ margin: 5px; padding: 10px;}
		#result{background-color: #CC7CB8;}
		.single{background-color: #819545;}
		[class^="single_"]{font-size: 1.1em; font-weight: bold;}
		.single_method{background-color: #CC5FB1; }
		.single-sent{background-color:#C5E369;}
		.single-return-true{background-color: #73D663;}
		.single-return-false{background-color: #E56A73;}
		[class^="single-"]{margin-left: 30px; }
		[class^="single-return"]{margin-bottom: 23px; }
	</style>
        
</head>

<body>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
<legend>Seleziona quali test svolgere:</legend>
<p class="check"><input type="checkbox" name="test[]" value="User" />User</p>
<p class="check"><input type="checkbox" name="test[]" value="Offer" />Offer</p>
<p class="check"><input type="checkbox" name="test[]" value="Prefer" />Prefer</p>
<p><input type="submit"></p>
</form>

<?php

if(isset($_GET['test'])){

	include('API.php');
	//var_dump(URL);

	echo "<div id=\"result\">Elenco dei test...</div>";
	foreach ($_GET['test'] as $value) {
		$request = json_decode(file_get_contents($dirJson.$value.'.json'));
		echo "<div class=\"single\"><span class=\"single_test\">$request->name:</span>";
		$request = $request->requests;

		$url = URL.strtolower($value);
		foreach ($request as $obj) {
			if($obj->method == 'GET'){
				$str = $obj->url;
				$type = preg_replace('@.*type=(\w+)&.*@', "$1", $str);
				$ar[$type] = $obj;
			}elseif($obj->method == 'POST'){
				$obj->dataSet = (array) json_decode($obj->rawModeData);
				$ar[$obj->dataSet['type']] = $obj;
			}
			//var_dump($obj);
		}
		//var_dump($ar);

		foreach ($ar as $key => $v) {
			echo "$key - ";
		}

		include_once($dirType.'Test'.$value.'.php');
		echo "</div>"; // end single

	}
}

?>

</body>
</html>


