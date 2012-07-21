<?php

class Caller
{
	protected $api_url;
	
	protected $data;
	
	protected $data_type;
	
	public function __construct($url='')
	{
		$this->api_url = $url;
	}
	
	public function setUrl($url) {
		$this->api_url = $url;
		return $this;
	}
	
	public function get() {
		$this->checkApiUrl();
		return $this->api_call(GET_CALL);
	}
	
	public function post() {
		$this->checkApiUrl();
		return $this->api_call(POST_CALL);
	}
	
	public function put() {
		$this->checkApiUrl();
		return $this->api_call(PUT_CALL);
	}
	
	public function delete() {
		$this->checkApiUrl();
		return $this->api_call(DELETE_CALL);
	}
	
	public function setData($data, $type = DATA_JSON) {
		$this->data = $data;
		$this->data_type = $type;
		return $this;
	}
	
	protected function checkApiUrl() {
		if($this->api_url == '') {
			throw new BadApiUrlException();
		}
	}

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
				// TODO implementar este metodo
				break;
			case DELETE_CALL:
				// TODO implementar este metodo
				break;
			default:
				die(_('Error incomprensible, esto no debería de suceder bajo ninguna circunstancia'));
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
