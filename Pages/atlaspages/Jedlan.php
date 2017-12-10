<?php


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
			Data
				value
			Data
				value
			Data [3] (travel distiance in meters)
				value
		description
		Timespan (times are in UMT)
			begin
			end 
*/

//variables

//totDur...so you can't add a dateTime to zero, because there is no zero dateTime...so what we do is make
//two DateTimes of NOW and then we add intervals to one of them...and then we see what the differance is between the two.....
$totDurE = new DateTime('00:00', new DateTimeZone('UTC'));
$totDurF = clone $totDurE;
$totDis = 0;


//Function for Getting the starting information
function requstform(){
	//build a form with start and end dates and start/end times
	//on submit call the loadkml with the start/end time and dates as paramaters
	
}

//Function for loading the kml into simplexml
function loadkml($startDate, $endDate,$startTime, $endTime) {
	
	//request the kml info from googleTimeline and send the Placemark info to the placemark function
	
	/*load the kml file into simplexml -- need to build out this section*/
	$xml=simplexml_load_file("history-2017-12-01.kml");
	
	//error checking
	if ($xml === false) {
		echo "Failed loading XML: ";
		foreach(libxml_get_errors() as $error) {
			echo "<br>", $error->message;
		}
	} else {
		
		//okay, we are going to drop out of PHP into HTML, then using a script to get the PHP back into HTML...
	
		?>
		
		<!--HTML line 99-->
		<script type="text/javascript">
			document.getElementById("status").innerHTML = "load kml sucessful";
			document.getElementById("myColumn1").innerHTML = "
			<!--hidden HTML line 100-->
			<!--Placemark line 100-->
				<?PHP
				echo "<!--line 102-->";
				//send to placemark
				//cyle through each Placemark
					foreach ($xml->Document->Placemark as $Placemark) {
			
						placemark($Placemark);
					}
				?>
			";
		</script>
<?php
		
	}
}

//Function for displaying each placemark
function placemark($Placemark){
	
	//variables
	$placeDis = 0;
	
	//build a display of each placemark and add the time/distance to totals
	echo "<div class='placemark'>";
	
	//Display the name and address
	echo "<strong>", $Placemark->name,"</strong> ", $Placemark->address;
	
	//check distiance traveled and display if needed
	if (round($Placemark->ExtendedData->Data[2]->value / 1609.34, 1) >.1) {
		echo round($Placemark->ExtendedData->Data[2]->value / 1609.34, 1) , " miles ";
		
		//calculate the disance in miles
		$placeDis = round($Placemark->ExtendedData->Data[2]->value / 1609.34, 1);
	
	}
	//set datetimes to the UTC they are
	$startTimeUTC = new DateTime($Placemark->TimeSpan->begin, new DateTimeZone('UTC'));
	$endTimeUTC = new DateTime($Placemark->TimeSpan->end, new DateTimeZone('UTC'));
			
	//calculate the interval
	$interval = date_diff($startTimeUTC, $endTimeUTC);
			
	//call the addTotals with the interval and placeDis
	addTotals($placeDis, $interval);
	
	
	//convert start and end times into PST from UTC
	$startTimeALA = $startTimeUTC;
	$startTimeALA->setTimeZone(new DateTimeZone('America/Los_Angeles'));
	$endTimeALA = $endTimeUTC;
	$endTimeALA->setTimeZone(new DateTimeZone('America/Los_Angeles'));
			
	//Display the interval and start/end times
	echo $interval->format("<br>Duration:%H:%I ");
	echo "Start: ", $startTimeALA->format("Y-m-d H:i ");
	echo "End: ", $endTimeALA->format("Y-m-d H:i"), "<br>";
	echo "</div>";
}

//Function for adding the durations to a running total
function addTotals($eachDis, $eachTime) {
	//add the times
	global $totDurE;
	global $totDis;
	
	$totDurE->add($eachTime);
	
	//add the distance
	$totDis = $totDis+$eachDis;
	
	//call displayTotal
	displayTotal($totDis, $totDurE);
	
}

//Function for removing the durations from the running total
function subtractTotals ($eachDis, $eachTime){
	
}

//function for displaying the running totals
function displayTotal ($totDis, $totTime) {
	
	global $totDurF;
	//figure out the hours and mins from all this date and totDur crap
	$diff = $totTime->diff($totDurF);
	$hours = $diff->h;
	$min = $diff->i;
	$hours = $hours + ($diff->days*24);
	
	//display remaining info and close the <div> -- the hack here is i drop out to php, into html, then run javascript to inject php into the html....i think....
	?>
	
	<!--I have now dropped into pure HTML....-->
	<script>
		updateElement("2nd-column","This got updated");
		document.getElementById("2nd-column").innerHTML = "
			<?php
				//this should be injecting the following PHP into a script, into HTML into PHP...I hope
				echo "<div class='placemark'>";
				echo "Total Distance: ", $totDis, " miles.<br>Total Time: ", $hours, ":", $min;
				echo "</div>";
			?>
			";
	</script>
	<?php
}

//Main page

include "/atlascon.htm";




loadkml(1,1,1,1);







//funtion for exporting the information

/*
	//load the kml file into simplexml//
	$xml=simplexml_load_file("history-2017-12-01.kml");
	//error checking
	if ($xml === false) {
		echo "Failed loading XML: ";
		foreach(libxml_get_errors() as $error) {
			echo "<br>", $error->message;
		}
	} else {
		//set some html stuff
			
			
			
		//cyle through each Placemark
		foreach ($xml->Document->Placemark as $Placemark) {
			
			echo "<div class='placemark'>";
			//Display the name and address
			echo "<strong>", $Placemark->name,"</strong> ", $Placemark->address;
			
			//check distiance traveled and display if needed
			if (round($Placemark->ExtendedData->Data[2]->value / 1609.34, 1) >.1) {
				echo round($Placemark->ExtendedData->Data[2]->value / 1609.34, 1) , " miles ";
				
				//add the distance to $totDis
				$totDis = $totDis+round($Placemark->ExtendedData->Data[2]->value / 1609.34, 1);
				
			}
			//set datetimes to the UTC they are
			$startTimeUTC = new DateTime($Placemark->TimeSpan->begin, new DateTimeZone('UTC'));
			$endTimeUTC = new DateTime($Placemark->TimeSpan->end, new DateTimeZone('UTC'));
			
			//calculate the interval
			$interval = date_diff($startTimeUTC, $endTimeUTC);
			
			//add the interval to $totDur
			$totDurE->add($interval);
			
			//convert start and end times into PST from UTC
			$startTimeALA = $startTimeUTC;
			$startTimeALA->setTimeZone(new DateTimeZone('America/Los_Angeles'));
			$endTimeALA = $endTimeUTC;
			$endTimeALA->setTimeZone(new DateTimeZone('America/Los_Angeles'));
			
			//Display the interval and start/end times
			echo $interval->format("<br>Duration:%H:%I ");
			echo "Start: ", $startTimeALA->format("Y-m-d H:i ");
			echo "End: ", $endTimeALA->format("Y-m-d H:i"), "<br>";
			echo "</div>";
		}
		
		//figure out the hours and mins from all this date and totDur crap
		$diff = $totDurE->diff($totDurF);
		$hours = $diff->h;
		$min = $diff->i;
		$hours = $hours + ($diff->days*24);
		
		//display remaining info and close the <div>
		echo "<div class='placemark'>";
		echo "Total Distance: ", $totDis, " miles.      Total Time: ", $hours, ":", $min;
		echo "</div>";
	}

*/
