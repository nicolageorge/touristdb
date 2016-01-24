<?php

class DB{
	private $dbhost = DB_HOST;
	private $dbuser = DB_USER;
	private $dbpass = DB_PASS;
	private $database=DB_NAME;
	private $query = "";
	private $connection;

	function __construct(){
		$this->connection = mysql_connect($this->dbhost, $this->dbuser, $this->dbpass);
    if (!$this->connection){
		    die('Not connected : ' . mysql_error());
    }

		$db_selected = mysql_select_db($this->database, $this->connection);
		if (!$db_selected)
		    die ('Can\'t use '.$this->database.' : ' . mysql_error());

	}

  function __destruct(){
    $this->connection = NULL;
  }

	public function setQuery($stmt){
		$this->query = $stmt;
	}

	public function executeQuery($q = ""){
    if(!(empty($q)))
      $this->query = $q;

		if(!empty($this->query))
			return mysql_query($this->query);
		else
			throw new Exception("query is empty");
	}

  public function getRows($q = ""){
    // Formulate Query
    // This is the best way to perform an SQL query
    // For more examples, see mysql_real_escape_string()
    if(!(empty($q)))
      $this->query = sprintf($q);

    // Perform Query
    $result = mysql_query($this->query);

    // Check result
    // This shows the actual query sent to MySQL, and the error. Useful for debugging.
    if (!$result) {
        $message  = 'Invalid query: ' . mysql_error() . "\n";
        $message .= 'Whole query: ' . $this->query;
        die($message);
    }

    // Use result
    // Attempting to print $result won't allow access to information in the resource
    // One of the mysql result functions must be used
    // See also mysql_result(), mysql_fetch_array(), mysql_fetch_row(), etc.
    $resultArray = array();
    while(($resultArray[] = mysql_fetch_assoc($result)) || array_pop($resultArray));

    // Free the resources associated with the result set
    // This is done automatically at the end of the script
    mysql_free_result($result);
    $result = NULL;
    return $resultArray;

  }


	public function getRow($q = ""){
		if(!(empty($q)))
		$this->query = sprintf($q);

		$result = mysql_query($this->query);

		if (!$result) {
			$message  = 'Invalid query: ' . mysql_error() . "\n";
			$message .= 'Whole query: ' . $this->query;
			die($message);
		}

		$resultArray = array();
		while(($resultArray[] = mysql_fetch_assoc($result)) || array_pop($resultArray));

		mysql_free_result($result);
		$result = NULL;
		return $resultArray[0];

	}

  public function insert($tableName, $toInsert){
    if(!empty( $toInsert[0] ) && is_array($toInsert[0])){
      $rezArray = array();
      foreach($toInsert as $k=>$v){
        $insertQuery = sprintf("INSERT INTO %s(%s) VALUES('%s')",$tableName, implode(',', array_keys($v)), implode("','", array_values($v)));
		 mysql_query($insertQuery);
        $rezArray[] = mysql_insert_id();
      }
      return $rezArray;
    }else{
      $insertQuery = sprintf("INSERT INTO %s(%s) VALUES('%s')",$tableName, implode(",", array_keys($toInsert)), implode("','", array_values($toInsert)));
      mysql_query($insertQuery);
	  return mysql_insert_id();
    }
  }



}











?>
