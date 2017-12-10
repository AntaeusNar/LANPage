<?php


//vars
//menu array
$mainmenu = array(
	'home' => array('text'=>'Home', 'url'=>'?p=home'),
	'away' => array('text'=>'Away', 'url'=>'?p=away'),
	'about'=> array('text'=>'About', 'url'=>'?p=about'),
);

//classes
class CNavigation {
	public static function GenerateMenu($items){
		$html = "<div class='flex-column' id='nav-vert'><ul>\n";
		foreach($items as $item){
			$html .= "<li><a href='{$item['url']}'>{$item['text']}</a></li>\n";
		}
		$html .= "</div>\n";
		return $html;
	}
}


//load header.htm file - which loads css
include "HTML/templates/header.htm";

echo CNavigation::GenerateMenu($mainmenu);

//load mainmenu.htm file
//include "HTML/templates/mainmenu.htm";
//load index.htm which is the main veiw content
include "HTML/templates/index.htm";
//load footer.htm
include "HTML/templates/footer.htm";

?>