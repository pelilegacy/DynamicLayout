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

        #alert {
	        width: 439px;
            height: 701px;
	        top: 66px;
	        left: 1481px;
            border: 0px;
            position: absolute;
	    }

        #artbox {
	        width: 194px;
            height: 662px;
	        top: 85px;
	        left: 1706px;
            border: 0px solid green;
            position: absolute;
	    }

        #pic {
            position: relative;
            top: 50%;
            transform: translateY(-50%);
            width: 99%;
            border: 2px solid;
            border-radius: 25px;
        }
    </style>
</head>
<body>
	<div id="game"></div>
	<div id="name"></div>
    <div id="alertbox"></div>
    <div id="artbox"></div>
	<script>

	    // Getting URL variables
		var params = getUrlVars(document.location.search);

	    // Getting player name from URL variable 'p'
		/** TODO: Store JSON data file in a HTTP(S) location, so you can debug with Chrome Dev Tools locally?
			Example: http://example.com/layout/data/data.json
			See: https://stackoverflow.com/questions/8449716/cross-origin-requests-are-only-supported-for-http-but-its-not-cross-domain
		 */

        var channel, tAlert;

	    $.getJSON("example.json", function(json) {

            // Set Twitch channel name here
	        channel = json.config.twitchchannel;
            apikey = json.config.giantbombapikey;
            tAlert = json.config.twitchalerts;

			elementAlertbox = document.getElementById('alertbox');
			elementArtbox = document.getElementById('artbox');
			elementGame = document.getElementById('game');
			
            // Enabling alert
            elementAlertbox.innerHTML = '<iframe id="alert" src="' + tAlert + '"></iframe>';

            // Executing function first since below it executes after timeout. This is here because otherwise it executes before channel name has been set
	        repeat();

			$.each(json.players, function(key, value) {

				var name = "";

				if (value.first.trim().toLowerCase() === params.player) {
					name = value.first + " " + value.last;
					$("#name").text(name);
					return false;
				}
	        });
			return false;
	    });

	    // This function will be repeated to request game name from Twitch API
        function repeat() {
            $.getJSON("https://api.twitch.tv/kraken/channels/" + channel + "?callback=?", function(json) {
                if(json.game != "") {
                        if(json.game != elementGame.innerHTML) {
                            elementGame.innerHTML = json.game;
                            getGiantbombApiImage(apikey, json.game);
                        }
                    }
	                else elementGame.innerHTML = "Pelin nimeä ei voi noutaa";
            });
        }

        // Requesting game data from Giantbomb api with Ajax
        function getGiantbombApiImage(apikey, game) {
            $.getJSON('http://www.giantbomb.com/api/games/?api_key=' + apikey + '&format=jsonp' + '&field_list=image&filter=name:' + encodeURI(game) + '&json_callback=?', function(json) {
                elementArtbox.innerHTML = '<img id="pic" src="' + json.results[0].image.super_url + '" />';
            });
        }

	    // Function to read URL variables (?chicken=big etc.)
	    function getUrlVars(qs) {

			qs = qs.split('+').join(' ');
			var params = {},
				tokens,
				re 	   = /[?&]?([^=]+)=([^&]*)/g;

			while (tokens = re.exec(qs)) {
				params[decodeURIComponent(tokens[1])] = decodeURIComponent(tokens[2]);
			}

			return params;
	    }

	    // Set scale-value from 0.0 to 1.0. This is here for previewing with browser (Does not work properly with Gecko and Trident)
	    if (params.scale != "") {
            $("html").css( "transform", "scale(" + params.scale + ")");
        }

	    // This changes background-image for 4:3 aspect ratio games
	    if (params.aspect === "retro") {
            $("body").css("background-image", "url(\'pelilegacy-layout-2015-kesa-43.png\')");
			
			var styles = {left : "1501px", width: "399px"};
			
            $("#artbox").css(styles);
        }

	    // This sets the function that will be repeated and how often
	    var timer = setInterval(function(){
			repeat();
		}, 10000);

	</script>
</body>
</html>
