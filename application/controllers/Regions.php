<?php
// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';

class Regions extends REST_Controller{

	function __construct(){
		parent::__construct();
        $this->load->model('Region');
	}

	public function all_get(){
        $this->response( $this->Region->getAll() );
	}

	function region_get()
    {
        if( !empty( $this->get('id') ) ){
            $data = $this->Region->getById( $this->get('id') ); 
            $this->response( $data );
        }else{
            $this->response( "ID not set" );
        }
    }
    /*
    function region_post()
    {       
        $data = array('returned: '. $this->post('id'));
        $this->response($data);
    }
 
    function region_put()
    {       
        $data = array('returned: '. $this->put('id'));
        $this->response($data);
    }
 
    function region_delete()
    {
        $data = array('returned: '. $this->delete('id'));
        $this->response($data);
    }
    */
}