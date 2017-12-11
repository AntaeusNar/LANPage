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


class Placemark {
	//this class should be one for each placemark, it will be the object
	//where we store all of the placemark info from the kml file reading
	
	//from kml
	protected $name;
	protected $address;
	protected $email;
	protected $catagory;
	protected $distance;
	protected $timeBegin;
	protected $timeEnd;
	
	//internally generated/other
	protected $TimeType;
	protected $TimeDurOrg;
	protected $editTimeBegin;
	protected $editTimeEnd;
	protected $TimeDurCur;
	
	//__construct
	
	//math
	
	//display
	
	//what do you want?
	
	//export?
	
	
}
?>