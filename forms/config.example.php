<?php
/**
 * ESEMPIO di configurazione del modulo contatti.
 *
 * Come usarlo:
 * 1. Copia questo file come "config.php" nella stessa cartella (forms/config.php)
 *    oppure, meglio ancora, come "mywebsite-config.php" nella cartella SOPRA
 *    quella pubblica del sito (fuori da public_html / www), se l'hosting lo consente.
 * 2. Inserisci la password per le app dell'account di invio.
 * 3. NON caricare mai config.php su GitHub (è già escluso dal file .gitignore).
 *
 * Password per le app Gmail: Account Google > Sicurezza > Verifica in due passaggi
 * attiva > "Password per le app". Si ottiene un codice di 16 lettere.
 */

return array(
  // Dove arrivano le richieste del sito
  'receiving_email' => 'biancamariamonti.psi@gmail.com',

  // Account che invia le email del modulo (quello di Bianca)
  'smtp_host'     => 'smtp.gmail.com',
  'smtp_port'     => 587,
  'smtp_username' => 'biancamariamonti.psi@gmail.com',
  'smtp_password' => 'INSERISCI_QUI_LA_PASSWORD_PER_LE_APP',
);
