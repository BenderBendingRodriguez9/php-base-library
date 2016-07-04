<?php
/**
 * Default Sub Page
 */
 
class DefaultPage extends SubPage
{
	public function Setup()
	{
		global $log;
		
		$log->Print( "DefaultPage completed Setup" );
		return;
	}
	
	
	public function Run() { return; }
	public function Close() { return; }

	public function HTMLContent()
	{
		printf( "
			<b>Content</b>
		" );
	}
}




?>