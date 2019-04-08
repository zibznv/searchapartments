
function changeImg(){
	var bodyimg = document.getElementById('backimg');
	var rand = 0.5 + Math.random() * 32;
	rand = Math.round(rand);
	bodyimg.style.backgroundImage = 'url(../image/apartments/image' + rand + '.jpg)';
}
setInterval(changeImg, 10000);




/*
function getXMLHttpRequest()
{
   if (window.XMLHttpRequest) { return new XMLHttpRequest(); }
   return new ActiveXObject('Microsoft.XMLHTTP');
}

function fullTankControl(){
	
	request = getXMLHttpRequest();
	url = "/fuelInTankWB_[" + new Date().getTime() + "]"; // Добавляем метку времени для уникальности url

	request.onreadystatechange = doner;
	//request.open('GET', url, true);
	//request.send(null);
	request.open('POST', url, true);
	request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	request.send("phone=067-508-07-09&address=Svytoshinskay 60");
}

function doner() {
	if (request.readyState == 4) {
		if (request.status == 200) {
			var responseBody = request.responseText;
			
			//var responseBody = request;
			//var message = request.getResponseHeader("Status");
			//alert(responseBody + "  " + message);
			
			alert(responseBody);
			console.log(request);
		}	
	}
}
*/




