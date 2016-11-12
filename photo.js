(function(){

	var width = 560;
	var height = 0;
	var streaming = false,
	video = null,
	canvas = null,
	photo = null,
	startbutton = null;

	function startup(){
		video = document.querySelector('#video'),
		canvas = document.querySelector('#canvas'),
		photo = document.querySelector('#photo'),
		startbutton = document.querySelector('#startbutton'),

		navigator.getMedia = (navigator.getUserMedia ||
			navigator.webkitGetUserMedia ||
			navigator.mozGetUserMedia ||
			navigator.msGetUserMedia);

			navigator.getMedia(
				{
					video: true,
					audio: false
				},
				function(stream){
					if (navigator.mozGetUserMedia){
						video.mozSrcObject = stream;
					}
					else{
						var vendorURL = window.URL || window.webkitURL;
						video.src = vendorURL.createObjectURL(stream);
					}
					video.play();
				},
				function(err){
					console.log("An error occured! " + err);
				}
			);

	video.addEventListener('canplay', function(ev){
		if (!streaming) {
			height = video.videoHeight / (video.videoWidth/width);
			video.setAttribute('width', width);
			video.setAttribute('height', height);
			canvas.setAttribute('width', width);
			canvas.setAttribute('height', height);
			streaming = true;
		}
	}, false);

	startbutton.addEventListener('click', function(ev){
		takepicture();
		ev.preventDefault();
	}, false);

	clearphoto();
}

	function clearphoto(){
		var context = canvas.getContext('2d');
		context.fillStyle = "#AAA";
		context.fillRect(0, 0, canvas.width, canvas.height);
		var data = canvas.toDataURL('image/png');
		photo.setAttribute('src', data);
	}

	function takepicture(){
		var context = canvas.getContext('2d');
		var ajax = new XMLHttpRequest();
		if (width && height){
		canvas.width = width;
		canvas.height = height;
		context.drawImage(video, 0, 0, width, height);
		var data = canvas.toDataURL('image/png');
		if (document.getElementById('moustache').checked){
			ajax.open("POST",'save.php?filter=moustache',true);
			document.getElementById('moustache').checked = false;
			document.querySelector('#startbutton').style.backgroundColor = "rgb(219,219,219)";
			document.querySelector('#startbutton').style.borderColor = "rgb(219,219,219)";
		}
		else if (document.getElementById('trump').checked){
			ajax.open("POST",'save.php?filter=trump',true);
			document.getElementById('trump').checked = false;
			document.querySelector('#startbutton').style.backgroundColor = "rgb(219,219,219)";
			document.querySelector('#startbutton').style.borderColor = "rgb(219,219,219)";
		}
		else if (document.getElementById('jeanneige').checked){
			ajax.open("POST",'save.php?filter=jeanneige',true);
			document.getElementById('jeanneige').checked = false;
			document.querySelector('#startbutton').style.backgroundColor = "rgb(219,219,219)";
			document.querySelector('#startbutton').style.borderColor = "rgb(219,219,219)";
		}
		else {
			// le bouton n'est pas cliquable
			alert("Please select a filter first");
			return ;
		}
		ajax.setRequestHeader('Content-Type', 'application/upload');
		ajax.send(data);
		ajax.onreadystatechange = function() {
				if (ajax.readyState === 4 && ajax.status === 200) {
					// console.log(ajax.responseText);
					//canvas.src = ajax.responseText;
				//	canvas.style.display = 'block';
					//location.reload();
					photo.setAttribute('src', ajax.responseText);
				}

			};
		photo.setAttribute('src', data);
		//location.reload();
		//console.log(data);
		myRefresh();
	}	else{
		clearphoto();
	}
	}

	window.addEventListener('load', startup, false);
})();

function myRefresh(){
	document.querySelector('#aptrump').style.display = "none";
	document.querySelector('#apmoustache').style.display = "none";
	document.querySelector('#apjeanneige').style.display = "none";
	var ajax = new XMLHttpRequest();
	ajax.onreadystatechange = function(){
		if (ajax.readyState === 4 && ajax.status === 200) {
			document.getElementById("gallery").innerHTML = ajax.responseText;
		}

};
ajax.open("GET", 'miniat.php', true);
ajax.send();
	clearFile();
}

function clearFile() {
	var fu = document.getElementById('imgupload');
	if (fu != null) {
		document.getElementById('imgupload').outerHTML = fu.outerHTML;
	}
}


function previewFile(){
	var preview = document.querySelector('#photo'); //selects the query named img
	var file    = document.querySelector('input[type=file]').files[0]; //sames as here
	var reader  = new FileReader();
	var data;
	reader.onloadend = function () {
		preview.src = reader.result;
	}
	if (file) {
		var fileName = file['name'].toLowerCase();
		if (fileName.substr(fileName.length - 4) != ".jpg" && fileName.substr(fileName.length - 4) != ".png")
		{
			alert("invalid file extension");
			clearFile();
			return ;
		}
		reader.readAsDataURL(file); //reads the data as a URL
		return (preview.src);
	} else {
		preview.src = "";
	}
}


function editUpload(){
	if (previewFile())
	{
	var ajax = new XMLHttpRequest;
	var photo = document.querySelector('#photo');
	var data = previewFile();
	var field = document.getElementById('imgupload');
	if (document.getElementById('moustache').checked){
		ajax.open("POST",'save.php?filter=moustache',true);
		document.getElementById('moustache').checked = false;
		document.querySelector('#startbutton').style.backgroundColor = "rgb(219,219,219)";
		document.querySelector('#startbutton').style.borderColor = "rgb(219,219,219)";
	}
	else if (document.getElementById('trump').checked){
		ajax.open("POST",'save.php?filter=trump',true);
		document.getElementById('trump').checked = false;
		document.querySelector('#startbutton').style.backgroundColor = "rgb(219,219,219)";
		document.querySelector('#startbutton').style.borderColor = "rgb(219,219,219)";
	}
	else if (document.getElementById('jeanneige').checked){
		ajax.open("POST",'save.php?filter=jeanneige',true);
		document.getElementById('jeanneige').checked = false;
		document.querySelector('#startbutton').style.backgroundColor = "rgb(219,219,219)";
		document.querySelector('#startbutton').style.borderColor = "rgb(219,219,219)";
	}
	else {
		// le bouton n'est pas cliquable
		alert("Please select a filter first");
		return ;
	}
	ajax.setRequestHeader('Content-Type', 'application/upload');
	ajax.send(data);
	ajax.onreadystatechange = function() {
			if (ajax.readyState === 4 && ajax.status === 200) {
				// console.log(ajax.responseText);
				//canvas.src = ajax.responseText;
			//	canvas.style.display = 'block';
				//location.reload();
				if (ajax.responseText == "error")
					return ;
				photo.setAttribute('src', ajax.responseText);
			}

	};
	myRefresh();

//	clearFile();
}
else
	alert("Please select a file");

myRefresh();

}
