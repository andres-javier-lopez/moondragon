<?php

class MoonDragon
{
	public static function run(Runnable $object) {
		try {
			$object->run();
		}
		catch(Status404Exception $e) {
			if(!headers_sent()) {
				header('HTTP/1.0 404 Not Found');				
				header('Status: 404 Page Not Found');
				echo $e->show404();
			}
		}
	}
}