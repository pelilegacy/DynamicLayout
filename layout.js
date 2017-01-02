/**
 *  @file layout.js - JavaScript code to handle layout setup dynamically
 *  @license MIT
 *  @requires jQuery 2.x
 *  @version 1.1
 */
jQuery(document).ready(function($) {

    /** Get URL variables */
    var params = getURLParams(document.location.search);

    /** Other necessary variables */
    var channel, clientID,
    jsonFile      = 'config.json',    // Name of the configuration file
    elementArtbox = $('#artbox'),     // jQuery object for game art
    elementGame   = $('#game');       // jQuery object for game name

    $.getJSON(jsonFile, function(json) {
        channel       = json.config.twitch_channel;         // Twitch channel name
        clientID      = json.config.twitch_client_id;       // Twitch.tv Client-ID

        /** Enabling the alert by creating an <iframe> element for it
         *  NOTE: This needs to be converted to serve StreamLabs API and is currently disabled
         *  See the new documentation: https://support.streamlabs.com/hc/en-us/sections/203901777-Guides
         *  elementAlertbox.append('<iframe id="alert" src="' + tAlert + '"></iframe>');
         */

        /**
         *  Executing function first since below it executes after timeout.
         *  This is here because otherwise it executes before channel name has been set.
         */
        repeat();

        /** This will update the layout with streamer's name */
        $.each(json.players, function(key, value) {

            var name = '';

            if (value.first.trim().toLowerCase() === params.player) {
                if (value.hasOwnProperty('twitter') && value.twitter.length > 0) {
                    name = "<span class='player-details'>" + value.first + " (<i class='fa fa-twitter'></i> @" + value.twitter + ")</span>";
                }
                else {
                    name = "<span class='player-details'>" + value.first + "</span>";
                }
                $("#name").html(name);
                return false;
            }
        });
        return false;
    })
    .fail(function() {
        /** For situations where configuration file cannot be found. */
        console.error(jsonFile + ' not found or it is unreachable. Create a readable JSON file from example.json. See README for details.');
        return false;
    });

    /**
     *  This function will be repeated to request the game name from Twitch API.
     *  @param none
     *  @return void
     */
    function repeat() {
        var baseURL = 'https://api.twitch.tv/kraken/channels/';

        $.ajax({
            'type': 'GET',
            'url': baseURL + channel,
            'headers': {
                'Client-ID': clientID
            }
        }).done(function(data) {
              if (data.game.length > 0) {
                  elementGame.html(data.game);
                    if (params.art === "1") {
                        getTwitchArt('733', '1080', data.game);
                    }
              }
              else {
                  elementGame.html('No game found.');
              }
        });
    }

    /**
     *  getTwitchArt - fetch game art from Twitch - Optimal aspect ratio for fullhd-sized image is 733x1080
     *  @param width :: String -> width as pixels of the cover art
	 *  @param height :: String -> height as pixels of the cover art
     *  @param game :: String -> the game that is currently being played
     *  @return void
     */

    function getTwitchArt(width, height, game) {

        var baseURL = "https://api.twitch.tv/kraken/search/",
            search  = encodeURI(game);

        /* Construct the query */
        var twitchAPICall = baseURL + 'games?type=suggest&q=' + search;

        $.ajax({
			'type': 'GET',
			'url': twitchAPICall,
			'headers': {
				'Client-ID': clientID
			}
		})
		.done(function(data) {

            var imgurl = "";

            if(data.games.length != 0) {
				for(i = 0; i < data.games.length; i++) {
					if(data.games[i].name == game)
					{
						imgurl = data.games[i].box.template.replace("{width}", width).replace("{height}", height);
					}
						
					elementArtbox.css({ "display": "inline" }).html('<img id="pic" src="' + imgurl + '" />');
				}
			}

        }).fail(function(err) {
            console.error("Error retrieving the game from Twitch: " + err);
        });
    }

    /**
     *  Function to read GET variables
     *  Note: This function is not the most optimum operation and will throw a warning when checking with JSHint.
     *  @param {string} qs - query string, usually same as document.location.search
     *  @return {object} GET parameters as object
     */

    function getURLParams(qs) {

        qs = qs.split('+').join(' ');
        var params = {},
            tokens,
            re = /[?&]?([^=]+)=([^&]*)/g;
        while (tokens = re.exec(qs)) { // jshint ignore:line
            params[decodeURIComponent(tokens[1])] = decodeURIComponent(tokens[2]);
        }

        return params;
    }

    /**
     *  Set scale-value from 0.0 to 1.0.
     *  This is here for previewing with browser.
     *  Does not work properly with Gecko and Trident.
     */
    if (params.scale !== "") {
        $("html").css( "transform", "scale(" + params.scale + ")");
    }

    /** This changes background-image for 4:3 aspect ratio games */
    if (params.aspect === "retro") {
        $("body").css("background-image", "url(img/retro.png)");
        $("#artbox").css({ 'left' : '1501px', 'width': '399px' });
    }

    /** This sets the function that will be repeated and how often */
    setInterval(function() {
        repeat();
    }, 10000);

});
