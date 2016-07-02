<?php

/**
 * @file (Inheritable) Database Object
 * @description
 * 
 */


// Database Type Definitions

class DB
{
	const FlatFile = 0;
	const MySQL = 1;		// Depreciated extension in PHP 7
	const MySQLi = 2;
	const Excel = 3;
}


// Database Configuration table Types
define( "TYPE_INT", 0 );
define( "TYPE_BOOL", 1 );
define( "TYPE_STRING", 2 );


// Inheritable Database Class
abstract class Database
{
	// Connection Variables
	private $host;				// (String)		Host/Location of DB Server
	private $user;				// (String)		Username for DB Server (if applicable)
	private $pass;				// (String)		Password for DB Server (if applicable)
	public $database;			// (String)		Name of current database (or default database)
	
	public $link;				// (Resource)	Link/Stream/Handler for Database
	
	// Abstract Methods (v1)
	
	abstract public function __construct();
	abstract public function __destruct();
	
	abstract public function Connect( string $p_host, string $p_user, string $p_pass, string $p_dbname);
	abstract public function Disconnect();
	abstract public function Query( string $p_query );
	abstract public function SelectDB( string $p_dbname );
	abstract public function GetNumRows( $p_resource );
	
	// Common Methods
	
	/**
	 * @brief Check if table exists
	 * @param string $table_name Name of table.
	 * @param resource $link Link to connection 
	 * @param bool $using_sql If not using SQL method cannot check.
	 * @return bool
	 */
	public function TableExists( string $table_name, $link, bool $using_sql = true )
	{
		// TODO: Database::TableExists: Add Non SQL Support
		if ( !$using_sql ) return true;
		
		$result = $this->Query( "SHOW TABLES LIKE '" . $table_name . "';" );
		
		if ( isset( $result->num_rows ) ) return $result->num_rows > 0 ? true : false;
		else return false;
	}
	
	
	
	/**
	 * @brief Checks to make sure database has PBL required tables (only works with MySQL type databases).
	 * @param mysqli_connection Link to database connection.
	 * @return bool Will return false if database is not configured/installed properly for PBL.
	 */
	public function Validate( $link )
	{
		global $CONFIG;
		global $log;
		
		$error = false;
		
		foreach ( $CONFIG["PBL"]["TABLES"] as $table )
		{
			if ( !$this->TableExists( $CONFIG["DB"]["TABLE_PREFIX"] . $table, $link ) )
			{
				$log->error( "Database::Validate", "Required table " . $CONFIG["DB"]["TABLE_PREFIX"] . $table . " does not exist." );
				$error = true; // Keep Checking
			}
		}
		
		// Failure?
		if ( $error ) return false;
		
		// Success
		return true;
	}
	
	

}


/**
* @brief Setup Database Connection
* @param const int $const_db_type (See DB:: class) 
* @return void
*/
function SetupDatabase( $const_db_type )
{
	global $log;
	global $CONFIG;
	
	switch ( $const_db_type )
	{
		case DB::FlatFile:
			$log->error( "SetupDatabase", "FlatFile database support is not Implemented" );
			break;
		
		case DB::MySQL:
			include_once( $CONFIG["PATH"]["MYSQL_CLASS"] );
			return new MySQLDatabase();
			break;
			
		case DB::MySQLi:
			include_once( $CONFIG["PATH"]["MYSQLI_CLASS"] );
			return new MySQLiDatabase();
			break;
		
		case DB::Excel:
			$log->error( "SetupDatabase", "Excel database support is not Implemented" );
			break;
	}
	
	return;
}



?>