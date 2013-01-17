<?php

/**
 * @brief Excepción general para el proceso de envío de correos
 * 
 * Normalmente se capturan las excepciones derivadas más específicas
 */
class MailerException extends MoonDragonException {}

/**
 * @brief Error en la configuración del correo
 * 
 * Implica un error en los valores de configuración que se proporcionaron
 */
class MailConfException extends MailerException {}

/**
 * @brief Error en el envío de correo
 * 
 * PHPMailer no pudo enviar el correo por alguna razón
 */
class MailSendException extends MailerException {}

// Fin del archivo
