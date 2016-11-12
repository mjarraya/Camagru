function checkDel(path)
{
 var r = confirm("Voulez vous vraiment supprimer votre oeuvre d\'art?");
 if (r == true)
	 window.location = "deletephoto.php?id=".concat(path);
}
function checkLike(id, user)
{
 var button = document.getElementById('like');
 var count = document.getElementById('countlikes');
 var ajax = new XMLHttpRequest();
 if (button.innerHTML == "j\'aime")
	 button.innerHTML = "je n\'aime plus";
 else
	 button.innerHTML = "j\'aime";
 ajax.open("GET", 'like.php?id='.concat(id)+'&user='.concat(user), true);
 ajax.send();
 ajax.onreadystatechange = function(){
	 if (ajax.readyState === 4 && ajax.status === 200) {
		 count.innerHTML = ajax.responseText;
	 }
 };
}
