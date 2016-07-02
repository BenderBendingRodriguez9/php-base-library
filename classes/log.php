<?php

/**
 * @file Log
 * @description Class used to send output to console/file.
 * 
 * @usage
 * $log = new log();		Log to file by default.
 */
 
 
// Includes
include_once "config.php";


// Definitions
define( "LOG_CONSOLE", 0 );
define( "LOG_FILE", 1 );

// Print Arguments
define( "NO_PREFIX", 101 );
define( "WARN_PREFIX", 102 );


class Log
{
	private $run_id;			// Psuedo-Random Running Session ID (for easier log viewing)
	
	private $method;			// Method of Logging
	private $filename;			// Filename for file log
	private $stream;			// File Stream for writing
	
	public $num_errors;			// Integer, error count for run.
	
	
	
	
	/**
	 * @brief Log Constructor
	 * @param constant int $p_method (LOG_CONSOLE or LOG_FILE)
	 * @param string $p_file 
	 * @return bool
	 */
	public function __construct( $p_method = LOG_FILE, $p_file = "log.txt" )
	{
		global $CONFIG;
		
		// Setup Unique Run ID and Reset Error Counter
		$this->run_id = random_int( 10, 99 );
		$this->num_errors = 0;
		
		// If Log is disabled, no further processing required.
		if ( !$CONFIG["LOG"]["ENABLED"] ) return false;
		
		// Check validity of $p_file
		if ( $p_method == LOG_FILE && ( !is_string( $p_file ) || strlen( $p_file ) == 0 ) )
		{
			$this->error( "log::__construct", "Log requires valid string for filename." );
			return false;
		}
		
		// Assignments
		$this->method = $p_method;
		$this->filename = $p_file;
		
		if ( $this->method == LOG_CONSOLE ) $this->print( "Log Started (Console Mode) (RID: " . $this->run_id . ")" );
		elseif ( $this->method == LOG_FILE )
		{
			$this->stream = fopen( $this->filename, $CONFIG["LOG"]["FOPEN_MODE"] );
			
			// Validate
			if ( !$this->stream )
			{
				// $this->error( "log::__construct", "Failed to open log file for writing using fopen(\"" . $this->filename . "\", a)." );
				// Trigger Error since log::error cannot write error to file.
				trigger_error( "log::__construct : failed to open stream for writing.", E_USER_ERROR );
				return false;
			}
			
			// Ready for Writing
			$this->print( "Log Started" );
		}
		else 
		{
			// Unknown Method
			$this->error( "log::__construct", "Log started with unknown method." );
			return false;
		}
		
		// Success
		return true;
	}
	
	
	
	
	
	public function print( $string, $args = null )
	{
		global $CONFIG;
		
		if ( !$CONFIG["LOG"]["ENABLED"] ) return false;
		
		// Which Format?
		$format_prefix;
		$format;
		
		// Fomrat Arguments
		switch ( $args )
		{
			case WARN_PREFIX:
				$format_prefix = "[" . $this->run_id . "] <b>[WARNING]</b>: ";
				break;
				
			case NO_PREFIX:
				$format_prefix = "";
				break;
				
			case null:
				$format_prefix = "[" . $this->run_id . "]";
				break;
				
			default:
				$format_prefix = "[" . $this->run_id . "]";
		}

		
		if ( $CONFIG["LOG"]["HTML_FORMAT"] ) $format = $format_prefix . " " . $string . "<br />";
		else $format = $format_prefix . " " . $string . "\r\n";
		
		// Output Source
		if ( $this->method == LOG_CONSOLE ) printf( $format );
		elseif ( $this->method == LOG_FILE ) 
		{
			// Print to File
			fwrite( $this->stream, $format );
			
			// Successful File Write
			return true;
		}
		else
		{
			$this->error( "log::print", "Unknown logging method." );
			return false;
		}
		
		return true;
	}
	
	
	
	
	
	/**
	 * @brief New Line without Prefix
	 * @return  
	 */
	public function nl() { $this->print( "", NO_PREFIX ); }
	
	
	
	
	
	/**
	 * @brief Print error message unless specified.
	 * @param string $function
	 * @param string $string 
	 * @return void
	 */
	public function error( $function, $string )
	{
		global $CONFIG;
		
		// Errometer
		$this->num_errors++;
		
		// Print Errors?
		if ( $CONFIG["LOG"]["ERRORS"] )
		{
			// Which Format?
			$format;
			
			if ( $CONFIG["LOG"]["HTML_FORMAT"] ) $format = "<br />[" . $this->run_id . "] <b>[ERROR]</b>: " . $function . ": " . $string . "<br /><br />";
			else $format = "\r\n[" . $this->run_id . "] [ERROR] " . $function . ": " . $string . "\r\n\r\n";
			
			printf( $format );
		}
		
		// Write to Log File additionaly (if LOG_FILE is enabled)
		if ( $this->method == LOG_FILE && $this->stream !== false )
		{
			// Formatting
			if ( !$CONFIG["LOG"]["HTML_FORMAT"] ) fwrite( $this->stream, "[" . $this->run_id . "] [ERROR] " . $function . ": " . $string . "\r\n" );
			else fwrite( $this->stream, "[" . $this->run_id . "] <b>[ERROR]</b> " . $function . ": " . $string . "<br />" );
		}
		
		return;
	}
	
	
	
	
	
	/**
	 * @brief Additional Closing Cleanup
	 * @return void
	 */
	public function Close() { return; }
	
	public function __destruct()
	{
		global $CONFIG;

		$line_break;
		
		if ( $CONFIG["LOG"]["HTML_FORMAT"] ) $line_break = "<br /><br />";
		else $line_break = "\r\n\r\n";
		
		$this->print( "End of Log (" . $this->num_errors . " errors)" . $line_break );
		
		// Close File Stream
		if ( $this->method == LOG_FILE )
		{
			fclose( $this->stream );
			return;
		}
	}
}

?>