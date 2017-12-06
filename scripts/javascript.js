<script>

function updateElement($id, $content) {
	document.getElementById($id).innerHTML = $content;
}

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

</script>