<script type='text/javascript' src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
<script type='text/javascript' src="https://code.jquery.com/ui/1.11.3/jquery-ui.min.js"></script>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" />
<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.3/themes/ui-lightness/jquery-ui.css" />
<script src="recorder.js"></script>
<script src="jquery.voice.min.js"></script>
<style type='text/css'>
		body {
			font-family:Helvetica,sans-serif;
		}
		a.recording {
			background-color:red !important;
		}
        a.btn {
          background:#eee;
          border:1px solid #bbb;
          text-align:center;
          color:#222;
        }
        a.btn-danger {
          background:red;
          color:#fff;
        }
        a.btn-success {
          background:green; 
          color:#fff;
        }
        h2 {
	        font-weight:800;
	        font-size:110%;
	        float:right;
	        margin-top:-20px;
	        margin-right:20px;
	        line-height:0;
        }
        a.record {
	        width:50px;
	        height:50px;
	        font-size:300%;
	        border-radius:50px;
	        margin:0;
	        margin-left:-25px;
	        padding:10px;
        }
        a.play {
			font-size:200%;
			margin-left:10px;
			margin-top:-15px;
			padding:10px;
			border-radius:10px;
			float:left;
		}
        div#slider {
	        margin:20px 20px 20px 80px;
        }
        div#recorder_holder {
	        background:#fafafa;
	        border:1px solid #ccc;
	        margin:20px;
	        margin-top:40px;
	        border-radius:10px;
        }
        div#record_holder {
	        margin-left:50%;
	        margin-top:-35px;
        }
        div#time {
	        float:right;
	        margin-right:20px;
	        font-size:90%;
        }
        a.disabled {
	        color:#ccc;
        }
        #download {
	        float:right;
	        margin-right:10px;
	        display:none;
        }
	</style>
	<script>
	$(function() {
    	$( "#slider" ).slider({disabled:true,min:0,max:100,slide:function(event,ui) {
	    	console.log(ui.value);
	    	$('audio')[0].currentTime = ui.value/100*$('audio')[0].duration;
	    	updatePlay();
    	}});
	});
	</script>
	
	<div id='recorder'>
		<div id="recorder_holder">
			<div id='record_holder'><a onclick="toggleRecording(this);" class="fa-microphone fa record btn btn-default record"></a></div>
			<h2>Audio Recorder</h2>
			<a onclick="togglePlay(this);" id='playbutton' class="fa-play fa play btn btn-default disabled"></a>
			<div id='time'>00:00 / 00:00</div>
			<a href="" id="download" download="audiorecording.wav"><i class="fa fa-download"></i></a>
			<div id="slider"></div>
		</div>
	</div>
    <audio src="" id="audio"></audio>
	<script type='text/javascript'>
        var iOS = ( navigator.userAgent.match(/iPad|iPhone|iPod/g) ? true : false );
        function hasGetUserMedia() {
          return !!(navigator.getUserMedia || navigator.webkitGetUserMedia ||
            navigator.mozGetUserMedia || navigator.msGetUserMedia);
        }
        var recorder = null;
        var recorded = false;
        var recordTimer = null;
        var recordingTimer = null;
        var recordingCount = 0;
        navigator.getUserMedia  = navigator.getUserMedia ||
                          navigator.webkitGetUserMedia ||
                          navigator.mozGetUserMedia ||
                          navigator.msGetUserMedia;
                          
        $("#audio").bind('ended', function(){
		    clearInterval(recordTimer);
		    $('#playbutton').removeClass('btn-success');
		    $('#playbutton').removeClass('fa-pause');
			$('#playbutton').addClass('fa-play');
			var dur = $('#audio')[0].duration;
			var dursecs = formatTime(dur);
			$("#slider").slider('value',0);
			$('#time').html("00:00 / "+dursecs);
		});
      
		function toggleRecording(obj) {
			if($(obj).hasClass('btn-danger')) {
				$(obj).removeClass('btn-danger');
				$(obj).removeClass('fa-stop');
				$(obj).addClass('fa-microphone');
				stopRecording();
			} else {
				$(obj).addClass('btn-danger');
				$(obj).removeClass('fa-microphone');
				$(obj).addClass('fa-stop');
				startRecording();
			}
		}
		function togglePlay(obj) {
			if(recorded) {
				if($(obj).hasClass('btn-success')) {
					$(obj).removeClass('btn-success');
					$(obj).removeClass('fa-pause');
					$(obj).addClass('fa-play');
					pauseRecording();
				} else {
					$(obj).addClass('btn-success');
					$(obj).removeClass('fa-play');
					$(obj).addClass('fa-pause');
					playRecording();
				}
			}
		}
		function stopRecording() {
          if(iOS) {
			window.location = 'ios:stopaudiorecording';
          } else {
	          Fr.voice.export(function(url){
				  $("#audio").attr("src", url);
				  $("#download").attr("href", url);
				  $('#download').css({'display':'block'});
			  }, "URL");
	          Fr.voice.stop();
	          $( "#slider" ).slider({disabled:false});
	          clearInterval(recordingTimer);
          }
          $('#playbutton').removeClass('disabled');
          recorded = true;
          console.log("STOP RECORDING");
          setTimeout(function() {
            updatePlay();
          },500);
		}
		function startRecording() {
          if(iOS) {
			window.location = 'ios:startaudiorecording';
          } else {
	        recordingCount = 0;
            Fr.voice.record(false, function(){
              console.log("STARTED");
              recordingTimer = setInterval(function() {
	              recordingCount += 1;
				  var dursecs = formatTime(recordingCount);
				  $('#time').html("00:00 / "+dursecs);
	          }, 1000);
            });
          }
          console.log("START RECORDING");
		}
		function playRecording() {
          if(iOS) {
			window.location = 'ios:playaudiorecording';
          } else {
	        $("#audio")[0].play();
	        recordTimer = setInterval(updatePlay, 100);
          }
          console.log("PLAY RECORDING");
		}
		function pauseRecording() {
          if(iOS) {
			window.location = 'ios:pauseaudiorecording';
          } else {
            $('#audio')[0].pause();
            clearInterval(recordTimer);
          }
          console.log("PAUSE RECORDING");
		}
		function seekRecording() {
          if(iOS) {
			window.location = 'ios:seekaudiorecording';
          } else {
            
          }
		}
		function updatePlay() {
			var ct = $('#audio')[0].currentTime;
			var dur = $('#audio')[0].duration;
			var percentage = ct/dur*100;
			$("#slider").slider('value',percentage);
			
			var ctsecs = formatTime(ct);
			var dursecs = formatTime(dur);
			
			$('#time').html(ctsecs+" / "+dursecs);
		}
		function formatTime(time) {
			time = Math.round(time);
			secs = 0;
			mins = 0;
			if(time > 60) {
				mins = Math.floor(time/60);
			}
			secs = time%60;
			if(secs < 10) {
				secs = '0'+secs;
			}
			if(mins < 10) {
				mins = '0'+mins;
			}
			return mins+":"+secs;
		}
</script>