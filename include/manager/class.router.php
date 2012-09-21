<?php

class Router
{
	private static $base_url = '';
	
	private static $files = array();
	
	private static $managers = array();
	
	public static function setBaseUrl($base) {
		self::$base_url = $base;
	}
	
	public static function addSection($identifier, $file, $manager = '') {
		self::$files[$identifier] = $file;
		self::$managers[$identifier] = $manager;
	}
	
	public static function getSection($identifier) {
		$result = new stdClass();
		$result->file = self::$files[$identifier];
		$result->manager = self::$managers[$identifier];
	}
	
	public static function enroute() {
		$requestURI = explode('/', $_SERVER['REQUEST_URI']);
		$scriptName = explode('/',$_SERVER['SCRIPT_NAME']);
		 
		for($i= 0; $i < sizeof($scriptName); $i++)
		{
			if ($requestURI[$i]     == $scriptName[$i])
			{
				unset($requestURI[$i]);
			}
		}
		
		$command = array_values($requestURI);
		$section = self::getSection($command[0]);
		
		include_once $section->file;
		if($section->manager != '') {
			$manager = $section->manager;
			MoonDragon::run(new $manager());
		}
	}
}