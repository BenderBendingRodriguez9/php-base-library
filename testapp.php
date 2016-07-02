<?php 
/**
 * Test Application for PBL
 */

// --------------------------------------
// Application Structure Setup
// --------------------------------------

global $CONFIG;
include_once $CONFIG["PATH"]["APP_CLASS"];

global $app;





// --------------------------------------
// Page Definitions
// --------------------------------------
class Page
{
	const Index = "index.php";
}



// --------------------------------------
// SupPage Values (url param 'page')
// --------------------------------------
class SubPages
{
	const Index = "default";
	const TestPage = "test";
}




// --------------------------------------
// Session Definitions
// --------------------------------------
class Session
{
	const ID = "SID";		// Session ID
}






// --------------------------------------
// TestApplication Implementation
// --------------------------------------

class TestApplication extends Application
{
	// Variables
	public $AppTitle = "PBL Test Application";
	
	public $CurrentPage;		// Current Filename
	public $CurrentSubPage;		// String value of $_GET["page"]
	public $SubPage;			// Handler for SubPage class
	
	
	
	/**
	 * @brief Setup Required Sessions (for CurrentPage)
	 * @return 
	 */
	public function SessionRequirements()
	{
		global $sessions;
		global $log;
		

		// ----------------------------------------
		// Define Required Sessions
		// --------------------------------------
		// Example: $sessions->Require("LoggedIn", "TestApplication::Test");
		
		switch ( $this->CurrentPage )
		{
			
			
			
			// --------------------------------------
			// Index.php & Sub Page's Required Sessions
			// --------------------------------------
			
			case Page::Index:
					switch( $this->GetCurrentSubPage() )
					{
						
						case SubPages::Index:
							break;
						
						
						case SubPages::TestPage:
							break;
					}
					
				break;
				
				
			
			
		}
	
		
		// --------------------------------------
		// Required Sessions for ALL PAGES
		// --------------------------------------
		
		$sessions->Require( Session::ID, $sessions->Create( Session::ID, session_id() ) );		// Required SID, and create (as callback) if doesn't exist.
		
		
		
		
		return;
		
	}
	

	/**
	 * @brief Application Setup Sequence (SETUP->Run->Close)
	 * @return int Error Code
	 */
	public function Setup() 
	{
		global $log;
		global $db;
		global $sessions;
		
		$log->nl();
		
		// Re-assign Variables for some reason
		$this->CurrentPage = $this->GetCurrentPage();			// CurrentPage is the filename, defined in Page class.
		$this->CurrentSubPage = $this->GetCurrentSubPage();		// CurrentSubPage is the string name of the child page.
		
		
		// Session Requirements
		$this->SessionRequirements();
		
		
		// Setup Completed Successfully
		$log->print( $this->AppTitle . " Started (" . $this->DateToString() . " " . $this->TimeToString() . ")" );
		$log->print( "CurrentPage value: " . $this->CurrentPage );
		$log->print( "CurrentSubPage value: " . $this->GetCurrentSubPage() );
		$log->print( "Session ID: " . $sessions->Get( Session::ID ) );
		
		
		
		// --------------------------------------
		// Sub Page Setup
		// --------------------------------------
		
		switch( $this->CurrentPage )
		{
			
			
			// --------------------------------------
			//  (index.php) Sub Page's
			// --------------------------------------
			case Page::Index:
			
				switch( $this->CurrentSubPage )
				{
					
					
					// --------------------------------------
					// Default Sub Page (none declared or default)
					// --------------------------------------
					case SubPages::Index:
						include_once( "pages/default.php" );
						$this->SubPage = new DefaultPage;
						break;
					
					
					// --------------------------------------
					// Test Sub Page
					// --------------------------------------
					case SubPages::TestPage:
						include_once( "pages/test.php" );	// Include
						$this->SubPage = new TestPage;		// Declare
						break;
						
					
					// --------------------------------------
					// Invalid Sub Page
					// --------------------------------------
					default:
						$log->Error( "TestApplication::Setup", "Invalid SubPage URL" );
						include_once( "pages/default.php" );	// Include
						$this->SubPage = new DefaultPage;		// Declare
						break;
				}
				
				
				
			break;
			
			
		}
		
		
		// Setup SubPage
		$this->SubPage->Setup();
		
		
		return 0; 
	}
	
	
	/**
	 * @brief Application Run Sequence (Setup->RUN->Close)
	 * @return int Error Code
	 */
	public function Run() 
	{
		global $log;
		global $sessions;
		
		
		// Validate Sessions
		$sessions->Validate();
		
		$this->SubPage->Run();
		
		// Run Completed Successfully
		$log->print( "Application Run Sequence Completed" );
		return 0; 
	}
	
	
	/**
	 * @brief Application Close Sequence (Setup->Run->CLOSE)
	 * @return int Error Code
	 */
	public function Close() 
	{
		global $log;
		
		$this->SubPage->Close();
		
		// Close Completed Successfully
		$log->print( "Application Closed Successfully" );
		$log->nl();
		
		return 0;
	}
	
	
	
	
	
	
} $app = new TestApplication();



?>
