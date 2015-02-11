<?php


$response = json_decode(file_get_contents('http://localhost/api_offerte/public_html/offers'));

var_dump( $response->Name);

