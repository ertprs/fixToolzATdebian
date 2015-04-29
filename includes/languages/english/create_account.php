<?php
/*
  $Id: create_account.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Creaza un cont nou');

define('HEADING_TITLE', 'Informatii pentru un cont nou');

define('TEXT_ORIGIN_LOGIN', '<font color="#c5001c"><small><b>Atentie:</b></font></small> Varsta minima pentru a creea un cont este de <font color="#c5001c"><small><b>18 ani</b></font></small>.');

define('EMAIL_SUBJECT', 'Bine ati venit la ' . STORE_NAME);
define('EMAIL_GREET_MR', 'Domnule %s,' . "\n\n");
define('EMAIL_GREET_MS', 'Doamna %s,' . "\n\n");
define('EMAIL_GREET_NONE', '%s' . "\n\n");
define('EMAIL_WELCOME', 'Va multumim pentru crearea unui cont la <b>' . STORE_NAME . '</b>.' . "\n\n");
define('EMAIL_TEXT', 'Acuma puteti folosi <b>numeroasele noastre servicii</b>, pe care le oferim. Cateva din aceste servicii sunt:' . "\n\n" . '' .
							'<li><b>Cos de cumparaturi permanent</b> - Orice produs adaugat in cosul de cumparaturi online ramane acolo pana in momentul in care este sters de dumneavoastra sau in momentul confirmarii comenzii.' . "\n" . '' .
							'<li><b>Agenda cu adrese</b> - Putem sa trimitem comanda la alta adresa decat adresa dumneavoastra de domiciliu! Acest lucru va ajuta sa trimite-ti comanda la locul de munca, daca nu sunteti acasa in timpul zilei.' . "\n" . '' .
							'<li><b>Istoric al comenzilor</b> - Puteti sa vizualizati toate comenzile facute la noi.' . "\n");
define('EMAIL_CONTACT', 'Pentru informatii suplimentare nu ezitati sa ne contactati prin e-mail la adresa: ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n\n");
define('EMAIL_WARNING', '');
?>
