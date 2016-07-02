<?php
/**
 * Test Sub Page
 */
 
class TestPage extends SubPage
{
	public function Setup()
	{
		global $log;
		
		$log->Print( "TestPage completed Setup" );
		return;
	}
	
	
	public function Run() { return; }
	public function Close() { return; }
}




?>