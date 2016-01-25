<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class sights extends CI_Controller{

	public function __construct(){
		parent::__construct();
        $this->load->library('session');
        $this->load->library('pagination');

        $this->load->helper('form');
		$this->load->helper( 'url' );
		$this->load->database();

		$this->load->library('form_validation');

		$this->load->model( 'Sight' );
		$this->load->model( 'Region' );
	}


	public function index(){
		$data = array();
    if( $this->uri->segment(3) ){
      $currentStart = $this->uri->segment(3);
    }else{
      $currentStart = 0;
    }

    $cfg['base_url']   = $this->config->base_url(). "sights/page/";
    $cfg['total_rows'] = $this->Sight->getCount();
    $cfg['per_page']   = 20;

    $cfg['full_tag_open']  = '<ul class="pagination">';
    $cfg['full_tag_close'] = "</ul>";

    $cfg['first_tag_open']  = '<li>';
    $cfg['first_tag_close'] = '</li>';
    $cfg['last_tag_open']  = '<li>';
    $cfg['last_tag_close'] = '</li>';
    $cfg['next_tag_open']  = '<li>';
    $cfg['next_tag_close'] = '</li>';
    $cfg['prev_tag_open']  = '<li>';
    $cfg['prev_tag_close'] = '</li>';
    $cfg['cur_tag_open']  = '<li><a href="#"><strong>';
    $cfg['cur_tag_close'] = '</strong></a></li>';
    $cfg['num_tag_open']  = '<li>';
    $cfg['num_tag_close'] = '</li>';

    $this->pagination->initialize( $cfg );

    $data['sights'] = $this->Sight->getAllPaginated($currentStart, 20);

		$this->load->view( 'allsightsview', $data );
	}

	public function addsight(){
		$data = array();
		$data[ "sightCategories" ] 		  = $this->Sight->getAllParentCategories();
		$data[ "sightSubcategories" ] 	= $this->Sight->getAllSubcategories();
		$data[ "regionLocalities" ] 	  = $this->Region->getLocalityByRegionId( 1 );

		$data[ "sightName" ] 		= "";
		$data[ "sightDescription" ] = "";
		$data[ "sightCategory" ] 	= "";
		$data[ "sightSubcategory" ] = "";
		$data[ "sightLocality" ] 	= "";
		$data[ "message" ]          = "";

		if( $this->input->server( 'REQUEST_METHOD' ) == 'POST' ){
			$data[ "sightName" ] 		= $this->input->post( 'sightName' );
			$data[ "sightDescription" ] = $this->input->post( 'sightDescription' );
			$data[ "sightCategory" ] 	= $this->input->post( 'sightCategory' );
			$data[ "sightSubcategory" ] = $this->input->post( 'sightSubcategory' );
			$data[ "sightLocality" ] 	= $this->input->post( 'sightLocality' );

			$addValues = array(
				"name" 		  => $data[ "sightName" ],
				"description" => $data[ "sightDescription" ],
				"cat_id" 	  => $data[ "sightCategory" ],
				"subcat_id"   => $data[ "sightSubcategory" ],
				"locality_id" => $data[ "sightLocality" ]
			);

			if( $addValues[ "name" ] != "" && $addValues[ "description" ] != "" ){
				$this->Sight->addSight( $addValues );
				$data[ "sightName" ] 		= "";
				$data[ "sightDescription" ] = "";
				$data[ "message" ] = "Atractia a fost creata";
			}else{
				$data[ "message" ] = "Erori de validare";
			}
		}

		$this->load->view( 'addsightview', $data );
	}

	public function editsight( $id ){
		$data = array();
		$currentSightId = $this->uri->segment(3);
		$data[ "currentSight" ] = $this->Sight->loadById( $currentSightId );

		$data[ "sightCategories" ]    = $this->Sight->getAllParentCategories();
		$data[ "sightSubcategories" ] = $this->Sight->getAllSubcategories();
		$data[ "regionLocalities" ]   = $this->Region->getLocalityByRegionId( 1 );

		$data[ "sightName" ] 		= $data[ "currentSight" ][ "name" ];
		$data[ "sightDescription" ] = $data[ "currentSight" ][ "description" ];
		$data[ "sightCategory" ] 	= $data[ "currentSight" ][ "cat_id" ];
		$data[ "sightSubcategory" ] = $data[ "currentSight" ][ "subcat_id" ];
		$data[ "sightLocality" ] 	= $data[ "currentSight" ][ "locality_id" ];
		$data[ "message" ] = "" ;

		if( $this->input->server( 'REQUEST_METHOD' ) == 'POST' ){
			$data[ "sightName" ] 		= $this->input->post( 'sightName' );
			$data[ "sightDescription" ] = $this->input->post( 'sightDescription' );
			$data[ "sightCategory" ] 	= $this->input->post( 'sightCategory' );
			$data[ "sightSubcategory" ] = $this->input->post( 'sightSubcategory' );
			$data[ "sightLocality" ] 	= $this->input->post( 'sightLocality' );

			$saveValues = array(
				"name" 		  => $data[ "sightName" ],
				"description" => $data[ "sightDescription" ],
				"cat_id" 	  => $data[ "sightCategory" ],
				"subcat_id"   => $data[ "sightSubcategory" ],
				"locality_id" => $data[ "sightLocality" ]
			);

			$this->Sight->saveSight( $currentSightId, $saveValues );
			$data[ "message" ] = "Atractia a fost modificata";
		}

		$this->load->view( 'editsightview', $data );
	}

}
