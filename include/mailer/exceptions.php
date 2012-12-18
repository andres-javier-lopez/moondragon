<?php

/// Excepción estándar de la librería Mailer
/// @ingroup Mailer
class MailerException extends LoggedException {}

/// Error de configuración de la librería Mailer
/// @ingroup Mailer
class MailConfException extends MailerException {}
