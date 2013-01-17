<?php

/**
 * @brief Clase para hacer llamadas a un API RESTful
 *
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
 * @ingroup Caller
 */

class Caller
{
	/**
	 * Dirección del API al que se realizarán las llamadas
	 * @var string $api_url
	 */
	protected $api_url;

	/**
	 * Datos que serán enviados al API
	 * @var string $data
	 */
	protected $data;

	/**
	 * Tipo de datos que serán enviados al API
	 * @var string $data_type
	 */
	protected $data_type;

	/**
	 * Constructor
	 * @param string $url url del API a la que se realizarán las llamadas
	 * @return void
	 */

	public function __construct($url='')
	{
		$this->api_url = $url;
	}

	/**
	 * Asigna la url del API al que se realizarán las llamadas
	 * @param string $url
	 * @return Caller este método se puede encadenar
	 */

	public function setUrl($url) {
		$this->api_url = $url;
		return $this;
	}

	/**
	 * Hace una llamada GET al API
	 * @return string Resultado del API
	 * @throws CallerException
	 * @throws BadApiUrlException
	 * @throws CurlException
	 */

	public function get() {
		$this->checkApiUrl();
		return $this->api_call(GET_CALL);
	}


	/**
	 * Hace una llamada POST al API
	 * @return string Resultado del API
	 * @throws CallerException
	 * @throws BadApiUrlException
	 * @throws EmptyDataException
	 * @throws CurlException
	 */
	public function post() {
		$this->checkApiUrl();
		return $this->api_call(POST_CALL);
	}

	/**
	 * Hace una llamada PUT al API
	 * @return string Resultado del API
	 * @throws CallerException
	 * @throws BadApiUrlException
	 * @throws EmptyDataException
	 * @throws CurlException
	 */
	public function put() {
		$this->checkApiUrl();
		return $this->api_call(PUT_CALL);
	}

	/**
	 * Hace una llamada DELETE al API
	 * @return string resultado del API
	 * @throws CallerException
	 * @throws BadApiUrlException
	 * @throws CurlException
	 */
	public function delete() {
		$this->checkApiUrl();
		return $this->api_call(DELETE_CALL);
	}

	/**
	 * Asigna los datos que serán enviados al API
	 * @param string $data
	 * @param string $type 
	 * @return Caller este método se puede encadenar
	 */
	public function setData($data, $type = DATA_JSON) {
		$this->data = $data;
		$this->data_type = $type;
		return $this;
	}

	/**
	 * Comprueba que la dirección de API existe
	 * @return void
	 * @throws BadApiUrlException
	 */
	protected function checkApiUrl() {
		if($this->api_url == '') {
			throw new BadApiUrlException();
		}
	}

	/**
	 * Se hace la llamada al API con el método específicado
	 * @param string $method El método se define por una constante
	 * @return string Resultado del API
	 * @throws CallerException
	 * @throws EmptyDataException
	 * @throws CurlException
	 */
	protected function api_call($method)
	{
		$curl = curl_init($this->api_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_REFERER, $_SERVER['HTTP_HOST']);

		assert('$method == GET_CALL || $method == POST_CALL || $method == PUT_CALL || $method == DELETE_CALL');
		switch($method) {
			case GET_CALL:
				// No hace nada, los parámetros por defecto
				break;
			case POST_CALL:
				if(is_null($this->data) || is_null($this->data_type)) {
					throw new EmptyDataException();
				}
				curl_setopt($curl, CURLOPT_HEADER, true);
				curl_setopt($curl, CURLOPT_POST, true);
				curl_setopt($curl, CURL_POSTFIELDS, $this->data);
				curl_setopt($curl, CURLOPT_HTTPHEADER, 'Content-type: '.$this->data_type);
				break;
			case PUT_CALL:
				if(is_null($this->data) || is_null($this->data_type)) {
					throw new EmptyDataException();
				}
				curl_setopt($curl, CURLOPT_HEADER, true);
				curl_setopt($curl, CURLOPT_PUT, true);
				curl_setopt($curl, CURLOPT_HTTPHEADER, 'Content-type: '.$this->data_type);

				// use a max of 256KB of RAM before going to disk
				$fp = fopen('php://temp/maxmemory:256000', 'w');
				if (!$fp) {
					throw new CallerException(_('No se pudo abrir archivo temporal'));
				}
				fwrite($fp, $this->data);
				fseek($fp, 0);

				curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
				curl_setopt($ch, CURLOPT_INFILE, $fp); // file pointer
				curl_setopt($ch, CURLOPT_INFILESIZE, strlen($this->data));
				break;
			case DELETE_CALL:
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
				break;
			default:
				assert('false; // Se ha llamado un método incorrecto en el caller');
				throw CallerException(_('Error incomprensible, esto no debería de suceder bajo ninguna circunstancia. Se ha llamado un método incorrecto en el caller.'));
		}

		$result = curl_exec($curl);
		curl_close($curl);

		if($result === false)
		{
			throw new CurlException();
		}

		return $result;
	}
}

// Fin del archivo
