<!DOCTYPE html>
<html>
<head>
	<title>Layout</title>
	<meta charset="utf-8">
    <script src="./jquery.js"></script>
    <style>

    @font-face {
        font-family: ChunkFive;
        src: url("ChunkFive.otf");
    }

    body {
        background-image: url("pelilegacy-layout-2015-kesa-169.png");
        background-repeat: no-repeat;
    }

    #game, #name {
        display: block;
        font-family: ChunkFive;
        text-align: center;
        color: white;
        font-size: 36px;
        position: absolute;
    }

    #name {
        width: 440px;
        bottom: 0px;
        right: 0px;
    }

    #game {
        width: 840px;
        top: 14px;
        left: 400px;
    }

    </style>
</head>
<body>
<div id="game"></div>
<div id="name"></div>
<script>

    /* Set Twitch channel name here */
    var channel = "pelilegacy_fi";

    /* Getting URL variables */
    var scale = getUrlVars().scale;
    var aspectratio = getUrlVars().aspect;
    var player = getUrlVars().player;

    /* Getting player name from URL variable 'p' */
    $.getJSON("data.json", function(json) {
        $.each(json.players, function(i, v) {
            if (v.first.toLowerCase() == player) {
                document.getElementById('name').innerHTML = v.first + " " + v.last;
                return;
            }
        });
    });

    /* This function will be repeated to request game name from Twitch API */
    function repeat() {
        var xmlhttp = new XMLHttpRequest(),
        url = 'https://api.twitch.tv/kraken/channels/' + channel;
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var request = JSON.parse(this.responseText);
                if(request.game != "") document.getElementById('game').innerHTML = request.game;
                else document.getElementById('game').innerHTML = "Pelin nimeä ei voi noutaa";
            }
        }
        xmlhttp.open('GET', url, true);
        xmlhttp.send();
    }

    /* Function to read URL variables (?chicken=big etc.)  */
    function getUrlVars() {
        var vars = {};
        var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {vars[key] = value;});
        return vars;
    }

    /* Set scale-value from 0.0 to 1.0. This is here for previewin with browser */
    if(scale != "") document.write("<style>html {zoom: " + scale + "; -moz-transform: scale(" + scale + "); -webkit-transform: scale(" + scale + "); transform: scale(" + scale + ");}</style>");
    
    /* This changes background-image for 4:3 aspect ratio games */    
    if(aspectratio == "retro") document.write("<style>body {background-image: url('pelilegacy-layout-2015-kesa-43.png');}</style>");

    /* Executing function first since below it executes after timeout */
    repeat();

    /* This sets the function that will be repeated and how often */
    var myVar=setInterval(function(){repeat()},10000);

</script>
</body>
</html>