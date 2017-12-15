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
	$xml=simplexml_load_file("KML/history-2017-12-01.kml");
	
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
		if ($xml->Document->status == 'ready') {
			//shit is ready yo
			echo "<br><p> file is ready</p>";
			
		}
		else{
			//need to initalis the doc
			//echo "<br><p> file is not ready, but now we can fix it</p>";
			
			//add a few peices of information to the xml->document
			
			//Time is stupid or i am
			$now1 = new DateTime('now');
			$now2 = clone $now1;
			$xml->Document->addChild('TotalTime');
			$xml->Document->addChild('TotalDis');
			$xml->Document->addChild('StartDate');
			$xml->Document->addChild('EndDate');
			
			foreach ($xml->Document->Placemark as $Placemarks){
				
				//Change meters to miles
				$Placemarks->ExtendedData->Data[2]->value = round($Placemarks->ExtendedData->Data[2]->value / 1609.34, 1);
				
				//Change moving to driving
				if ($Placemarks->name == "Moving"){
					$Placemarks->name = "Driving";
				}
				
				//Change catagory to personal
				$Placemarks->ExtendedData->Data[1]->value = "Personal";
				
				//Change times
				$startTimeUTC = new DateTime($Placemarks->TimeSpan->begin, new DateTimeZone('UTC'));
				$endTimeUTC = new DateTime($Placemarks->TimeSpan->end, new DateTimeZone('UTC'));
				
				//convert start and end times into PST from UTC
				$startTimeALA = $startTimeUTC;
				$startTimeALA->setTimeZone(new DateTimeZone('America/Los_Angeles'));
				$endTimeALA = $endTimeUTC;
				$endTimeALA->setTimeZone(new DateTimeZone('America/Los_Angeles'));
				
				//check dates
				$startdate = $startTimeALA->format("Y-m-d");
				$enddate = $endTimeALA->format("Y-m-d");
				$Placemarks->TimeSpan->addChild('date', $startdate);
				if ($startdate == $enddate){
					$Placemarks->TimeSpan->begin = $startTimeALA->format("H:i");
					$Placemarks->TimeSpan->end = $endTimeALA->format("H:i");
					
				}else{
				$Placemarks->TimeSpan->begin = $startTimeALA->format("Y-m-d H:i");
				$Placemarks->TimeSpan->end = $endTimeALA->format("Y-m-d H:i");
				}
				
				echo "Total Interval 1: " .$xml->document->TotalTime ." -133- <br>";
				//calculate the interval or time duration
				$interval = date_diff($startTimeUTC, $endTimeUTC);
				$Placemarks->addChild('interval', $interval->format("%H:%I"));
				echo "Current Interval: " .$Placemarks->interval ." -137- <br>";
				
				//add interval to xml->document->totaltime
				//looks like for this to work, i need to make a datetime variable out of the current total time, add the interval, then convert the answer back into a string....yay!
				if ($xml->document->TotalTime == null){
					$xml->document->TotalTime = $interval->format("%H:%I");
					echo $xml->document->TotalTime ." -143- <br>";
				} else {
					$CurrentIntervalTotal = new datetime($xml->document->TotalTime);
					echo $CurrentIntervalTotal->format("%H:%I") ." -145- <br>";
					$CurrentIntervalTotal2 = date_add($CurrentIntervalTotal, $interval);
					$xml->document->TotalTime = $CurrentIntervalTotal2->format('%H:%I');
				}
				//$xml->document->TotalTime = date_diff($now1, date_add($now2, $interval));
				
				echo "Total Interval 2: " .$xml->document->TotalTime ." -151-";
				echo displayPlacemark($Placemarks);
			}
		}
	
	
	}
}

function placemarkinti($Placemark) {
	//this is going to initalis the placemark, adding the additionall info we need
	
	//convert the time zones to PST
	
	
}
function displayPlacemark($Placemark){
	//This will build a display of the placemark...hopefully alot of the logic will be removed once we edit things
	
	//start with the div and class
	$html = "<div class= 'placemark " .$Placemark->ExtendedData->Data[1]->value ."'>";
	
	//display catagory
	$html .= $Placemark->ExtendedData->Data[1]->value .": " .$Placemark->TimeSpan->date ."<br>";
	
	//Display Name
	$html .= "<strong>" .$Placemark->name ."</strong> ";
	
	//display miles if it is there
	if ($Placemark->name == "Driving"){
		$html .= $Placemark->ExtendedData->Data[2]->value . " Miles <br>";
	} else {
	//print the address
	$html .= $Placemark->address ."<br>";
	}
	
	//show times
	$html .= "Start Time: " .$Placemark->TimeSpan->begin ."     Total Time: " .$Placemark->interval ."<br>";
	$html .= "End Time: " .$Placemark->TimeSpan->end ." ";
	
	
	
	
	$html .= "</div>";
	
	return $html;
	
}

function updateKML(){
	//this should cycle through all of the relavent placemarks and totalup the needed info
}

function displayKML() {
	//this should display the total kml doc
}
	
?>