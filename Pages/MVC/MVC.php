<?php

class Model{
	
	//only accessable data
	public $string;
	
	public function __construct(){
		//puts some stuff into the data
		$this->string = "MVC + PHP = Awesome, click here!";
	}
}

class View {
	
	//not accessable from the outside
	private $model;
	private $controller;
	
	//adds the controller and model to the view class
	public function __construct($controller, $model){
		$this->controller = $controller;
		$this->model = $model;
	}
	
	//will output whatever is in the model string
	public function output(){
		return '<p><a href="MVC.php?action=clicked">' .$this->model->string ."</a></p>";
	}
}

class Controller {
	
	//not accessable from the outside
	private $model;
	
	//this sets the model given as what it do stuff to....
	public function __construct($model) {
		$this->model = $model;
	}
	
	//when passed a clicked action from the main page, this will change the model
	public function clicked() {
		$this->model->string = "Updated Data, thanks to MVC and PHP!";
	}
}

//Main code//

//Creates the model from the model class
$model = new Model();
//creates a controller for the model
$controller = new Controller($model);
//creates a veiw for both the controller and the model
$view = new View($controller, $model);

//if an action happens, this will pass it onto the controller
if (isset($_GET['action']) && !empty($_GET['action'])){
	$controller->{$_GET['action']}();
}

//displays the current view of the model....
echo $view->output();

?>