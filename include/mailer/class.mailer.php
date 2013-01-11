<?php


/**
 * @brief Clase para envío de correo electrónico
 * 
 * Basado en PHPMailer
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
 * @ingroup Mailer
 */

class Mailer
{
	/**
	 * Los correos electrónicos se envían mediante la función mail()
	 */
	const MAIL = 10;

	/**
	 * Los correos electrónicos se envían mediante SMTP
	 */
	const SMTP = 20;

	/**
	 * Los correos electrónicos se envían mediante el SMTP de Gmail
	 */
	const GMAIL = 30;

	/**
	 * Almacena las variables de configuración de la clase
	 * @var array $conf
	 */
	private static $conf = array();
	
	/**
	 * Devuelve los valores de configuración de la clase
	 * @param string $name
	 * @return string
	 */
	private static function get( $name )
	{
		if(isset(self::$conf[$name])) {
			return self::$conf[$name];
		}
		else {
			return '';
		}
	}
	
	/**
	 * Agrega las direcciones de los remitentes
	 * @param string|array $address puede ser una dirección única o un arreglo de direcciones
	 * @param PHPMailer $mail
	 * @return void
	 * @throws MailConfException
	 */
	private static function processAddress( $address, PHPMailer $mail )
	{
		if( is_array($address) )
		{
			foreach( $address as $name => $email )
			{
				$mail->AddAddress( $email, $name );
			}
		}
		elseif( $address == '' )
		{
			if( self::get('default_address_email') != '' )
			{
				$mail->AddAddress( self::get('default_address_email'), self::get('default_address_name') );
			}
			else
			{
				throw new MailConfException( 'No hay direcciones de envío' ); //@todo localizar contenido
			}
		}
		else
		{
			$mail->AddAddress( $address );
		}
	}

	/**
	 * Configura el método mediante el cual se enviara el correo electrónico
	 * @param PHPMailer $mail
	 * @return void
	 * @throws MailConfException
	 */
	private static function confMailServer( PHPMailer $mail )
	{
		$type = self::get('mail_type');

		switch( $type )
		{
			case self::GMAIL :
				self::confGmail($mail);
				break;
			case self::SMTP :
				self::confSMTP($mail);
				break;
			case self::MAIL :
			default:
				// No hace nada
		}
	}

	/**
	 * Configura el sistema para enviar los correos a través de Gmail
	 * @param PHPMailer $mail
	 * @return void
	 * @throws MailConfException
	 */
	private static function confGmail( PHPMailer $mail )
	{
		$gmail_username = self::get('gmail_username');
		$gmail_password = self::get('gmail_password');

		if( $gmail_username == '' || $gmail_password == '' )
		{
			throw new MailConfException( 'No se han configurado todos los parámetros para Gmail (gmail_username, gmail_password)'); //@todo localizar contenido
		}

		$mail->IsSMTP();
		$mail->SMTPAuth   = true;
		$mail->SMTPSecure = "ssl";
		$mail->Host       = "smtp.gmail.com";
		$mail->Port       = 465;
		$mail->Username   = $gmail_username;
		$mail->Password   = $gmail_password;
	}

	/**
	 * Configura el sistema para enviar los correos mediante un servidor SMTP
	 * @param PHPMailer $mail
	 * @return void
	 * @throws MailConfException
	 */
	private static function confSMTP( PHPMailer $mail )
	{
		$domain = self::get( 'smtp_domain' );
		$username = self::get('smtp_username');
		$password = self::get('smtp_password');
		
		if( $domain == '' || $username == '' || $password == '' )
		{
			throw new MailConfException('No se han configurado todos los parametros para SMTP (smtp_domain, smtp_username, smtp_password)' ); //@todo localizar contenido
		}
		
		$mail->IsSMTP();
		$mail->SMTPAuth   = true;
		
		$mail->Host       = $domain;
		$mail->Port       = (self::get('smtp_port') == '')?26:self::get('smtp_port');                    // set the SMTP port for the GMAIL server
		$mail->Username   = $username;
		$mail->Password   = $password;
	}
	
	/**
	 * Configura las direcciones desde las que se envía el correo electrónico
	 * @param PHPMailer $mail
	 * @return void
	 */
	private static function confSender( PHPMailer $mail )
	{
		if( self::get( 'from_email' ) == '' )
		{
			trigger_error( 'No se ha definido la variable from_email', E_USER_NOTICE );
		}
		else
		{
			$mail->SetFrom( self::get( 'from_email' ), self::get( 'from_name' ) );
		}
		
		if( self::get( 'replyto_email') != '' )
		{
			$mail->AddReplyTo( self::get( 'replyto_email' ),  self::get( 'replyto_name' ) );
		}
	}

	/**
	 * Configura los parámetros del sistema
	 * 
	 * @todo Agregar lista de valores de configuración
	 * @see url de lista de valores de configuración aquí
	 * @param string $name
	 * @param string $value
	 * @return void
	 */
	
	public static function set( $name, $value )
	{
		self::$conf[$name] = $value;
	}
	
	/**
	 * Envía el correo electrónico con los parámetros configurados en el sistema
	 * @param string $subject
	 * @param string $body
	 * @param string|array $address puede ser una dirección única o un arreglo de direcciones
	 * @return void
	 * @throws MailConfException
	 * @throws MailSendException
	 */
	
	public static function sendMail( $subject, $body, $address = '' )
	{
		assert('class_exists("PHPMailer")');
		$mail = new PHPMailer(true);

		$mail->Subject = $subject;
		$mail->MsgHTML($body);
		
		self::confMailServer($mail);
		self::confSender($mail);
		self::processAddress($address, $mail);
		
		if(self::get('charset') == '') {
			$mail->CharSet = 'utf-8';
		}
		else {
			$mail->CharSet = self::get('charset');
		}
		
		try
		{
			$mail->Send();
		}
		catch(phpmailerException $e)
		{
			throw new MailSendException($e->getMessage());
		}
	}

}

// Fin de archivo
