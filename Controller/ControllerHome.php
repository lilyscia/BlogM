<?php

use \Alice\Framework\Controller;

class ControllerHome extends Controller
{
	public function index() 
	{
		$this->generateView(array());
    }
}
