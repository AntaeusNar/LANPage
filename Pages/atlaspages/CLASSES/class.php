<?php



class Menu {
	//this class is a div flex box menu
	
	protected $htmlclass; //the class of the div
	protected $htmlid; //the id of the div
	protected $menuitems; //the array of items in the menu
	
	//Builds a new menu Object with given varables
	public function __construct ($HTMLClass, $HTMLID, $MenuItems) {
		$this->htmlclass = $HTMLClass;
		$this->htmlid = $HTMLID;
		$this->menuitems = $MenuItems;
	}
	
	//displays the created menu object
	public function display() {
		//generates div html
		$html = "<div class='{$this->htmlclass}' id='{$this->htmlid}'>\n";
		//generates each item html
		foreach($this->menuitems as $item){
			$html .= "<a href='{$item['url']}'>{$item['text']}</a>\n";
		}
		//closes div html
		$html .="\n</div>\n";
		return $html;
	}
}

?>