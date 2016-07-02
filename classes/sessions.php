<?php
/**
 * Session Engine
 */
 

class SType
{
	const Normal = 0;
	const Required = 1;
	const Temp = 2;
}


class SessionEngine
{
	// Variables
	public $enabled;			// (bool) Value from $CONFIG["SESSIONS"]["ENABLED"]
	
	public $required_sessions;	// (array) Contains keys for sessions that must be required. Format: $required_sessions[3][KEY] = "MySession3", $required_sessions[3][CALLBACK] = "MyCallback3"
	
	/**
	 * @brief Get Session Value by Key
	 * @param string|int $p_key 
	 * @return mixed Session value
	 */
	public function Get( $p_key )
	{
		if ( $this->Exists( $p_key ) )
			return $_SESSION[$p_key];
		else return null;
	}
	
	/**
	 * @brief Create Session
	 * @param string|int $p_key Key or Identifier
	 * @param mixed $p_value Initial value of session.
	 * @param bool $overwrite Overwrite session if exists?
	 * @return bool
	 */
	public function Create( $p_key, $p_value, bool $overwrite = false )
	{
		global $log;
		
		if ( !$this->enabled ) return false;
		if ( isset( $_SESSION[$p_key] ) && !$overwrite ) return false;
		

		$_SESSION[$p_key] = $p_value;
		
		return true;
	}
	
	
	public function Require( $p_key, $p_callback )
	{
		if ( !$this->enabled ) return false;
		
		// Push New Requirement into required_sessions stack.
		$this->required_sessions[] = array( "KEY" => $p_key, "CALLBACK" => $p_callback );
		
		return true;
	}
	
	
	public function Validate()
	{
		if ( !$this->enabled ) return false;
		
		// Loop required_sessions array, run callback function if session is not set.
		foreach( $this->required_sessions as $session ) 
		{
			if ( !$this->Exists( $session["KEY"] ) )
			{
				call_user_func( $session["CALLBACK"] );
				return;
			}
		}
		
		return;
	}
	
	
	/**
	 * @brief Constructor
	 * @use Only (and always) call when declaring $sessions new. Setup() still needs to be called afterwards.
	 * @return  
	 */
	public function __construct()
	{
		// Assignments
		global $CONFIG;
		global $log;
		$this->required_sessions = array();
		
		$this->enabled = $CONFIG["SESSIONS"]["ENABLED"];
		
		// Enabled?
		if ( !$this->enabled ) return false;
		
		if ( session_start() ) $log->print( "Session Engine Started" );
		else $log->error( "SessionEngine::__construct", "session_start has returned false." );
		
		return true;
	}
	
	
	/**
	 * @brief Check if session exists
	 * @param string|int $p_key Session name/key. 
	 * @return  
	 */
	public function Exists( $p_key )
	{
		if ( !$this->enabled ) return false;
		
		if ( isset( $_SESSION[$p_key] ) && $_SESSION[$p_key] != null )
			return true;
		else return false;
	}
	
	
	
	/**
	 * @brief Delete Session (unset) if exists.
	 * @param string|int $p_key 
	 * @return  
	 */
	public function Delete( $p_key )
	{
		if ( !$this->enabled ) return false;
		
		// Unset Session
		if ( $this->Exists( $p_key ) )
		{
			unset( $_SESSION[$p_key] );
			
			return true;
		}
		
		// Session didn't exist in first place.
		return false;
	}
	
	
	
	/**
	 * @brief Session Cleanup
	 * @return bool
	 */
	public function Close()
	{
		if ( !$this->enabled ) return false;
		
		
		// TODO: Destroy Temp Sessions
		return true;
	}
}



?>