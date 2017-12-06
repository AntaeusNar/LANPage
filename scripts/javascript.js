<script type="text/javascript">

//this should only get called when the body is done loading.....
function initElement() {
	//MainHeader Testing
	var mainheader = document.getElementById("mainheader");
	
	if (mainheader == null) {
		alert("mainheader is null!");
	}else {
		alert("well we found the mainheader");
	}
	mainheader.innerHTML = mainheader.innerHTML + "<p>Finsished Loading</p>";
	
	//footer testing
	var footer = document.getElementById("footer");
	footer.onclick = showAlert;
	
	
};

//this will load when the atlascon.htm is done loading....I hope
function initatlascon(){
	alert("atlascon finished loading");
	//sub doc element testing
	var loadkml = document.getElementById("Loadkml");
	if (loadkml == null) {
		alert("can't find loadkml");
	};
	loadkml.onclick = showAlert;
};
	
// update element generic function
function updateElement($id, $content) {
	document.getElementById($id).innerHTML = $content;
};

	
//loadDoc generic function
function loadDoc($id, $path) {
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById($id).innerHTML =
				this.responseText;
			};
		};
	xhttp.open("GET", $path, true);
	xhttp.send();
};


//onclick functions


function showAlert(event) {
	alert("Holy Crap this finally did something");
};

</script>