<?php
/*
  $Id: advanced_search.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE_1', 'Cautare Avansata');
define('NAVBAR_TITLE_2', 'Rezultatele Cautarii');

define('HEADING_TITLE_1', 'Cautare Avansata');
define('HEADING_TITLE_2', 'Produse ce corespund criterilor de cautare');

define('HEADING_SEARCH_CRITERIA', 'Criteri de Cautare');

define('TEXT_SEARCH_IN_DESCRIPTION', 'Cautare in descrierile produselor');
define('ENTRY_CATEGORIES', 'Categori:');
define('ENTRY_INCLUDE_SUBCATEGORIES', 'Subcategori include');
define('ENTRY_MANUFACTURERS', 'Producatori:');
define('ENTRY_PRICE_FROM', 'Pret minim:');
define('ENTRY_PRICE_TO', 'Pret maxim:');
define('ENTRY_DATE_FROM', 'Din data:');
define('ENTRY_DATE_TO', 'Pana la data:');

define('TEXT_SEARCH_HELP_LINK', '<u>Ajutor</u> [?]');

define('TEXT_ALL_CATEGORIES', 'Toate Categoriile');
define('TEXT_ALL_MANUFACTURERS', 'Toti Producatorii');

define('HEADING_SEARCH_HELP', 'Ajutor');
define('TEXT_SEARCH_HELP', 'Keywords may be separated by AND and/or OR statements for greater control of the search results.<br><br>For example, <u>Microsoft AND mouse</u> would generate a result set that contain both words. However, for <u>mouse OR keyboard</u>, the result set returned would contain both or either words.<br><br>Exact matches can be searched for by enclosing keywords in double-quotes.<br><br>For example, <u>"notebook computer"</u> would generate a result set which match the exact string.<br><br>Brackets can be used for further control on the result set.<br><br>For example, <u>Microsoft and (keyboard or mouse or "visual basic")</u>.');
define('TEXT_CLOSE_WINDOW', '<u>Inchide Fereastra</u> [x]');

define('TABLE_HEADING_IMAGE', '');
define('TABLE_HEADING_MODEL', 'Model');
define('TABLE_HEADING_PRODUCTS', 'Nume Produs');
define('TABLE_HEADING_MANUFACTURER', 'Producator');
define('TABLE_HEADING_QUANTITY', 'Cantitate');
define('TABLE_HEADING_PRICE', 'Pret (cu TVA)');
define('TABLE_HEADING_WEIGHT', 'Greutate');
define('TABLE_HEADING_BUY_NOW', 'Adauga in cos');

define('TEXT_NO_PRODUCTS', 'Nu exista produse care sa corespunda criterilor de cautare.');

define('ERROR_AT_LEAST_ONE_INPUT', 'Trebuie completat cel putin un camp din formularul de cautare.');
define('ERROR_INVALID_FROM_DATE', 'Invalid From Date.');
define('ERROR_INVALID_TO_DATE', 'Invalid To Date.');
define('ERROR_TO_DATE_LESS_THAN_FROM_DATE', 'Pana la data trebuie sa fie mai mare sau egal cu Din data.');
define('ERROR_PRICE_FROM_MUST_BE_NUM', 'Pret minim trebuie sa fie un numar.');
define('ERROR_PRICE_TO_MUST_BE_NUM', 'Pret maxim trebuie sa fie un numar.');
define('ERROR_PRICE_TO_LESS_THAN_PRICE_FROM', 'Pret maxim trebuie sa fie mai mare sau egal cu Pret minim.');
define('ERROR_INVALID_KEYWORDS', 'Cuvinte invalide.');
?>
