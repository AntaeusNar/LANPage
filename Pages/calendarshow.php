<html>
	<head>   
		<link href="/CSS/calendar.css" type="text/css" rel="stylesheet" />
	</head>
	<body>
		<?php
			include 'calendar.php';
 
			$calendar = new Calendar();
 
			echo $calendar->show();
		?>
	</body>
</html>  