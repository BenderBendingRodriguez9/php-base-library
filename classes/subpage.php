<?php
/**
 * Abstract SubPage Class
 */

abstract class SubPage
{
	abstract public function Setup();
	abstract public function Run();
	abstract public function Close();
	abstract public function HTMLContent();
}


?>