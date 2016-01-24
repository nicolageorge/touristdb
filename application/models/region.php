<?php

class Region extends CI_Model{
	private $id;
	private $name;
	private $location;
	private $surface;
	private $neighbours;
	private $landscape;
	private $hidro_net;
	private $description;
	private $zone;
	private $url;
	private $latitude;
	private $longitude;

	function __construct(){
		parent::__construct();
	}

	public function getAll(){
		$query = $this->db->query("SELECT * FROM regions");
		$rez = [];
		foreach( $query->result_array() as $row ){
			$rez[] = $row;
		}
		return $rez;
	}

	public function getById( $id ){
		$stmt  = sprintf("SELECT * FROM regions WHERE id = %d", $id);
		$query = $this->db->query( $stmt );
		$row   = $query->row_array();
		if( $row != null ){
			return $row;
		}else{
			return "Invalid ID";
		}
	}

	public function getNameById( $id ){
		$stmt  = sprintf( "SELECT * FROM regions WHERE id = %d", $id );
		$query = $this->db->query( $stmt );
		$row   = $query->row_array();
		if( $row != null ){
			return $row["name"];
		}else{
			return "Invalid ID";
		}
	}

	public function getLocalityByRegionId( $id ){
		$query = $this->db->query( sprintf("SELECT * FROM localities WHERE region_id = %d", $id) );
		return $query->result_array();
	}
}