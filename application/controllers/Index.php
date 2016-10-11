<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends CI_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->helper( 'url' );
		$this->load->database();
		$this->load->model( 'Sight' );
	}


	public function index(){
		$data = array();
		// $data['sights'] = $this->Sight->getAllPaginated(0, 20);

		// $this->load->view( 'allsightsview', $data );
		$this->load->view( 'indexview', $data );
	}

}
