<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class SightsFilter {

	private $filterString = "";
	private $filterName   = "";
	private $filterRegions = "";
    private $filterValidated = "";

	public function getFilterName(){
		return $this->filterName;
	}

    public function getFilterString(){
    	return $this->filterString;
    }

	public function setFilterName( $name ){
		$this->filterName = $name;
	}

	public function setFilterString( $val ){
		$this->filterString = $val;
	}

	private function setFilterRegions( $region_ids ){
		$this->filterRegions = $region_ids;
	}

    private function setFilterValidated( $param ){
        $this->filterValidated = $param;
    }

    public function buildFilterFromParams( $params ) {
        var_dump( $params );

    	if( isset( $params["filter_name"] ) ){
    		$this->setFilterName( $params["filter_name"] );
    	}

    	if( !empty( $params["filter_regions"] ) ){
			$this->setFilterRegions( $params["filter_regions"] );		
    	}

        if( isset( $params["filter_validated"] ) ){
            $this->setFilterValidated( $params["filter_validated"] );
        }
    }

    public function prepareFilterString(){
    	if( $this->filterName != "" ){
    		$this->filterString = "`sights`.`name` LIKE '%" . $this->filterName . "%'";	
    	}

    	if( !empty( $this->filterRegions ) ){
    		if( $this->filterName != "" ){
    			$this->filterString .= " AND ";
    		}
    		$this->filterString .= "`localities`.`region_id` IN ( " . $this->filterRegions . " )";
    	}

        if( !empty( $this->filterValidated ) ){
            if( $this->filterName != "" || $this->filterRegions != "" ){
                $this->filterString .= " AND ";
            }
            $this->filterString .= "`sights`.`validated` = " . $this->filterValidated;
        }

    	$this->filterString = " WHERE " . $this->filterString;
    }

    public function getFilterWhereString(){
    	if( $this->filterName != "" || !empty( $this->filterRegions ) ){
    		$this->prepareFilterString();
    	}
        var_dump( $this->filterString );
    	return $this->filterString;
    }

}

/* End of file SightsFilter.php */