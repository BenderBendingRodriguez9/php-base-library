<?php

global $CONFIG;

// --------------------------------------
// PHP Error Reporting
// --------------------------------------
error_reporting( E_ALL );


// --------------------------------------
// Application
// --------------------------------------
$CONFIG["APPLICATION"] = "testapp.php";			// Path to Application Implementation


// --------------------------------------
// Sessions
// --------------------------------------
$CONFIG["SESSIONS"]["ENABLED"] = true;			// Using Sessions?


// --------------------------------------
// Timezone
// --------------------------------------
$CONFIG["TIMEZONE"] = "America/Chicago";


// --------------------------------------
// File Paths & Directories
// --------------------------------------
$CONFIG["PATH"]["LOG_CLASS"] = "classes/log.php";
$CONFIG["PATH"]["APP_CLASS"] = "classes/application.php";
$CONFIG["PATH"]["SESSION_CLASS"] = "classes/sessions.php";
$CONFIG["PATH"]["DATABASE_CLASS"] = "classes/database.php";
$CONFIG["PATH"]["MYSQLI_CLASS"] = "classes/mysqli.php";
$CONFIG["PATH"]["MYSQL_CLASS"] = "classes/mysql.php";
$CONFIG["PATH"]["MAILER_CLASS"] = "classes/mailer.php";
$CONFIG["PATH"]["SUBPAGE_CLASS"] = "classes/subpage.php";


// --------------------------------------
// Database Configuration
// --------------------------------------
// NOTE: These variables MUST BE DECLARED when using any database type, even if they are not applicable.
$CONFIG["DB"]["HOST"] = "localhost";			// Server/File address or location.
$CONFIG["DB"]["USER"] = "root";					// Username for DB Server (if applicable)
$CONFIG["DB"]["PASS"] = "";						// Password for DB Server (if applicable)
$CONFIG["DB"]["DATABASE"] = "pbl_app";			// Database to start with.
$CONFIG["DB"]["TABLE_PREFIX"] = "pbl_";			// Prefix for database tables.


// --------------------------------------
// Log Class Variables
// --------------------------------------
$CONFIG["LOG"]["ERRORS"] = true;				// Print errors to output, regardless of CONFIG[LOG][ENABLED] status.
$CONFIG["LOG"]["ENABLED"] = true;				// Enable Logging for Printing/Writing
$CONFIG["LOG"]["HTML_FORMAT"] = true;			// Use HTML formatting in Log class (opposed to C style console output.)
$CONFIG["LOG"]["FOPEN_MODE"] = "w";				// Sets mode to open log file stream (using fopen).



// --------------------------------------
// PBL Database Tables
// --------------------------------------
$CONFIG["PBL"]["TABLES"][0] = "configurations";

?>