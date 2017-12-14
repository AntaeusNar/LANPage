<?php

function loadkml() {
/*
	The basics of this kml/xml that we are looking for are as follows

	For a time at a location it is a follows:

	<Placemark>
		<name>Home</name>
		<address>3509 Pelican Brief Ln, North Las Vegas, NV 89084</address>
		<ExtendedData>
			<Data name="Email"><value>jedidiah.h.wallace@gmail.com</value></Data>
			<Data name="Category"><value></value></Data>
			<Data name="Distance"><value>0</value></Data>
		</ExtendedData>
		<description>  from 2017-12-01T01:49:47.566Z to 2017-12-01T15:19:28.000Z. Distance 0m </description>
		<Point><coordinates></coordinates></Point>
		<gx:Track><altitudeMode>clampToGround</altitudeMode><gx:coord>-115.186723 36.286696899999995 0</gx:coord></gx:Track>
		<TimeSpan>
			<begin>2017-12-01T01:49:47.566Z</begin>
			<end>2017-12-01T15:19:28.000Z</end>
		</TimeSpan>
	</Placemark>

	
	For a driving time it is as follows:

	<Placemark>
		<name>Driving</name>
		<address></address>
		<ExtendedData>
			<Data name="Email"><value>jedidiah.h.wallace@gmail.com</value></Data>
			<Data name="Category"><value>Driving</value></Data>
			<Data name="Distance"><value>1714</value></Data>
		</ExtendedData>
		<description> Driving from 2017-12-01T15:19:28.000Z to 2017-12-01T15:31:08.488Z. Distance 1714m </description>
		<Point><coordinates></coordinates></Point>
		<gx:Track><altitudeMode>clampToGround</altitudeMode><gx:coord>-115.186723 36.286696899999995 0</gx:coord><gx:coord>-115.186723 36.286696899999995 0</gx:coord><gx:coord>-115.1862176 36.2873792 0</gx:coord><gx:coord>-115.1852681 36.2871483 0</gx:coord><gx:coord>-115.1844367 36.2879865 0</gx:coord><gx:coord>-115.1837498 36.2891433 0</gx:coord><gx:coord>-115.1836274 36.2892139 0</gx:coord><gx:coord>-115.1840721 36.2888006 0</gx:coord><gx:coord>-115.1839425 36.2890109 0</gx:coord><gx:coord>-115.1874429 36.2905438 0</gx:coord><gx:coord>-115.1874429 36.2905438 0</gx:coord><gx:coord>-115.1792073 36.2876378 0</gx:coord><gx:coord>-115.1792821 36.2877252 0</gx:coord></gx:Track>
		<TimeSpan>
			<begin>2017-12-01T15:19:28.000Z</begin>
			<end>2017-12-01T15:31:08.488Z</end>
		</TimeSpan>
	</Placemark>
	
	As you can see this is setup as:
	Placemark
		name
		address
		ExtendedData
			Data (Email)
				value
			Data (Category)
				value
			Data [3] (travel distiance in meters)
				value
		description
		Timespan (times are in UMT)
			begin
			end 
*/

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
		
		//Check to see if this an edited file
		if ($xml->Document->status == ready) {
			//shit is ready yo
			
		}
		else{
			//need to initalis the doc
			
		}
	
	
	}
}
?>