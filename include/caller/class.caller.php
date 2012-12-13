<?php

/**
 * Clase para hacer llamadas a un RESTful API
 *
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
 * @ingroup Caller
 */


class Caller
{
	protected $api_url;
	
	protected $data;
	
	protected $data_type;
        
         /**
	 * Constructor 
          * @param string $url
	 * TODO: nose que hace
          * @return void 
        */
	
	public function __construct($url='')
	{
		$this->api_url = $url;
	}
	
        /**
	 * TODO: nose que hace 
         * @param string $url
	 * TODO: nose que hace
         * @return void 
        */
        
	public function setUrl($url) {
		$this->api_url = $url;
		return $this;
	}
        
        /**
	 * Metodo de Envio  
         * TODO: nose que hace
          * @return void 
        */
	
	public function get() {
		$this->checkApiUrl();
		return $this->api_call(GET_CALL);
	}
        
        
        /**
	 * metodo de envio POST 
          * TODO: nose que hace
          * @return void 
        */
	
	public function post() {
		$this->checkApiUrl();
		return $this->api_call(POST_CALL);
	}
        
        /**
	 * TODO: nose que hace
         * @return void 
        */
	
	public function put() {
		$this->checkApiUrl();
		return $this->api_call(PUT_CALL);
	}
	
        /**
	 * Elimina una paeticion 
         * TODO: nose que hace
         * @return void 
        */
	public function delete() {
		$this->checkApiUrl();
		return $this->api_call(DELETE_CALL);
	}
	
        /**
	 * Conjunto de Datos  
          * @param string Data
         * @param $type
	 * TODO: nose que hace
          * @return void 
        */
	public function setData($data, $type = DATA_JSON) {
		$this->data = $data;
		$this->data_type = $type;
		return $this;
	}
	
        /**
	  * TODO: nose que hace
          * @return void 
        */
	protected function checkApiUrl() {
		if($this->api_url == '') {
			throw new BadApiUrlException();
		}
	}
        
        /**
	 * La Api llama un metdo de la funcion 
          * @param string $method
	 * TODO: nose que hace
          * @return void 
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
								
				/** use a max of 256KB of RAM before going to disk */
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
				assert('false; // Esto NO NO NO');
				throw CallerException(_('Error incomprensible, esto no debería de suceder bajo ninguna circunstancia'));
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
