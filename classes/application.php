<?php

/**
 * @file Application
 * @description Library used to wrap/handle an application.
 * @usage When creating a new application, it should be contained within its own class, extending on Application.
 * The application class primarily contains common methods and infrastructure for actual application.
 */
 
define( "DATE_DEFAULT", 0 );			// Date String (Common)
define( "DATE_ALT", 1 );				// Date String (Alternate)
define( "TIME_NOW", 0 );				// Function argument for time()

define( "STATUS_SUCCESS", 0 );			// App Sequence Success
define( "STATUS_FAILED", 1 );			// App Sequence Failed


abstract class Application
{
	
	
	// Abstract Methods
	
	/**
	 * @brief Application setup processing.
	 * @return int User defined setup status.
	 */
	abstract public function Setup();
	
	/**
	 * @brief This function contains the main processing.
	 * @return int User defined run status.  
	 */
	abstract public function Run();
	
	/**
	 * @brief Cleanup and Close Application
	 * @return void
	 */
	abstract public function Close();
	
	
	
	
	// Common Methods
	
	
	
	
	/**
	 * Constructor
	 * @return  
	 */
	public function __construct()
	{
		global $CONFIG;
		
		// Timezone
		date_default_timezone_set( $CONFIG["TIMEZONE"] );
		
		
		return true;
	}
	
	
	/**
	 * @brief Returns time in string format.
	 * @param int $p_timestamp Timestamp (default is time())
	 * @return string Time
	 */
	public function TimeToString( int $p_timestamp = TIME_NOW )
	{
		$time;
		
		if ( $p_timestamp == TIME_NOW ) $time = time();
		else $time = $p_timestamp;
		
		return date( "g:i a", $time );
	}
	
	
	/**
	 * @brief Get common date in string format.
	 * @param int $p_format Format version (default is DATE_DEFAULT) (DATE_DEFAULT, DATE_ALT)
	 * @param int $p_timestamp Timestamp to convert to date string (default is current time [TIME_NOW])
	 * @return  
	 */
	public function DateToString( int $p_format = DATE_DEFAULT, int $p_timestamp = TIME_NOW )
	{
		// Variables
		$date;
		$time;
		
		// Timestamp
		if ( $p_timestamp == TIME_NOW ) $time = time();
		else $time = $p_timestamp;
		
		// Format
		switch ( $p_format )
		{
			// Default (Longish)
			case DATE_DEFAULT:
				$date = date( "F j, Y", $time );
				break;
			
			// Alternate (Short)
			case DATE_ALT:
				$date = date( "m/d/y", $time );
				break;
		}
		
		return $date;
	}
	
	
	/**
	 * @brief Get Current Page's Filename
	 * @return string Filename
	 */
	public function GetCurrentPage()
	{
		return substr( $_SERVER["SCRIPT_NAME"], strrpos($_SERVER["SCRIPT_NAME"],"/")+1 );
	}
	
	
	/**
	 * @brief Get current page based on 'page' parameter in url.
	 * @return mixed (value of $_GET["page"])
	 */
	public function GetCurrentSubPage()
	{
		// If 'page' url parameter is not set, return (string)default.
		if ( !isset( $_GET["page"] ) )
			return "default";
		
		return $_GET["page"];
	}
	
	
	public function __destruct() { return; }
}


/**
 * @brief Empty callback function, normally used as a default parameter value for functions having a optional callback parameter.
 * @return null
 */
function empty_callback() { return null; }


?>