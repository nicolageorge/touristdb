<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class sights extends CI_Controller{

	public function __construct(){
		parent::__construct();
    	$this->load->library( 'session' );
    	$this->load->library( 'pagination' );

    	$this->load->helper( 'form' );
		$this->load->helper( 'url' );
		$this->load->database();

		$this->load->library( 'form_validation');
		$this->load->library( 'SightsFilter');
		$this->load->library( 'formbuilder' );

		$this->load->model( 'Sight' );
		$this->load->model( 'Region' );
		$this->load->model( 'sightCategory' );

		$this->config->load( 'pagination' );
	}

	public function index(){
		$data = array();
	    if( $this->uri->segment(3) ){
	      	$currentStart = $this->uri->segment(3);
	    }else{
	      	$currentStart = 0;
	    }

	    if( $this->input->server( 'REQUEST_METHOD' ) == 'POST' ){

	    	if( $this->input->post( 'sightsNameFilter' ) ){
	    		$this->session->set_userdata( 'sights_filter_name', $this->input->post( 'sightsNameFilter' ) );
	    	}else{
	    		$this->session->set_userdata( 'sights_filter_name', "" );
	    	}

	    	if( $this->input->post( 'sightsRegionFilter' ) ){
	    		$this->session->set_userdata( 'sights_filter_regions', $this->input->post( 'sightsRegionFilter' ) );
	    	}else{
	    		$this->session->set_userdata( 'sights_filter_regions', "" );
	    	}

	    	if( $this->input->post( 'sightsValidatedFilter' ) !== false ){
	    		$this->session->set_userdata( 'sights_filter_validated', $this->input->post( 'sightsValidatedFilter' ) );
	    	}else{
	    		$this->session->set_userdata( 'sights_filter_validated', "" );
	    	}
	    }

	    $sightsFilter = new SightsFilter();
		if( $this->session->userdata( 'sights_filter_name' ) || $this->session->userdata( 'sights_filter_regions' ) ){
	    	$sightsFilter->buildFilterFromParams( array(
	    		'filter_name' 	 => $this->session->userdata( 'sights_filter_name' ),
	    		'filter_regions' => $this->session->userdata( 'sights_filter_regions' ),
	    		'filter_validated' => $this->session->userdata( 'sights_filter_validated' )
	    	) );
	    }
	    $filter = $sightsFilter->getFilterWhereString();

	    $form_options = array(
	    	array(	'id' => 'sightsNameFilterId',
		        'placeholder' => 'Numele atractiei',
		        'label' => 'Numele Atractiei',
		        'name'  => 'sightsNameFilter',
		        'value' => $this->session->userdata( 'sights_filter_name' )
			)
		);
		$this->formbuilder->init( array( 'default_form_control_class' => 'col-sm-2' ) );
		$data['sights_filter_form_elements'] = $this->formbuilder->build_form_horizontal( $form_options );

	    $data['sights'] = $this->Sight->getAllPaginated($currentStart, $this->config->config["pagination"]["per_page"], $filter);
	    $data['regions'] = $this->Region->getAllNames();
	    $data['filter_region_value'] = $this->session->userdata( 'sights_filter_regions' );
	    $data['filter_validated_value'] = $this->session->userdata( 'sights_filter_validated' );

		$cfg = $this->config->config["pagination"];
	    $cfg['base_url']   = $this->config->base_url(). "index.php/sights/page/";
	    $cfg['total_rows'] = $this->Sight->getCount();
	    $this->pagination->initialize( $cfg );

		$this->load->view( 'allsightsview', $data );
	}

	public function addsight(){
		$data = array();
		$data[ "sightCategories" ] 		  = $this->sightCategory->getAllParentCategories();
		$data[ "sightSubcategories" ] 	  = $this->sightCategory->getAllSubcategories();
		$data[ "regionLocalities" ] 	  = $this->Region->getLocalityByRegionId( 1 );

		$data[ "sightName" ] 		= "";
		$data[ "sightDescription" ] = "";
		$data[ "sightCategory" ] 	= "";
		$data[ "sightSubcategory" ] = "";
		$data[ "sightLocality" ] 	= "";
		$data[ "sightPrice" ]	 	= "";
		$data[ "sightProgram" ] 	= "";
		$data[ "sightAddress" ] 	= "";
		$data[ "sightContact" ] 	= "";
		$data[ "sightLatitude" ]	= "";
		$data[ "sightLongitude" ]   = "";
		$data[ "sightValidated" ] 	=  0;

		$data[ "message" ]          = "";

		if( $this->input->server( 'REQUEST_METHOD' ) == 'POST' ){
			$data[ "sightName" ] 		= $this->input->post( 'sightName' );
			$data[ "sightDescription" ] = $this->input->post( 'sightDescription' );
			$data[ "sightCategory" ] 	= $this->input->post( 'sightCategory' );
			$data[ "sightSubcategory" ] = $this->input->post( 'sightSubcategory' );
			$data[ "sightLocality" ] 	= $this->input->post( 'sightLocality' );
			$data[ "sightPrice" ] 		= $this->input->post( 'sightPrice' );
			$data[ "sightProgram" ] 	= $this->input->post( 'sightProgram' );
			$data[ "sightAddress" ] 	= $this->input->post( 'sightAddress' );
			$data[ "sightContact" ] 	= $this->input->post( 'sightContact' );
			$data[ "sightLatitude" ]	= $this->input->post( 'sightLatitude' );
			$data[ "sightLongitude" ]  	= $this->input->post( 'sightLongitude' );
			$data[ "sightValidated" ] 	= ( $this->input->post( 'sightValidated' ) == "sightValidated" ? 1 : 0 );

			$addValues = array(
				"name" 		  => $data[ "sightName" ],
				"description" => $data[ "sightDescription" ],
				"cat_id" 	  => $data[ "sightCategory" ],
				"subcat_id"   => $data[ "sightSubcategory" ],
				"locality_id" => $data[ "sightLocality" ],
				"price"		  => $data[ "sightPrice" ],
				"program"	  => $data[ "sightProgram" ],
				"address"     => $data[ "sightAddress" ],
				"contact"	  => $data[ "sightContact" ],
				"latitude"	  => $data[ "sightLatitude" ],
				"longitude"   => $data[ "sightLongitude" ],
				"validated"   => $data[ "sightValidated"]
			);

			if( $addValues[ "name" ] != "" && $addValues[ "description" ] != "" ){
				$this->Sight->addSight( $addValues );

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

		$coords = $this->Sight->getCoordinates( $currentSightId );

		$nearby = $this->Sight->getNearbyPlaces( $coords["latitude"], $coords["longitude"], 10 );
		var_dump( $nearby );
		die();

		$data[ "sightCategories" ]    = $this->sightCategory->getAllParentCategories();
		$data[ "sightSubcategories" ] = $this->sightCategory->getAllSubcategories();
		$data[ "regionLocalities" ]   = $this->Region->getLocalityByRegionId( 1 );

		$data[ "sightName" ] 		= $data[ "currentSight" ][ "name" ];
		$data[ "sightDescription" ] = $data[ "currentSight" ][ "description" ];
		$data[ "sightCategory" ] 	= $data[ "currentSight" ][ "cat_id" ];
		$data[ "sightSubcategory" ] = $data[ "currentSight" ][ "subcat_id" ];
		$data[ "sightLocality" ] 	= $data[ "currentSight" ][ "locality_id" ];
		$data[ "sightPrice" ]       = $data[ "currentSight" ][ "price" ];
		$data[ "sightProgram" ]    	= $data[ "currentSight" ][ "program" ];
		$data[ "sightAddress" ]		= $data[ "currentSight" ][ "address" ];
		$data[ "sightContact" ]		= $data[ "currentSight" ][ "contact" ];
		$data[ "sightLatitude" ]	= $data[ "currentSight" ][ "latitude" ];
		$data[ "sightLongitude" ]	= $data[ "currentSight" ][ "longitude" ];
		$data[ "sightValidated" ] 	= $data[ "currentSight" ][ "validated" ];

		$data[ "message" ] = "" ;

		if( $this->input->server( 'REQUEST_METHOD' ) == 'POST' ){
			$data[ "sightName" ] 		= $this->input->post( 'sightName' );
			$data[ "sightDescription" ] = $this->input->post( 'sightDescription' );
			$data[ "sightCategory" ] 	= $this->input->post( 'sightCategory' );
			$data[ "sightSubcategory" ] = $this->input->post( 'sightSubcategory' );
			$data[ "sightLocality" ] 	= $this->input->post( 'sightLocality' );
			$data[ "sightPrice" ] 		= $this->input->post( 'sightPrice' );
			$data[ "sightProgram" ] 		= $this->input->post( 'sightProgram' );
			$data[ "sightAddress" ] 		= $this->input->post( 'sightAddress' );
			$data[ "sightContact" ] 		= $this->input->post( 'sightContact' );
			$data[ "sightLatitude" ]		= $this->input->post( 'sightLatitude' );
			$data[ "sightLongitude" ]		= $this->input->post( 'sightLongitude' );
			$data[ "sightValidated" ] 		= ( $this->input->post( 'sightValidated' ) == "sightValidated" ? 1 : 0 );

			$saveValues = array(
				"name" 		  => $data[ "sightName" ],
				"description" => $data[ "sightDescription" ],
				"cat_id" 	  => $data[ "sightCategory" ],
				"subcat_id"   => $data[ "sightSubcategory" ],
				"locality_id" => $data[ "sightLocality" ],
				"price"   => $data[ "sightPrice" ],
				"program" => $data[ "sightProgram" ],
				"address" => $data[ "sightAddress" ],
				"contact"  => $data[ "sightContact" ],
				"latitude" => $data[ "sightLatitude" ],
				"longitude" => $data[ "sightLongitude" ],
				"validated" => $data[ "sightValidated" ]
			);

			$this->Sight->saveSight( $currentSightId, $saveValues );
			$data[ "message" ] = "Atractia a fost modificata";
		}

		$this->load->view( 'editsightview', $data );
	}

  	public function deletesight( $id ){
    $data = array();
    $sightToDelete = (int)$this->uri->segment(3);
    $data["message"] = "Atractia a fost stearsa";

	if( is_int( $sightToDelete ) ){
		try{
			$this->Sight->deleteById( $sightToDelete );
		}catch(Exception $ex){
			$data["message"] = "A aparut o eroare, detalii : " . $ex->getMessage();
		}
	}

    	$this->load->view( 'deletesightview', $data );
	}

}
