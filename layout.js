/**
 *  @file layout.js - JavaScript code to handle layout setup dynamically
 *  @author Joni Nieminen (Arkkis)
 *  @author Niko Heikkilä (nikoheikkila)
 *  @license GPL
 *  @requires jQuery 2.x
 *  @version 1.0
 */
jQuery(document).ready(function($) {

    /** Get URL variables */
    var params = getUrlVars(document.location.search);

    /** Other necessary variables */
    var apikey, channel, clientID,
    jsonFile        = 'config.json',    // Name of the configuration file
    /* elementAlertbox = $('#alertbox'),   jQuery object for Twitch alert */
    elementArtbox   = $('#artbox'),     // jQuery object for game art
    elementGame     = $('#game');       // jQuery object for game name

    $.getJSON(jsonFile, function(json) {

        channel     = json.config.twitch_channel;       // Twitch channel name
        apikey      = json.config.giantbomb_apikey;     // GiantBomb API key
        /* tAlert      = json.config.twitch_alert;          TwitchAlert API URL */
        clientID    = json.config.twitch_client_id;  // Twitch.tv Client-ID

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
                name = value.first + " " + value.last;
                $("#name").text(name);
                return false;
            }
        });
        return false;
    })
    .fail(function() {
        /** For situations where streamer cannot be found. */
        console.error(jsonFile + ' not found or it is unreachable. Create a readable JSON file from example.json.');
        return false;
    });

    /**
     *  This function will be repeated to request the game name from Twitch API.
     *  @param none
     *  @return void
     */
    function repeat() {
        var baseURL = 'https://api.twitch.tv/kraken/channels/';
        // $.getJSON(baseURL + channel + "?callback=?", function(json) {
        //
        //     if (json.game !== '') {
        //         if (json.game !== elementGame.html()) {
        //             elementGame.html(json.game);
        //             getGameArt(apikey, json.game);
        //         }
        //     }
        //     else {
        //         elementGame.html('No game found.');
        //     }
        // });

        $.ajax({
            'type': 'GET',
            'url': baseURL + channel,
            'headers': {
                'Client-ID': clientID
            }
        })
        .done(function(data) {
              if (data.game !== '') {
                  if (data.game !== elementGame.html()) {
                      elementGame.html(data.game);
                      getGameArt(apikey, data.game);
                  }
              }
              else {
                  elementGame.html('No game found.');
              }
        });
    }

    /**
     *  Requesting game data from Giantbomb API
     *  @param {string} apikey - API Key
     *  @param {string} game - name of the game to lookup art
     *  @return void
     */
    function getGameArt(apikey, game) {
        // TODO: Request sometimes return incorrect game if there are more games that starts with the same name

        var baseURL = 'https://www.giantbomb.com/api/games/?api_key=';

        $.getJSON(baseURL + apikey + '&format=jsonp' + '&field_list=image&filter=name:' + encodeURI(game) + '&json_callback=?', function(json) {

            var url = '';

            if (typeof json.results[0] !== 'undefined') {
                url = json.results[0].image.super_url;
                elementArtbox.css({ 'display': 'inline' });
            }
            else {
                elementArtbox.css({ 'display': 'none' });
            }

            $.get(url).success(function() {
                elementArtbox.html('<img id="pic" src="' + url + '" />');
            }).fail(function() {
                elementArtbox.css({ 'display': 'none' });
            });
        });
    }

    /**
     *  Function to read GET variables
     *  Note: This function is not the most optimum operation and will throw a warning when checking with JSHint.
     *  @param {string} qs - query string, usually same as document.location.search
     *  @return {object} GET parameters as object
     */

    function getUrlVars(qs) {

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
