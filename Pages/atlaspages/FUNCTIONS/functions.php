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
			
			//add a few peices of information to the xml->document
			
			$xml->Document->addChild('TotalTime');
			$xml->Document->addChild('DisTotal');
			$xml->Document->addChild('StartDate');
			$xml->Document->addChild('EndDate');
			
			//Start the Placemark Column
			$html = '<div class="flex-column" id="placemarks">' .'<!--Start Placemark Column-->' ."\n";
			
			foreach ($xml->Document->Placemark as $Placemarks){
				
				//Change meters to miles and rounds to 0.00
				$Placemarks->ExtendedData->Data[2]->value = round($Placemarks->ExtendedData->Data[2]->value / 1609.34, 1);
				//getting ready to add miles to total miles
				$CurrentDis = (float)$Placemarks->ExtendedData->Data[2]->value;
				$CurTotDis = (float)$xml->Document->DisTotal;
				
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
				
				//calculate the interval or time duration
				$interval = date_diff($startTimeUTC, $endTimeUTC);
				$Placemarks->addChild('interval', $interval->format("P%dDT%HH%iM"));
				
				//check if this is a personal or business placemark
				if ($Placemarks->ExtendedData->Data[1]->value == "Business"){
					$test = $xml->Document->TotalTime;
					//Add the Milage
					$NewTotDis = $CurrentDis + $CurTotDis;
					$xml->Document->DisTotal = $NewTotDis;
					
					//add interval to xml->document->totaltime
					if ($test == ''){
						$xml->Document->TotalTime = $interval->format("P%dDT%HH%iM");
					} else {
						$CurrentIntervalTotal = new DateInterval($xml->Document->TotalTime);
						if ($CurrentIntervalTotal == false){
							echo "Error creating datetime on line 148ish in loadkml<br>";
						}else{
							$CurrentIntervalTotal = IntervalAdd($CurrentIntervalTotal, $interval);
							$xml->Document->TotalTime = $CurrentIntervalTotal->format("P%dDT%HH%iM");
						}
					}
				}
				
				//Call the Placemark display function
				$html .= displayPlacemark($Placemarks) ."\n";
				
			}
			$html .= '</div>' .'<!--End Placemark Column-->'. "\n\n";
			//show main details in next column
			$html .= TotalsDisplay($xml);
		}
	
	
	}
	return $html;
}


function displayPlacemark($Placemark){
	//This will build a display of the placemark...hopefully alot of the logic will be removed once we edit things
	
	//start with the div and class
	$html = "<div class= 'placemark " .$Placemark->ExtendedData->Data[1]->value ."'>";
	
	//display catagory
	$html .= '<select form="totalform" name="catagory"><option value="Personal" selected>Personal</option><option value="Business">Business</option>';
	//$html .= $Placemark->ExtendedData->Data[1]->value;
	$html .= '</select>' .": " .$Placemark->TimeSpan->date ."<br>";
	
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
	$CurrentPlacemarkInterval = new DateInterval($Placemark->interval);
	$html .= "Start Time: " .$Placemark->TimeSpan->begin ."     Total Time: " .$CurrentPlacemarkInterval->format("%H:%I") ."<br>";
	$html .= "End Time: " .$Placemark->TimeSpan->end ." ";
	
	
	
	
	$html .= "</div>";
	
	return $html;
	
}

function TotalsDisplay($xml) {
	//This will generate the display for the totals when given the $xml and return $html
	
	//basic framework
	$html = '<div class="flex-column">' ."\n";
	$html .= '<div class="placemark" id="totals">' . "\n" .'<form id="totalform" method="post">' ."\n";
	
	//test the Total time
	if ($xml->Document->TotalTime !=''){
		//dispay total time correctly
		$TotalTime = new DateInterval($xml->Document->TotalTime);
		$html .= 'Total Time: ' .$TotalTime->format("%d:%H:%I") ."<br>\n";
	} else {
		//just throwup whatever
		$html .= 'Total Time: ' .$xml->Document->TotalTime ."<br>\n";
	}
	$html .= 'Total Miles: ' .$xml->Document->DisTotal ."<br>\n";
	$html .= '<input type="submit" value="Update">'."\n";
	$html .= '</form>' ."\n" .'</div>' ."\n" .'</div>' ."\n";
	
	return $html;
	
}
	
function IntervalAdd($Intv1, $Intv2){
	//this should allow me to add to intervals together and return a total dateinterval
	$a = new DateTime('00:00');
	$b = clone $a;
	$a->add($Intv1);
	$a->add($Intv2);
	return $b->diff($a);
}
?>