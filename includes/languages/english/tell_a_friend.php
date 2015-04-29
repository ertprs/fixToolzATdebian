<?php
/*
  $Id: tell_a_friend.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Recomanda unui prieten');

define('HEADING_TITLE', 'Recomanda unui prieten produsul:<br>%s');

define('FORM_TITLE_CUSTOMER_DETAILS', 'Detalii despre tine');
define('FORM_TITLE_FRIEND_DETAILS', 'Detalii despre prietenul tau');
define('FORM_TITLE_FRIEND_MESSAGE', 'Mesajul tau');

define('FORM_FIELD_CUSTOMER_NAME', 'Numele tau:');
define('FORM_FIELD_CUSTOMER_EMAIL', 'Adresa ta de e-mail:');
define('FORM_FIELD_FRIEND_NAME', 'Numele prietenului tau:');
define('FORM_FIELD_FRIEND_EMAIL', 'Adresa de e-mail a prietenului tau:');

define('TEXT_EMAIL_SUCCESSFUL_SENT', 'E-mail-ul tau despre <b>%s</b> a fost trimis cu succes catre <b>%s</b>.');

define('TEXT_EMAIL_SUBJECT', 'Prietenul tau %s, iti recomanda acest produs de la %s');
define('TEXT_EMAIL_INTRO', 'Salut %s,' . "\n\n" . 'Prietenul tau , %s, crede ca vei fi interesat de %s de la %s.');
define('TEXT_EMAIL_LINK', 'Ca sa vezi produsul click pe linkul de mai jos sau copiaza-l in browser-ul tau preferat:' . "\n\n" . '%s');
define('TEXT_EMAIL_SIGNATURE', 'Iti multumim ca ai folosit serviciile noastre.'."\n".'Echipa ToolsZone.ro iti doreste o zi buna.,' . "\n\n" . '%s');

define('ERROR_TO_NAME', 'Eroare: Adresa de mail a prietenului tau nu poate sa fie goala.');
define('ERROR_TO_ADDRESS', 'Eroare: Adresa de mail a prietenului tau trebuie sa fie valida.');
define('ERROR_FROM_NAME', 'Eroare: Adresa ta de mail nu poate sa fie goala.');
define('ERROR_FROM_ADDRESS', 'Eroare: Adresa ta de mail trebuie sa fie valida.');
?>
