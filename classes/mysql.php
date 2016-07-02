<?php
/**
 * @file MySQL
 * @description Wrapper for MySQL library, using procedural style programming.
 * @note Procedural MySQL support is DEPRECIATED and no longer used in PHP 7.0 >
 */


class MySQLDatabase extends Database
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
		global $log;
		$log->error( "MySQLDatabase::__construct", "Original procedural MySQL support is depreciated as of PHP 7. Use MySQLi instead." );
		
		return false;
	}
	
	
	public function __destruct() { return; }
	public function Disconnect() { return; }
	public function Connect( string $p_host, string $p_user, string $p_pass, string $p_dbname) { return false; }
	public function Query( string $p_query ) { return false; }
	public function SelectDB( string $p_dbname ) { return false; }
	public function GetNumRows( $p_resource ) { return 0; }
	
	
	
	
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