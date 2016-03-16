<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Audio Player</title>	
	<script src="build/jquery.js"></script>	
	<script src="build/mediaelement-and-player.min.js"></script>
	<link rel="stylesheet" href="build/mediaelementplayer.min.css" />
</head>
<body>
<span id="result"></span><br>
<span id="name"></span>
<video id='vid' width='600' height='600' controls autoplay="true"></video>
<audio id='aud' controls></audio>
<script>

function loadstreams() {
	if(navigator.webkitGetUserMedia) {
	    navigator.webkitGetUserMedia({video:true}, function(stream) {
    	    loadvideo(stream);
		}, function(err) {
			console.log(err);
			err.code == 1 && (alert("You can click the button again anytime to enable."))
		});
		navigator.webkitGetUserMedia({audio:true}, function(stream) {
    	    loadaudio(stream);
		}, function(err) {
			console.log(err);
			err.code == 1 && (alert("You can click the button again anytime to enable."))
		});
	} else if(navigator.mozGetUserMedia) {
		navigator.mozGetUserMedia({video:true}, function(stream) {
    	    loadvideo(stream);
		}, function(err) {
			console.log(err);
			err.code == 1 && (alert("You can click the button again anytime to enable."))
		});
		navigator.mozGetUserMedia({audio:true}, function(stream) {
    	    loadaudio(stream);
		}, function(err) {
			console.log(err);
			err.code == 1 && (alert("You can click the button again anytime to enable."))
		});
	} else {
		console.log("ERRRR");
	}
    
    function loadvideo(stream) {
        source = window.URL.createObjectURL(stream);
        $('#vid').attr('src',source);
        $('#vid')[0].play();
    }
    function loadaudio(stream) {
	    //source = window.URL.createObjectURL(stream);
	    //$('#aud').attr('src',source);
        //$('#aud')[0].play();
    }
}

$('document').ready(function() {
	if (hasGetUserMedia()) {
	  // Good to go!
	  console.log("GOOD GOOD");
	  loadstreams();
	} else {
	  alert('getUserMedia() is not supported in your browser');
	}
});

	function hasGetUserMedia() {
		return !!(navigator.getUserMedia || navigator.webkitGetUserMedia ||
	            navigator.mozGetUserMedia || navigator.msGetUserMedia);
	}
</script>
</body>
</html>