<?php

function loadkml() {

	//loads the given kml into an xml with simplexml
	$xml=simplexml_load_file("");
	
	//error checking
	if ($xml === false) {
		//found some errors...list them out
		echo "Failed loading xml: ";
		foreach(libxml_get_errors() as $error) {
			echo "<br>", $error->message;
		}
	} 
	else {
		//no errors on load found moving on
		
	
	
	}
}
?>