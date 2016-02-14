jQuery(document).ready(function($) {

    // Get URL variables
    var params = getUrlVars(document.location.search);

    // Necessary variables
    var apikey, channel, tAlert,
    jsonFile        = 'config.json',
    elementAlertbox = $('#alertbox'),
    elementArtbox   = $('#artbox'),
    elementGame     = $('#game');

    $.getJSON(jsonFile, function(json) {

        // Set Twitch channel name here
        channel = json.config.twitch_channel;
        apikey  = json.config.giantbomb_apikey;
        tAlert  = json.config.twitch_alert;

        // Enabling the alert with iframe
        elementAlertbox.append('<iframe id="alert" src="' + tAlert + '"></iframe>');

        /** Executing function first since below it executes after timeout.
            This is here because otherwise it executes before channel name has been set.
        **/
        repeat();

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
        console.error(jsonFile + ' not found or it is unreachable. Create a readable JSON file from example.json.');
        return false;
    });

    // This function will be repeated to request game name from Twitch API
    function repeat() {
        var baseURL = 'https://api.twitch.tv/kraken/channels/';
        $.getJSON(baseURL + channel + "?callback=?", function(json) {

            if (json.game !== '') {
                if (json.game !== elementGame.html()) {
                    elementGame.html(json.game);
                    getGiantbombApiImage(apikey, json.game);
                }
            }
            else {
                elementGame.html('No game found.');
            }
        });
    }

    // Requesting game data from Giantbomb API with Ajax
    function getGiantbombApiImage(apikey, game) {
        // TODO: Request sometimes return incorrect game if there are more games that starts with the same name

        var baseURL = 'http://www.giantbomb.com/api/games/?api_key=';

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
                elementArtbox.append('<img id="pic" src="' + url + '" />');
            }).fail(function() {
                elementArtbox.css({ 'display': 'none' });
            });
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
        $("#artbox").css({ 'left' : '1501px', 'width': '399px' });
    }

    // This sets the function that will be repeated and how often
    var timer = setInterval(function(){
        repeat();
    }, 10000);

});
