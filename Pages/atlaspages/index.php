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

$topmenu = array(
	'1' => array('text'=>'1', 'url'=>'?=1'),
	'2' => array('text'=>'2', 'url'=>'?=2'),
	'3' => array('text'=>'3', 'url'=>'?=3'),
	'4' => array('text'=>'4', 'url'=>'?=4'),
);


//create objects
$MainMenu = new Menu("flex-column", "nav-vert", $mainmenu);
$TopMenu = new Menu("flex-row", "nav-hort", $topmenu);

//load header.htm file - which loads css
include "HTML/templates/header.htm";

//call to generate mainmenu based off of the array mainmenu
echo $MainMenu->display();
echo $TopMenu->display();


//load index.htm which is the main veiw content
include "HTML/templates/index.htm";
//load footer.htm
include "HTML/templates/footer.htm";

?>