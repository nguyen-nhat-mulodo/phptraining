<?php

class Controller_Helllo extends Controller_Template
{

	public function action_extends()
	{
		$data["subnav"] = array('extends'=> 'active' );
		$this->template->title = 'Helllo &raquo; Extends';
		$this->template->content = View::forge('helllo/extends', $data);
	}

	public function action_Restctronller()
	{
		$data["subnav"] = array('Restctronller'=> 'active' );
		$this->template->title = 'Helllo &raquo; Restctronller';
		$this->template->content = View::forge('helllo/Restctronller', $data);
	}

}
