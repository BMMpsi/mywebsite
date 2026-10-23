<?php
  /**
  * Gestore del modulo contatti.
  *
  * Le credenziali SMTP NON stanno in questo file (che è pubblico su GitHub):
  * vengono lette da config.php, che è escluso dal repository.
  * Vedi forms/config.example.php per le istruzioni.
  */

  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;

  require 'PHPMailer/src/Exception.php';
  require 'PHPMailer/src/PHPMailer.php';
  require 'PHPMailer/src/SMTP.php';

  // Cerca la configurazione prima fuori dalla cartella pubblica del sito, poi in forms/
  $config_paths = array(
    __DIR__ . '/../../mywebsite-config.php',
    __DIR__ . '/config.php'
  );
  $config = null;
  foreach ($config_paths as $path) {
    if (file_exists($path)) {
      $config = require $path;
      break;
    }
  }
  if (!is_array($config) || empty($config['smtp_username']) || empty($config['smtp_password'])) {
    die('Il modulo contatti non è configurato. Riprova più tardi o scrivi direttamente via email.');
  }

  if( file_exists($php_email_form = '../assets/vendor/php-email-form/php-email-form.php' )) {
    include( $php_email_form );
  } else {
    die( 'Unable to load the "PHP Email Form" Library!');
  }

  // Ripulisce un testo da usare in un'intestazione email (niente a capo, lunghezza limitata)
  function header_safe($text, $max = 80) {
    $text = trim(preg_replace('/[\r\n\t]+/', ' ', (string) $text));
    return mb_substr($text, 0, $max);
  }

  $visitor_name = header_safe(isset($_POST['name']) ? $_POST['name'] : '');
  $visitor_subject = header_safe(isset($_POST['subject']) ? $_POST['subject'] : '', 120);

  $contact = new PHP_Email_Form;
  $contact->ajax = true;

  $contact->to = $config['receiving_email'];
  // Nome del visitatore: usato per il "Rispondi a", così la risposta va a lui
  $contact->from_name = $visitor_name;
  $contact->from_email = isset($_POST['email']) ? $_POST['email'] : '';
  $contact->subject = '[Sito] ' . $visitor_subject . ($visitor_name !== '' ? ' - da ' . $visitor_name : '');

  // Invio tramite l'account configurato (il mittente tecnico è sempre questo account)
  $contact->smtp = array(
    'host' => isset($config['smtp_host']) ? $config['smtp_host'] : 'smtp.gmail.com',
    'username' => $config['smtp_username'],
    'password' => $config['smtp_password'],
    'port' => isset($config['smtp_port']) ? $config['smtp_port'] : 587
  );

  // Nome visualizzato nel campo "Da": chiaro che arriva dal modulo del sito
  $contact->option('FromName', 'Modulo sito – ' . ($visitor_name !== '' ? $visitor_name : 'visitatore'));

  $contact->add_message( $visitor_name, 'Nome');
  $contact->add_message( $contact->from_email, 'Email');
  $contact->add_message( isset($_POST['message']) ? $_POST['message'] : '', 'Messaggio', 10);

  echo $contact->send();
?>
