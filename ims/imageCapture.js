(function(){

	var canvas = document.getElementById('canvas'),
		baseImg = canvas.getContext('2d'),
		overLay = document.getElementById('overLay'),
		overLayImg = overLay.getContext('2d'),
		pen = document.getElementById('pen'),
		dom = document.getElementById('dom'),
		ball = document.getElementById('ball'),
		fat = document.getElementById('fat'),
		video = document.getElementById('video'),
		post = document.getElementById('post'),
		captureBtn = document.getElementById('capture'),
		con = 0,
		vendorUrl = window.URL || window.webkitURL;
		

	navigator.getMedia = 	navigator.getUserMedia ||
							navigator.webkitGetUserMedia ||
							navigator.mozGetUserMedia ||
							navigator.msGetUserMedia ||
							navigator.oGetUserMedia;

	if (navigator.getUserMedia) {
			navigator.getUserMedia({video: true, audio: false}, 
				handleVideo, videoError);
		}	
		function handleVideo(stream) {
			video.srcObject = stream;
		}
		function videoError(error) {
			console.log("OH NO!")
			// An error occured
			// error.code
		}

	pen.addEventListener('click', function(){
		stickerobj = new Image;
		stickerobj.src = "../meme/meme1.png";
		stickerobj.onload = function() {
			overLayImg.drawImage(stickerobj, 0, 0, 75, 75);
		}
		});

	dom.addEventListener('click', function(){
		stickerobj = new Image;
		stickerobj.src = "../meme/meme2.png";
		stickerobj.onload = function() {
			overLayImg.drawImage(stickerobj, 0, 0, 75, 75);
		}
		});

	ball.addEventListener('click', function(){
		stickerobj = new Image;
		stickerobj.src = "../meme/meme3.png";
		stickerobj.onload = function() {
			overLayImg.drawImage(stickerobj, 0, 0, 75, 75);
		}
		});

	fat.addEventListener('click', function(){
		stickerobj = new Image;
		stickerobj.src = "../meme/meme4.png";
		stickerobj.onload = function() {
			overLayImg.drawImage(stickerobj, 0, 0, 75, 75);
		}
		});

	captureBtn.addEventListener('click', function(){
	    baseImg.drawImage(video, 0, 0, 400, 300);
	    con = 1;
	});
	document.getElementById('file-input').onchange = function(e) {
	    var img = new Image();
	    img.onload = draw;
	    img.onerror = failed;
	    img.src = URL.createObjectURL(this.files[0]);
	  };
	  function draw() {
	    var canvas = document.getElementById('canvas');
	    canvas.width = 400;
	    canvas.height = 300;
	    var ctx = canvas.getContext('2d');
	    ctx.drawImage(this, 0,0, 400, 300);
	    con = 1;
	  }
	  function failed() {
	    console.error("The provided file couldn't be loaded as an Image media");
	  }

	post.addEventListener('click', function () {

		if(con == 1){

	        var baseUrl = canvas.toDataURL();
	        let memeUrl = overLay.toDataURL();
	        const url = "imgProcessor.php";
	        var xhttp = new XMLHttpRequest();
	        var contents = "baseUrl="+baseUrl+"&memeUrl="+memeUrl;
	        xhttp.open("POST", url, true);
	        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	        xhttp.onreadystatechange = function(){
	            if(xhttp.status == 200){
	                console.log(this.responseText);
	            }
	        }
	        xhttp.send(contents);
	       window.location.reload(true);
	    }
	    else{
	        alert("No image added");
	    }
	});
})();