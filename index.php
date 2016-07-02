<?php
/**
 * @file Test Page
 * @description Page to test functionality of php-base-library.
 */

// ----------------------- PHP BASE LIBRARY SETUP ---------------------------
// PBL Setup Sequence



// --------------------------------------
// Configuration File
// --------------------------------------
include_once "config.php";			// Configuration Variables



// --------------------------------------
// Essential Classes
// --------------------------------------
include_once $CONFIG["PATH"]["LOG_CLASS"];
include_once $CONFIG["PATH"]["APP_CLASS"];
include_once $CONFIG["PATH"]["SESSION_CLASS"];
include_once $CONFIG["PATH"]["MAILER_CLASS"];
include_once $CONFIG["PATH"]["SUBPAGE_CLASS"];
include_once $CONFIG["PATH"]["DATABASE_CLASS"];



// --------------------------------------
// Global Variables
// Note: These variables must be declared as-is, they are used often, in multiple classes.
// --------------------------------------
global $log;		// Log Handler
global $app;		// Application Handler
global $db;			// Database Handler
global $sessions;	// Sessions Handler



// --------------------------------------
// Setup Engines
// -------------------------------------- 
$log = new Log( LOG_CONSOLE );
$db = SetupDatabase( DB::MySQLi );
$sessions = new SessionEngine();





// -------------------------- PBL APPLICATION -------------------------------
// App-Specific Code Belongs Here
// Application always needs to be assigned to $app variable.




// --------------------------------------
// Run Application
// --------------------------------------
include_once $CONFIG["APPLICATION"];


$app->Setup();		// Ignition
$app->Run();		// Liftoff
$app->Close();		// Landing






// ----------------------- PHP BASE LIBRARY CLOSE ---------------------------
// PBL Closing Sequence




// Close Sequence
$sessions->Close();
$db->Disconnect();
$log->Close();

?>

