<?php

// General Exceptions for MoonDragon

class MoonDragonException extends Exception {}

class Status404Exception extends MoonDragonException {
	public function show404() {
		echo '<h1>404 Page Not Found</h1>';
		echo '<p>'.$this->getMessage().'</p>';
	}	
}

class HeadersException extends MoonDragonException {}