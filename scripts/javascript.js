<script>

//this should only get called when the body is done loading.....
function initElement() {
	//alert("Page is Loaded");
	var mainheader = document.getElementById("mainheader");
	mainheader.innerHTML = mainHeader.innerHTML + "<p>Finsished Loading</p>";
}

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
document.getElementById("Loadkml").onclick = showAlert;

function showAlert(event) {
	alert("Holy Crap this finally did something");
}

</script>