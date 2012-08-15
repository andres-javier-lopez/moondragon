<?php

/**
 * Manejador de plantillas
 *
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright TuApp.net - GNU Lesser General Public License
 * @date Feb 2012
 * @version 3.2
 * @ingroup Core
 */

class Template
{
	/**
	 * Ruta de directorios para plantillas
	 * @var array $dir
	 */
	private static $dir = array('./');
	
	/**
	 * Nombre de la plantilla actual
	 * @var string $template
	 */
	private $template;
		
	/**
	 * Variables para ser reemplazadas en la plantilla
	 * @var array $vars
	 */
	private $vars = array();

	/**
	 * Contenido de la plantilla
	 * @var string $content
	 */
	private $content = '';
	
	/**
	 * Determina si una plantilla es del tipo página
	 * @var boolean $page
	 */
	private $page;

	/**
	 * Constructor de la clase
	 * @param string $template
	 * @param boolean $page Determina si la plantilla es una página, por defecto es falso
	 * @return void
	 */
	public function __construct( $template = '', $page = false )
	{
		if($template != '') {
			$this->setTemplate( $template, $page );
		}
	}

	/**
	 * Método mágico para convertir el objeto en una cadena
	 * @return string
	 */
	public function __toString()
	{
		return $this->show();
	}

	/**
	 * Modifica la plantilla seleccionada
	 * @param string $template
	 * @param boolean $page Determina si la plantilla es una página, por defecto es falsa
	 * @return void
	 */
	public function setTemplate( $template, $page = false )
	{
		$found = false;
		
		assert('!empty(self::$dir)');
		foreach(self::$dir as $dir) {
			if(file_exists($dir.$template)) {
				$this->template = $dir.$template;
				$found = true;
			}
			else if(file_exists($dir.$template.'.tpl')) {
				$this->template = $dir.$template.'.tpl';
				$found = true;
			}
		}

		if(!$found){
			throw new TemplateNotFoundException();
		}
		
		assert('file_exists($this->template)');
		
		if(strpos($this->template, 'page.') !== false) {
			$this->page = true;
		}
		else {
			$this->page = $page;
		}
		$this->content = '';
	}

	/**
	 * Modifica las variables que serán agregadas a la plantilla
	 * @param array $vars
	 * @return void
	 */
	public function setVars( $vars )
	{
		$this->vars = $vars;
	}

	/**
	 * Devuelve el contenido de la plantilla despues de procesarlo
	 * @return string
	 * @throws TemplateException
	 */
	public function show()
	{
		if( $this->content == '' )
		{
			$this->readTemplate();
			$this->replaceVars();
		}

		return $this->content;
	}

	/**
	 * Lee el archivo de plantilla
	 * @return void
	 * @throws TemplateException
	 */
	private function readTemplate()
	{
		assert('file_exists($this->template)');
		$file_data = fopen($this->template, 'r');
		if ( !$file_data )
		{
			$this->content = '';
			throw new RenderException( _('No se pudo leer la plantilla') );
		}

		$this->content = fread( $file_data, filesize( $this->template ) );
		fclose( $file_data );
	}

	/**
	 * Reemplaza las variables en la plantilla
	 * @return void
	 */
	private function replaceVars()
	{
		assert('is_array($this->vars)');
		assert('isset($this->content)');
		foreach($this->vars as $key => $var)
		{
			$this->content = str_replace( '[#:'.$key.']', $var, $this->content );
		}
		$this->content = preg_replace( '/\[\#:(\w*)\]/s', '', $this->content );
		
		if($this->page) {
			preg_match_all('/<md:(\w*|\w+:\w+)\/>/i', $this->content, $templates);
			
			$this->content = str_replace('<md:content/>', Buffer::getContent('output'), $this->content);
			foreach($templates[1] as $tpl)
			{
				$this->content = str_replace('<md:'.$tpl.'/>', self::load($tpl, $this->vars), $this->content);
			}
			
			$this->content = str_replace('</head>', Buffer::getContent('meta').'</head>', $this->content);
			$this->content = str_replace('</body>', Buffer::getContent('error').'</body>', $this->content);
			$this->content = str_replace('</body>', Buffer::getContent('script').'</body>', $this->content);
			
			if(isset(MoonDragon::$registry['base_url']))
			{
				$this->content = str_replace('<head>', '<head><base href="'.MoonDragon::$registry['base_url'].'/" />', $this->content);
			}
		}
	}
	
	/**
	 * Agrega una ruta de directorio al sistema de plantillas
	 * @param string $dir
	 */
	public static function addDir($dir) {
		assert('is_array(self::$dir)');
		self::$dir[] = trim($dir, '/').'/';
	}

	/**
	 * Procesa y devuelve la plantilla específicada
	 * @param string $template
	 * @param array $vars
	 * @param boolean $page Determina si la plantilla es una página, por defecto es falso
	 * @return string
	 * @throws TemplateException
	 */
	public static function load( $template, $vars = array(), $page = false)
	{
		$tpl = new self( $template, $page );
		$vars_array = array_merge(  Vars::getVars(), $vars );
		$tpl->setVars( $vars_array );
		return $tpl->show();
	}
}

// Fin de archivo
