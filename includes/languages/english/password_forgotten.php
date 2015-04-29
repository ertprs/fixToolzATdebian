<?php
/*
  $Id: password_forgotten.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE_1', 'Login');
define('NAVBAR_TITLE_2', 'Recuperare parola');

define('HEADING_TITLE', 'Am uitat parola!');

define('TEXT_MAIN', 'Daca ti-ai uitat parola, introdce adresa de e-mail in campul de mai jos si vei primi un mesaj cu noua ta parola!');

define('TEXT_NO_EMAIL_ADDRESS_FOUND', 'Eroare: Adresa de e-mail nu exista in baza noastra de date.');

define('EMAIL_PASSWORD_REMINDER_SUBJECT', STORE_NAME . ' - Parola noua');
define('EMAIL_PASSWORD_REMINDER_BODY', 'O noua parola a fost ceruta de ' . $REMOTE_ADDR . '.' . "\n\n" . 'Parola noua pentru \'' . STORE_NAME . '\' este:' . "\n\n" . '   %s' . "\n\n");

define('SUCCESS_PASSWORD_SENT', 'Succes: O noua parola a fost trimisa.');
?>