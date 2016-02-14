<!DOCTYPE html>
<html>
<head>
	<title>Layout</title>
	<meta charset="utf-8">
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
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
	<script src="layout.js"></script>
</body>
</html>
