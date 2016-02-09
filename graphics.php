<!DOCTYPE html>
<html>
<head>
	<title>Layout</title>
	<meta charset="utf-8">
    <style>
        body {
            padding: 0px;
            margin: 0px;
        }

        #wrapper {
            height: 100vh;
            line-height: 100vh;
        }

        img {
            width: 99%;
            vertical-align: middle;
            border: 2px solid;
            border-radius: 25px;
        }
    </style>
</head>
<body>
<?php

$game = $_GET['game'];
$apikey = $_GET['apikey'];

$source = file_get_contents("http://www.giantbomb.com/api/games/?api_key=" . $apikey . "&format=json&field_list=image&filter=name:" . $game); // Searching image from giantbomb api with game's name
$json = json_decode($source, true);

if($json['results']['0']['image']['super_url'] != '') {
    if(strpos(file_get_contents($json['results']['0']['image']['super_url']), 'xml') === false) { // if url's content has "xml" string, it is propably not image right?
        echo '<div id="wrapper"><img id="pic" src="' . $json['results']['0']['image']['super_url'] . '" /></div>';
    }
}

?>
</body>
</html>