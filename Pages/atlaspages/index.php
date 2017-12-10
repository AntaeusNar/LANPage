<?php

//Require Classes
require_once "CLASSES/class.menu.php";

//vars
//menu array
$mainmenu = array(
	'home' => array('text'=>'Home', 'url'=>'?p=home'),
	'away' => array('text'=>'Away', 'url'=>'?p=away'),
	'about'=> array('text'=>'About', 'url'=>'?p=about'),
);


//create objects
$MainMenu = new Menu("flex-column", "nav-vert", $mainmenu);


//load header.htm file - which loads css
include "HTML/templates/header.htm";

//call to generate mainmenu based off of the array mainmenu
echo $MainMenu->display();


//load index.htm which is the main veiw content
include "HTML/templates/index.htm";
//load footer.htm
include "HTML/templates/footer.htm";

?>