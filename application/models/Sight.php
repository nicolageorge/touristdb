<?php

class Sight extends CI_Model{

	function __construct(){
		parent::__construct();
		$this->load->model("SightCategory");
		$this->load->model("Region");
	}

	public function addSight( $values ){
		return $this->db->insert( 'sights', $values );
	}

	public function saveSight( $siteId, $values ){
		$this->db->where( 'id', $siteId );
		$this->db->update( 'sights', $values );
	}

	public function getAll(){
		$query = $this->db->query("SELECT `sights`.`id`,
			`sights`.`name`,
			`sights`.`description`,
		    `sights`.`subcat_id`,
		    `sights_categories`.`id` as cat_id,
		    `sights_categories`.`name` as cat_name,
            `localities`.`name` as loc_name,
            `localities`.`region_id` as region_id
		    	FROM `sights`
		    INNER JOIN `sights_categories`
		    	ON `sights`.`cat_id` = `sights_categories`.`id`
            INNER JOIN `localities`
            	ON `sights`.`locality_id` = `localities`.`id`");
		$rez = [];

		$sCat = new SightCategory();
		$reg  = new Region();

		foreach( $query->result_array() as $row ){
			$row["subcat_name"] = $sCat->getCategNameById( $row["subcat_id"] );
			$row["region"] = $reg->getNameById( $row["region_id"] );
			$rez[] = $row;
		}
		return $rez;
	}

  public function getCount(){
    $query = $this->db->query( "SELECT count(id) as count FROM sights" );
    return $query->row()->count;
  }

	public function getAllPaginated($start, $pageSize){
    $theQuery = sprintf( "SELECT `sights`.`id`,
			`sights`.`name`,
			`sights`.`description`,
		    `sights`.`subcat_id`,
		    `sights_categories`.`id` as cat_id,
		    `sights_categories`.`name` as cat_name,
        `localities`.`name` as loc_name,
        `localities`.`region_id` as region_id,
        `localities`.`latitudine` as latitude,
        `localities`.`longitudine`as longitude
		    	FROM `sights`
		    LEFT JOIN `sights_categories`
		    	ON `sights`.`cat_id` = `sights_categories`.`id`
        LEFT JOIN `localities`
          ON `sights`.`locality_id` = `localities`.`id`
        LIMIT %d, %d", $start, $pageSize );

		$query = $this->db->query( $theQuery );
		$rez = [];

		$sCat = new SightCategory();
		$reg  = new Region();

		foreach( $query->result_array() as $row ){
			$row["subcat_name"] = $sCat->getCategNameById( $row["subcat_id"] );
			$row["region"] = $reg->getNameById( $row["region_id"] );
			$rez[] = $row;
		}
		return $rez;
	}

	public function loadById( $id ){
		$query = $this->db->query( sprintf( "SELECT * from sights WHERE id = %d", $id ) );
		return $query->result_array()[0];
	}

	public function getAllParentCategories(){
		$query = $this->db->query("SELECT * FROM sights_categories WHERE parent = 0");
		return $query->result_array();
	}

	public function getAllSubcategories(){
		$query = $this->db->query("SELECT * FROM sights_categories WHERE parent <> 0");
		return $query->result_array();
	}

  public function deleteById( $id ){
    //$query = $this->db->query( sprintf( "DELETE FROM sights WHERE id = %d", $id );
    $this->db->where( 'id', $id );
    $this->db->delete('sights');
  }
}
