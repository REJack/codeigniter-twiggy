<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function index()
	{	
		$this->twiggy->set(
			array(
				'elapsed_time'=>$this->benchmark->elapsed_time('total_execution_time_start', 'total_execution_time_end'),
				'ENVIRONMENT'=>ENVIRONMENT,
				'CI_VERSION'=>CI_VERSION
				)
			);
		$this->twiggy->display('welcome_message');
	}
}
