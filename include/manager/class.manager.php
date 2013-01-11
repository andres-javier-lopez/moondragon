<?php

/**
 * @brief Clase para manejo de procesos
 *
 * @todo agregar nueva función para obtener parámetros
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
 * @ingroup Manager
 */

abstract class Manager implements Runnable{

	/**
	 * Arreglo con las secciones de la llamada de la ruta
	 * @var array $call
	 */
	protected $call = array();

	/**
	 * Ruta asociada con el manager
	 * @var string $manager_url
	 */
	protected $manager_url = '';

	/**
	 * Expresión regular que se compara con la ruta del manager
	 * @var string $match_url
	 */
	protected $match_url = '';

	/**
	 * Variable de control para comprobar si se ejecuto el constructor
	 * @var boolean
	 */
	private $ready = false;

	/**
	 * Inicializa el manager asociado a una ruta específica
	 * @param string $match_url
	 */
	public function __construct($match_url = '') {
		$this->ready = true;
		if($match_url != '') {
			if(!defined('CLEAN_URL')) {
				define('CLEAN_URL', true);
			}
			$this->manager_url = $match_url;
			$this->match_url = '/'.str_replace('/', '\/', $match_url).'(\/.*)?/i';
		}
	}

	/**
	 * Tarea que se ejecuta por defecto
	 * @return void
	 */
	public abstract function index();
	 
	/**
	 * Pone en ejecución el manager
	 * @return void
	 * @throws ManagerException
	 * @throws TaskException
	 */
	public function run() {
		assert('$this->ready; /* Run default constructor */');
		$task = $this->getTask();
		$this->$task();
	}

	/**
	 * Si se intenta llamar una tarea que no esta definida, se dispara una excepción.
	 * Metodo mágico.
	 * @param string $method Nombre de la tarea
	 * @param array $params
	 * @return void
	 * @throws TaskException
	 */
	public function __call($method, $params) {
		throw new TaskException();
	}

	/**
	 * Redirige el manager hacia una nueva tarea
	 * @param string $task
	 * @return void
	 */
	protected function doTask($task) {
		if(defined('CLEAN_URL') && CLEAN_URL == true) {
			assert('CLEAN_URL');
			if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
				$protocol = 'https://';
			}
			else {
				$protocol = 'http://';
			}
			assert(isset($_SERVER['HTTP_HOST']));
			$redirection = $_SERVER['HTTP_HOST'].'/'.$this->manager_url.'/'.$task;
			$redirection = $protocol.str_replace('//', '/', $redirection);
		}
		else if(isset($_SERVER['PATH_INFO'])){
			assert('!CLEAN_URL');
			$dir = str_replace($_SERVER['PATH_INFO'], '', $_SERVER['PHP_SELF']);
			$redirection = $dir.'/'.$task;
			$redirection = str_replace('//', '/', $redirection);
		}
		else {
			assert('!CLEAN_URL && !isset($_SERVER["PATH_INFO"])');
			$redirection = '?task='.$task;
		}
		 
		assert('isset($redirection) && $redirection != ""');
		MoonDragon::redirect($redirection);
	}


	/**
	 * Obtiene la tarea activa
	 * @return string
	 * @throws ManagerException
	 */
	protected function getTask() {
		$task = '';
		 
		if(defined('CLEAN_URL') && CLEAN_URL == true) {
			assert('CLEAN_URL');
			if(isset($_SERVER['REQUEST_URI']) && $this->match_url != '') {
				$uri = $_SERVER['REQUEST_URI'];
				if(preg_match($this->match_url, $uri, $matches) == 1){
					assert('count($matches) > 0');
					if(isset($matches[1])){
						$pos = strpos($matches[1], '?');
						if($pos !== false) {
							$string = substr($matches[1], 0, $pos);
							$task = $this->loadParams($string);
							assert('$string == "/" || $task != ""');
						}
						else {
							$task = $this->loadParams($matches[1]);
							assert('$matches[1] == "/" || $task != ""');
						}
							
					}
				}
				else {
					throw new ManagerException();
				}
			}
		}
		else if(isset($_SERVER['PATH_INFO'])) {
			$pathinfo = $_SERVER['PATH_INFO'];
			$task = $this->loadParams($pathinfo);
			assert('$pathinfo == "/" || $task != ""');
		}
		 
		if($task == '' && isset($_GET['task'])) {
			$task = $_GET['task'];
		}
		 
		if($task != '') {
			return $task;
		}
		else {
			return 'index';
		}
	}

	/**
	 * Procesa los parámetros recibidos en la URL
	 * @return string
	 */
	protected function loadParams($params) {
		$params = trim($params, '/');
		if($params != '') {
			$this->call = explode('/', $params);
			assert('count($this->call) > 0');
			return $this->call[0];
		}
		else {
			return '';
		}
	}
}

/**
 * @brief Alias para la clase Manager
 *
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
 * @ingroup Manager
 */
abstract class Controller extends Manager {}

// Fin de archivo
