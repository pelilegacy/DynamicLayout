<!DOCTYPE html>
<html>
<head>
	<title>Layout</title>
	<meta charset="utf-8">
    <script src="./jquery.jss"></script>
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

    var channel = "pelilegacy_fi";

    function repeat(){
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

    function getUrlVars() {
        var vars = {};
        var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {vars[key] = value;});
        return vars;
    }

    var scale = getUrlVars().s;
    var aspectratio = getUrlVars().a;
    var player = getUrlVars().p;

    if(scale != "") document.write("<style>html {zoom: " + scale + "; -moz-transform: scale(" + scale + "); -webkit-transform: scale(" + scale + "); transform: scale(" + scale + ");}</style>");
    if(aspectratio == "43") document.write("<style>body {background-image: url('pelilegacy-layout-2015-kesa-43.png');}</style>");
    if(player == "joni") document.getElementById('name').innerHTML = "Joni Nieminen";
    else if(player == "jonni") document.getElementById('name').innerHTML = "Jonni Estola";
    else if(player == "niko") document.getElementById('name').innerHTML = "Niko Heikkilä";
    else if(player == "erno") document.getElementById('name').innerHTML = "Erno Koivistoinen";

    repeat();
    var myVar=setInterval(function(){repeat()},10000);

</script>
</body>
</html>