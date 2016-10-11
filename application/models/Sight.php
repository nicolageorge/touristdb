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
			`sights`.`validated`,
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

	public function getAllPaginated( $start = 0, $pageSize = 20, $filter = "" ){
    $theQuery = sprintf( "SELECT `sights`.`id`,
			`sights`.`name`,
			`sights`.`description`,
		    `sights`.`subcat_id`,
			`sights`.`validated`,
			`sights`.`address`,
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
        %s
        LIMIT %d, %d",$filter, $start, $pageSize );

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

	public function deleteById( $id ){
		//$query = $this->db->query( sprintf( "DELETE FROM sights WHERE id = %d", $id );
		$this->db->where( 'id', $id );
		$this->db->delete('sights');
	}

	public function getCoordinates( $sight_id ){
		$query = $this->db->query( sprintf( "SELECT latitude, longitude, locality_id from sights WHERE id = %d", $sight_id ) );
		$rez = $query->result_array()[0];

		if( $rez["latitude"] == 0 && $rez["longitude"] == 0 ){
			$loc_query = $this->db->query( sprintf( "SELECT * from localities WHERE id = %d", $rez["locality_id"] ) );
			$rez = $loc_query->result_array()[0];
			return [ "latitude" => $rez["latitudine"], "longitude" => $rez["longitudine"] ];
		}else{
			return [ "latitude" => $rez["latitude"], "longitude" => $rez["longitude"] ];
		}
	}

	public function getNearbyPlaces( $latitude, $longitude, $distance ){
		$query = sprintf( "SELECT *,
			3956 * 2 * ASIN( SQRT(
				POWER( SIN( ( %s - abs( `localities`.`latitudine` ) ) * pi()/180 / 2 ), 2 ) +
					COS( %s * pi() / 180 ) *
					COS( abs( `localities`.`latitudine` ) *  pi() / 180 ) *
				POWER( SIN( ( %f – abs( `localities`.`longitudine` ) ) *  pi() / 180 / 2), 2 )
			) ) as distance FROM `localities` having distance < %d ORDER BY distance limit 10;", $latitude, $latitude, $longitude, $distance );

		var_dump( $query );

	}
}
