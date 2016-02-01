<!DOCTYPE html>
<html>
<head>
	<title>Layout</title>
	<meta charset="utf-8">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <style>

		/* Font for texts */
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
	        font-family: ChunkFive, sans-serif;
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
		var params = getUrlVars();

	    /* Getting player name from URL variable 'p' */
		/** TODO: Store JSON data file in a HTTP(S) location, so you can debug with Chrome Dev Tools locally?
			Example: http://example.com/layout/data/data.json
			See: https://stackoverflow.com/questions/8449716/cross-origin-requests-are-only-supported-for-http-but-its-not-cross-domain
		 */

	    $.getJSON("data.json", function(json) {
	        $.each(json.players, function(i, v) {

				if (v.first.trim().toLowerCase() === params.player) {
					var name = v.first + " " + v.last;
					$("#name").html(name);
	            }

				return false;
	        });
	    });

	    /* This function will be repeated to request game name from Twitch API */
		// TODO: Make repeat() work with jQuery calls and $.ajax requests as well!
	    function repeat() {
	        var xmlhttp = new XMLHttpRequest(),
	        url = "https://api.twitch.tv/kraken/channels/" + channel;
	        xmlhttp.onreadystatechange = function() {
	            if (this.readyState == 4 && this.status == 200) {
	                var request = JSON.parse(this.responseText);
	                if(request.game != "") document.getElementById('game').innerHTML = request.game;
	                else document.getElementById('game').innerHTML = "Pelin nimeä ei voi noutaa";
	            }
	        };

	        xmlhttp.open('GET', url, true);
	        xmlhttp.send();
	    }

	    /* Function to read URL variables (?chicken=big etc.)  */
	    function getUrlVars() {
	        var vars = {};
	        var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi,
				function(m, key, value) { vars[key] = value; });

			return vars;
	    }

	    /* Set scale-value from 0.0 to 1.0. This is here for previewing with browser */
		// TODO: Do not use document.write but jQuery CSS calls

	    if (params.scale !== "") document.write("<style>html {zoom: " + params.scale + "; -moz-transform: scale(" + params.scale + "); -webkit-transform: scale(" + params.scale + "); transform: scale(" + params.scale + ");}</style>");

	    /* This changes background-image for 4:3 aspect ratio games */
	    if (params.aspect === "retro") document.write("<style>body {background-image: url('pelilegacy-layout-2015-kesa-43.png');}</style>");

	    /* Executing function first since below it executes after timeout */
	    repeat();

	    /* This sets the function that will be repeated and how often */
	    var timer = setInterval(function(){
			repeat();
		}, 10000);

	</script>
</body>
</html>
