<?php

/**
 * @file MySQLi Database Object
 * @description Wrapper for MySQLi library, using object oriented method. Since were using OO style, there is little (almost no) need to expand it further (as opposed to classic procedural).
 * @see mysqli_result object (PHP Manual) when using MySQLiDatabase::Query.
 */


class MySQLiDatabase extends Database
{
	// Connection Variables
	private $host;				// (String)		Host/Location of DB Server
	private $user;				// (String)		Username for DB Server (if applicable)
	private $pass;				// (String)		Password for DB Server (if applicable)
	public $database;			// (String)		Name of current database (or default database)
	
	public $link;				// (Resource)	Link/Stream/Handler for Database
	
	
	// Abstract Methods
	
	
	public function __construct()
	{
		// Auto Grab Auth Details
		$this->GetAuthDetails();
		
		// Attempt Connection
		$this->Connect( $this->host, $this->user, $this->pass, $this->database );
		
		return;
	}
	
	
	
	public function __destruct() { return; }
	
	
	
	
	/**
	 * @brief Close MySQLi Connection
	 * @return  
	 */
	public function Disconnect()
	{
		global $log;
		
		if ( $this->link && !$this->link->connect_error ) 
		{
			$this->link->close();
			$log->print( "MySQLi Connection Closed" );
			$this->link = false;
		} else $log->print( "MySQLi Link has already been closed.", WARN_PREFIX );
		
		return;
	}
	
	
	
	
	public function Connect( string $p_host, string $p_user, string $p_pass, string $p_dbname)
	{
		global $log;
		$log->print( "Attempting to establish MySQLi Connection..." );
		
		// New MySQLi Connection Resource
		$this->link = new mysqli( $p_host, $p_user, $p_pass, $p_dbname );
		
		// Check Connection
		if ( $this->link->connect_error ) 
		{
			// Failure
			$log->error( "MySQLiDatabase::Connect", "(" . $this->link->connect_errno . ") " . $this->link->connect_error );
			return false;
		}
		
		// Connection Success
		$log->print( "MySQLi Connection Established" );
		
		// Validate Database
		if ( !$this->Validate( $this->link ) )
		{
			// Validation Failed
			$log->error( "MySQLiDatabase::Connect", "Database validation failed." );
			$this->Disconnect();
			return false;
		}
		
		return true;
	}
	
	
	
	/**
	 * @brief Run Query on DB
	 * @param string $p_query 
	 * @return (bool|mysqli_result) Returns FALSE on failure. For successful SELECT, SHOW, DESCRIBE or EXPLAIN queries will return a mysqli_result object. For other successful queries MySQLiDatabase::Query will return TRUE. 
	 */
	public function Query( string $p_query )
	{
		global $log;
		
		$result = $this->link->query( $p_query );
		
		if ( !$result ) 
		{
			$log->error( "MySQLiDatabase::Query", $this->link->error );
			return false;
		}
		
		return $result;
	}
	
	
	
	
	
	public function SelectDB( string $p_dbname )
	{
		global $log;
		
		// Select New DB
		$this->link->select_db( $p_dbname );
		
		// Error Checking
		if ( $result = $this->Query( "SELECT DATABASE() " ) )
		{
			$row = $result->fetch_row();
			if ( $row[0] !== $p_dbname )
			{
				$log->error( "MySQLiDatabase::SelectDB", "Current database name does not match change, indicating failure to select database." );
				return false;
			}
		}
		
		$this->database = $row[0];
		$log->print( "Database was changed to: " . $this->database . "." );
		$result->close();
		
		return true;
	}
	
	
	/**
	 * @brief Get Number of Rows returned from Query
	 * @param mysqli_result $p_resource 
	 * @return Num Rows
	 * @depreciated
	 * @see mysqli_result object (returned by this->Query)
	 */
	public function GetNumRows( $p_resource )
	{
		return 0;
	}
	
	
	
	
	// MySQLiDatabase Specific
	
	
	
	/**
	 * @brief Auto-Assign Auth Details from Configuration to this->
	 * @return void  
	 */
	public function GetAuthDetails()
	{
		global $CONFIG;
		
		$this->host = $CONFIG["DB"]["HOST"];
		$this->user = $CONFIG["DB"]["USER"];
		$this->pass = $CONFIG["DB"]["PASS"];
		$this->database = $CONFIG["DB"]["DATABASE"];
		
		return;
	}
}



?>