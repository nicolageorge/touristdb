<?php

class SightCategory extends CI_Model{
	private $all_categ;

	function __construct() {
		parent::__construct();

		$query = $this->db->query("SELECT * FROM sights_categories");
		$this->all_categ = [];
		foreach( $query->result_array() as $row ) {
			$this->all_categ[ $row["id"] ] = $row;
		}
	}

	public function getAllCateg() {
		return $this->all_categ;
	}

	public function getCategById( $id ){
		return $this->all_categ[ $id ];
	}

	public function getCategNameById( $id ){
		if( !empty( $this->all_categ[ $id ] ) )
			return $this->all_categ[ $id ]["name"];	
		else
			return "";
	}

	public function getAllParentCategories(){
		$query = $this->db->query("SELECT * FROM sights_categories WHERE parent = 0");
		return $query->result_array();
	}

	public function getAllSubcategories(){
		$query = $this->db->query("SELECT * FROM sights_categories WHERE parent <> 0");
		return $query->result_array();
	}


}


