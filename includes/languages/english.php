<?php
/*
  $Id: english.php 1743 2007-12-20 18:02:36Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2007 osCommerce

  Released under the GNU General Public License
*/

// look in your $PATH_LOCALE/locale directory for available locales
// or type locale -a on the server.
// Examples:
// on RedHat try 'en_US'
// on FreeBSD try 'en_US.ISO_8859-1'
// on Windows try 'en', or 'English'
@setlocale(LC_TIME, 'ro_RO.ISO_8859-1');

define('DATE_FORMAT_SHORT', '%d/%m/%Y');  // this is used for strftime()
define('DATE_FORMAT_LONG', '%d/%m/%Y'); // this is used for strftime()
define('DATE_FORMAT', 'd/m/Y'); // this is used for date()
define('DATE_TIME_FORMAT', DATE_FORMAT_SHORT . ' %H:%M:%S');

////
// Return date in raw format
// $date should be in format mm/dd/yyyy
// raw date is in format YYYYMMDD, or DDMMYYYY
function tep_date_raw($date, $reverse = true) {
  if ($reverse) {
    return substr($date, 6, 4) . substr($date, 3, 2) . substr($date, 0, 2);
  } else {
    return substr($date, 6, 4) . substr($date, 0, 2) . substr($date, 3, 2);
  }
}

// if USE_DEFAULT_LANGUAGE_CURRENCY is true, use the following currency, instead of the applications default currency (used when changing language)
define('LANGUAGE_CURRENCY', 'RON');

// Global entries for the <html> tag
define('HTML_PARAMS','dir="LTR" lang="ro"');

// charset for web pages and emails
define('CHARSET', 'iso-8859-2');

// page title
define('TITLE', STORE_NAME);

// header text in includes/header.php
define('HEADER_TITLE_CREATE_ACCOUNT', 'Creaza un cont');
define('HEADER_TITLE_MY_ACCOUNT', 'Contul meu');
define('HEADER_TITLE_CART_CONTENTS', 'Continutul cosului');
define('HEADER_TITLE_CHECKOUT', 'Casa');
define('HEADER_TITLE_TOP', 'Sus');
define('HEADER_TITLE_CATALOG', 'Acasa');
define('HEADER_TITLE_LOGOFF', 'Iesire');
define('HEADER_TITLE_LOGIN', 'Autentificare');

// footer text in includes/footer.php
define('FOOTER_TEXT_REQUESTS_SINCE', 'requests since');

// text for gender
define('MALE', 'Domnul');
define('FEMALE', 'Doamna');
define('MALE_ADDRESS', 'Domnul');
define('FEMALE_ADDRESS', 'Doamna');

// text for date of birth example
define('DOB_FORMAT_STRING', 'dd/mm/yyyy');

// categories box text in includes/boxes/categories.php
define('BOX_HEADING_CATEGORIES', 'Categori');

// manufacturers box text in includes/boxes/manufacturers.php
define('BOX_HEADING_MANUFACTURERS', 'Producatori');

// whats_new box text in includes/boxes/whats_new.php
define('BOX_HEADING_WHATS_NEW', 'Produse noi');

// quick_find box text in includes/boxes/quick_find.php
define('BOX_HEADING_SEARCH', 'Cautare');
define('BOX_SEARCH_TEXT', 'Foloseste cuvinte cheie pentru a gasi ceea ce cauti');
define('BOX_SEARCH_ADVANCED_SEARCH', 'Cautare avansata');

// specials box text in includes/boxes/specials.php
define('BOX_HEADING_SPECIALS', 'Promotii');

// reviews box text in includes/boxes/reviews.php
define('BOX_HEADING_REVIEWS', 'Reviews');
define('BOX_REVIEWS_WRITE_REVIEW', 'Write a review on this product!');
define('BOX_REVIEWS_NO_REVIEWS', 'There are currently no product reviews');
define('BOX_REVIEWS_TEXT_OF_5_STARS', '%s of 5 Stars!');

// shopping_cart box text in includes/boxes/shopping_cart.php
define('BOX_HEADING_SHOPPING_CART', 'Cos de cumparaturi');
define('BOX_SHOPPING_CART_EMPTY', '0 items');

// order_history box text in includes/boxes/order_history.php
define('BOX_HEADING_CUSTOMER_ORDERS', 'Istoric cumparaturi');

// best_sellers box text in includes/boxes/best_sellers.php
define('BOX_HEADING_BESTSELLERS', 'Bestseller');
define('BOX_HEADING_BESTSELLERS_IN', 'Bestsellers in<br>&nbsp;&nbsp;');

// notifications box text in includes/boxes/products_notifications.php
define('BOX_HEADING_NOTIFICATIONS', 'Anunturi');
define('BOX_NOTIFICATIONS_NOTIFY', 'Anuntati-ma daca sunt modificari la <b>%s</b>');
define('BOX_NOTIFICATIONS_NOTIFY_REMOVE', 'nu ma anuntati daca sunt modificari la <b>%s</b>');

// manufacturer box text
define('BOX_HEADING_MANUFACTURER_INFO', 'Informatii privind producatorul');
define('BOX_MANUFACTURER_INFO_HOMEPAGE', '%s Pagina web');
define('BOX_MANUFACTURER_INFO_OTHER_PRODUCTS', 'Alte produse');

// languages box text in includes/boxes/languages.php
define('BOX_HEADING_LANGUAGES', 'Limbi straine');

// currencies box text in includes/boxes/currencies.php
define('BOX_HEADING_CURRENCIES', 'Monede');

// information box text in includes/boxes/information.php
define('BOX_HEADING_INFORMATION', 'Informatii');
define('BOX_INFORMATION_PRIVACY', 'Anunt privind intimitatea');
define('BOX_INFORMATION_CONDITIONS', 'Conditii privind utilizarea');
define('BOX_INFORMATION_SHIPPING', 'Distributia si returnarea produselor');
define('BOX_INFORMATION_CONTACT', 'Contact');

// tell a friend box text in includes/boxes/tell_a_friend.php
define('BOX_HEADING_TELL_A_FRIEND', 'Anunta un prieten');
define('BOX_TELL_A_FRIEND_TEXT', 'Spune unui cunoscut despre acest produs');

// tell a friend box text in includes/boxes/tell_a_friend.php
define('BOX_HEADING_ADD_TO_FAVORITES', 'Adauga in lista de favorite');

// checkout procedure text
define('CHECKOUT_BAR_DELIVERY', 'Informatii privind distributia');
define('CHECKOUT_BAR_PAYMENT', 'Informatii privind plata');
define('CHECKOUT_BAR_CONFIRMATION', 'Confirmare');
define('CHECKOUT_BAR_FINISHED', 'Sfarsit!');

// pull down default text
define('PULL_DOWN_DEFAULT', 'Selecteaza');
define('TYPE_BELOW', 'Scrie sub');

// javascript messages
define('JS_ERROR', 'In procesarea formularului dumneavoastra au fost intalnite erori\n\nVa rugam faceti urmatoarele modificari:\n\n');

define('JS_REVIEW_TEXT', '*  \'Review Text\' trebuie sa aiba minim ' . REVIEW_TEXT_MIN_LENGTH . ' caractere.\n');
define('JS_REVIEW_RATING', '* trebuie sa dati o nota site-ului pentru a scrie ceva despre el.\n');

define('JS_ERROR_NO_PAYMENT_MODULE_SELECTED', '* Va rog alegeti metoda de plata a comenzii.\n');

define('JS_ERROR_SUBMITTED', 'Acest formular a fost trimis deja. Va rog apasati Ok si asteptati finalizarea procedurii.');

define('ERROR_NO_PAYMENT_MODULE_SELECTED', 'Va rog seectati metoda de plata a comenzii.');

define('CATEGORY_COMPANY', 'Detalii Companie');
define('CATEGORY_PERSONAL', 'Date personale');
define('CATEGORY_ADDRESS', 'Adresa');
define('CATEGORY_CONTACT', 'Informati de contact');
define('CATEGORY_OPTIONS', 'Optiuni');
define('CATEGORY_PASSWORD', 'Parola');

define('ENTRY_COMPANY', 'Nume Companie:');
define('ENTRY_COMPANY_ERROR', '');
define('ENTRY_COMPANY_TEXT', '');
define('ENTRY_GENDER', '');
define('ENTRY_GENDER_ERROR', 'Va rog selectati formula de adresare.');
define('ENTRY_GENDER_TEXT', '*');
define('ENTRY_FIRST_NAME', 'Prenume:');
define('ENTRY_FIRST_NAME_ERROR', 'Prenumele trebuie sa contina minim ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' caractere.');
define('ENTRY_FIRST_NAME_TEXT', '*');
define('ENTRY_LAST_NAME', 'Nume:');
define('ENTRY_LAST_NAME_ERROR', 'Numele trebuie sa contina minim ' . ENTRY_LAST_NAME_MIN_LENGTH . ' caractere.');
define('ENTRY_LAST_NAME_TEXT', '*');
define('ENTRY_DATE_OF_BIRTH', 'Data nasterii:');
define('ENTRY_DATE_OF_BIRTH_ERROR', 'Data nasterii trebuie sa aiba urmatoarea forma: ZZ/LL/AAAA (ex 21/05/1970)');
define('ENTRY_DATE_OF_BIRTH_TEXT', '* (ex. 21/05/1970)');
define('ENTRY_EMAIL_ADDRESS', 'Adresa de E-Mail:');
define('ENTRY_EMAIL_ADDRESS_ERROR', 'Adresa de E-Mail trebuie sa contina minim ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' caractere.');
define('ENTRY_EMAIL_ADDRESS_CHECK_ERROR', 'Adresa de E-Mail nu pare valida. Va rugam faceti modificarile necesare.');
define('ENTRY_EMAIL_ADDRESS_ERROR_EXISTS', 'Adresa de E-Mail exista deja in baza noastra de date - va rugam sa va logati cu aceasta adresa sau sa creati un nou cont cu o adresa diferita.');
define('ENTRY_EMAIL_ADDRESS_TEXT', '*');
define('ENTRY_STREET_ADDRESS', 'Adresa:');
define('ENTRY_STREET_ADDRESS_ERROR', 'Strada din adresa trebuie sa contina minim ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' caractere.');
define('ENTRY_STREET_ADDRESS_TEXT', '*');
define('ENTRY_SUBURB', 'Suburb:');
define('ENTRY_SUBURB_ERROR', '');
define('ENTRY_SUBURB_TEXT', '');
define('ENTRY_POST_CODE', 'Cod postal:');
define('ENTRY_POST_CODE_ERROR', 'Codul postal trebuie sa aiba minim' . ENTRY_POSTCODE_MIN_LENGTH . ' caractere.');
define('ENTRY_POST_CODE_TEXT', '*');
define('ENTRY_CITY', 'Oras:');
define('ENTRY_CITY_ERROR', 'Orasul trebuie sa contina minim ' . ENTRY_CITY_MIN_LENGTH . ' caractere.');
define('ENTRY_CITY_TEXT', '*');
define('ENTRY_STATE', 'Judet:');
define('ENTRY_STATE_ERROR', 'Judetul trebuie sa contina minim ' . ENTRY_STATE_MIN_LENGTH . ' caractere.');
define('ENTRY_STATE_ERROR_SELECT', 'Va rugam sa alegeti judetul din lista');
define('ENTRY_STATE_TEXT', '*');
define('ENTRY_COUNTRY', 'Judetul:');
define('ENTRY_COUNTRY_ERROR', 'Va rugam sa alegeti judetul din lista.');
define('ENTRY_COUNTRY_TEXT', '*');
define('ENTRY_TELEPHONE_NUMBER', 'Numar de telefon:');
define('ENTRY_TELEPHONE_NUMBER_ERROR', 'Numerul de telefon trebuie sa contina minim' . ENTRY_TELEPHONE_MIN_LENGTH . ' caractere.');
define('ENTRY_TELEPHONE_NUMBER_TEXT', '*');
define('ENTRY_FAX_NUMBER', 'Numar de fax:');
define('ENTRY_FAX_NUMBER_ERROR', '');
define('ENTRY_FAX_NUMBER_TEXT', '');
define('ENTRY_NEWSLETTER', 'Abonare Newsletter:');
define('ENTRY_NEWSLETTER_TEXT', '');
define('ENTRY_NEWSLETTER_YES', 'Subscriu');
define('ENTRY_NEWSLETTER_NO', 'Nu subscriu');
define('ENTRY_NEWSLETTER_ERROR', '');
define('ENTRY_PASSWORD', 'Parola:');
define('ENTRY_PASSWORD_ERROR', 'Parola trebuie sa contina minim ' . ENTRY_PASSWORD_MIN_LENGTH . ' caractere.');
define('ENTRY_PASSWORD_ERROR_NOT_MATCHING', 'Confirmarea parolei trebuie sa fie aceeasi cu parola.');
define('ENTRY_PASSWORD_TEXT', '*');
define('ENTRY_PASSWORD_CONFIRMATION', 'Confirma parola:');
define('ENTRY_PASSWORD_CONFIRMATION_TEXT', '*');
define('ENTRY_PASSWORD_CURRENT', 'Parola curenta:');
define('ENTRY_PASSWORD_CURRENT_TEXT', '*');
define('ENTRY_PASSWORD_CURRENT_ERROR', 'Parola trebuie sa contina minim ' . ENTRY_PASSWORD_MIN_LENGTH . ' caractere.');
define('ENTRY_PASSWORD_NEW', 'Parola noua:');
define('ENTRY_PASSWORD_NEW_TEXT', '*');
define('ENTRY_PASSWORD_NEW_ERROR', 'Noua parola trebuie sa contina minim ' . ENTRY_PASSWORD_MIN_LENGTH . ' caractere.');
define('ENTRY_PASSWORD_NEW_ERROR_NOT_MATCHING', 'Confirmarea parolei trebuie sa fie aceeasi cu parola noua.');
define('PASSWORD_HIDDEN', '--HIDDEN--');

define('FORM_REQUIRED_INFORMATION', '* Informatii obligatorii');

// constants for use in tep_prev_next_display function
define('TEXT_RESULT_PAGE', 'Result Pages:');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS', 'Rezultate de la %d la %d din %d');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS', 'Vizualizeaza <b>%d</b> to <b>%d</b> (of <b>%d</b> comenzi)');
define('TEXT_DISPLAY_NUMBER_OF_REVIEWS', 'Vizualizeaza <b>%d</b> to <b>%d</b> (of <b>%d</b> cronici)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS_NEW', 'Vizualizeaza <b>%d</b> to <b>%d</b> (of <b>%d</b> produse noi)');
define('TEXT_DISPLAY_NUMBER_OF_SPECIALS', 'Vizualizeaza <b>%d</b> to <b>%d</b> (of <b>%d</b> oferte)');

define('PREVNEXT_TITLE_FIRST_PAGE', 'Prima pagina');
define('PREVNEXT_TITLE_PREVIOUS_PAGE', 'Pagina anterioara');
define('PREVNEXT_TITLE_NEXT_PAGE', 'Pagina urmatoare');
define('PREVNEXT_TITLE_LAST_PAGE', 'Ultima pagina');
define('PREVNEXT_TITLE_PAGE_NO', 'Pagina %d');
define('PREVNEXT_TITLE_PREV_SET_OF_NO_PAGE', 'Cele mai recente %d Pagini');
define('PREVNEXT_TITLE_NEXT_SET_OF_NO_PAGE', 'Urmatoarele of %d Pagini');
define('PREVNEXT_BUTTON_FIRST', '&lt;&lt;FIRST');
define('PREVNEXT_BUTTON_PREV', '&lt;&lt');
define('PREVNEXT_BUTTON_NEXT', '&gt;&gt');
define('PREVNEXT_BUTTON_LAST', 'LAST&gt;&gt;');

define('IMAGE_BUTTON_ADD_ADDRESS', 'Adauga adresa');
define('IMAGE_BUTTON_ADDRESS_BOOK', 'Lista de adrese');
define('IMAGE_BUTTON_BACK', 'Inapoi');
define('IMAGE_BUTTON_BUY_NOW', 'Cumpara acum');
define('IMAGE_BUTTON_CHANGE_ADDRESS', 'Schimba adresa');
define('IMAGE_BUTTON_CHECKOUT', 'Iesire');
define('IMAGE_BUTTON_CONFIRM_ORDER', 'Confirmare comanda');
define('IMAGE_BUTTON_CONTINUE', 'Continua');
define('IMAGE_BUTTON_CONTINUE_SHOPPING', 'Continua cumparaturile');
define('IMAGE_BUTTON_DELETE', 'Sterge');
define('IMAGE_BUTTON_EDIT_ACCOUNT', 'Editeaza adresa');
define('IMAGE_BUTTON_HISTORY', 'Istoric comenzi');
define('IMAGE_BUTTON_LOGIN', 'Logheza');
define('IMAGE_BUTTON_IN_CART', 'Adauga in cos');
define('IMAGE_BUTTON_NOTIFICATIONS', 'Notificare');
define('IMAGE_BUTTON_QUICK_FIND', 'Cautare rapida');
define('IMAGE_BUTTON_REMOVE_NOTIFICATIONS', 'Sterge notificari');
define('IMAGE_BUTTON_REVIEWS', 'Recenzii');
define('IMAGE_BUTTON_SEARCH', 'Cauta');
define('IMAGE_BUTTON_SHIPPING_OPTIONS', 'Optiuni');
define('IMAGE_BUTTON_TELL_A_FRIEND', 'Anunta un prieten');
define('IMAGE_BUTTON_UPDATE', 'Update');
define('IMAGE_BUTTON_UPDATE_CART', 'Update Cart');
define('IMAGE_BUTTON_WRITE_REVIEW', 'Write Review');

define('SMALL_IMAGE_BUTTON_DELETE', 'Sterge');
define('SMALL_IMAGE_BUTTON_EDIT', 'Editeaza');
define('SMALL_IMAGE_BUTTON_VIEW', 'Vezi');

define('ICON_ARROW_RIGHT', 'mai multe');
define('ICON_CART', 'In cos');
define('ICON_ERROR', 'Eroare');
define('ICON_SUCCESS', 'Succes');
define('ICON_WARNING', 'Anunt');

define('TEXT_GREETING_PERSONAL', 'Bine ati venit <span class="greetUser">%s!</span> Doriti sa vedeti ce <a href="%s"><u>noi produse</u></a> sunt disponibile?');
define('TEXT_GREETING_PERSONAL_RELOGON', '<small>Daca nu %s, please <a href="%s"><u>logati-va </u></a> la contul dumneavoastra.</small>');
define('TEXT_GREETING_GUEST', 'Bine ati venit <span class="greetUser">Guest!</span> Doriti sa<a href="%s"><u> va logati in</u></a>? sau preferati sa <a href="%s"><u>creati un cont</u></a>?');

define('TEXT_SORT_PRODUCTS', 'Sortati produsele ');
define('TEXT_DESCENDINGLY', 'descendent');
define('TEXT_ASCENDINGLY', 'ascendent');
define('TEXT_BY', ' dupa ');

define('TEXT_REVIEW_BY', 'dupa %s');
define('TEXT_REVIEW_WORD_COUNT', '%s cuvinte');
define('TEXT_REVIEW_RATING', 'Note: %s [%s]');
define('TEXT_REVIEW_DATE_ADDED', 'Data adougarii: %s');
define('TEXT_NO_REVIEWS', 'Momentan nu sunt recenzii pentru acest produs.');

define('TEXT_NO_NEW_PRODUCTS', 'Momentan nu sunt produse.');

define('TEXT_UNKNOWN_TAX_RATE', 'Unknown tax rate');

define('TEXT_REQUIRED', '<span class="errorText">Required</span>');

define('ERROR_TEP_MAIL', '<font face="Verdana, Arial" size="2" color="#ff0000"><b><small>TEP ERROR:</small> Cannot send the email through the specified SMTP server. Please check your php.ini setting and correct the SMTP server if necessary.</b></font>');
define('WARNING_INSTALL_DIRECTORY_EXISTS', 'Warning: Installation directory exists at: ' . dirname($_SERVER['SCRIPT_FILENAME']) . '/install. Please remove this directory for security reasons.');
define('WARNING_CONFIG_FILE_WRITEABLE', 'Warning: I am able to write to the configuration file: ' . dirname($_SERVER['SCRIPT_FILENAME']) . '/includes/configure.php. This is a potential security risk - please set the right user permissions on this file.');
define('WARNING_SESSION_DIRECTORY_NON_EXISTENT', 'Warning: The sessions directory does not exist: ' . tep_session_save_path() . '. Sessions will not work until this directory is created.');
define('WARNING_SESSION_DIRECTORY_NOT_WRITEABLE', 'Warning: I am not able to write to the sessions directory: ' . tep_session_save_path() . '. Sessions will not work until the right user permissions are set.');
define('WARNING_SESSION_AUTO_START', 'Warning: session.auto_start is enabled - please disable this php feature in php.ini and restart the web server.');
define('WARNING_DOWNLOAD_DIRECTORY_NON_EXISTENT', 'Warning: The downloadable products directory does not exist: ' . DIR_FS_DOWNLOAD . '. Downloadable products will not work until this directory is valid.');

define('TEXT_CCVAL_ERROR_INVALID_DATE', 'The expiry date entered for the credit card is invalid. Please check the date and try again.');
define('TEXT_CCVAL_ERROR_INVALID_NUMBER', 'The credit card number entered is invalid. Please check the number and try again.');
define('TEXT_CCVAL_ERROR_UNKNOWN_CARD', 'The first four digits of the number entered are: %s. If that number is correct, we do not accept that type of credit card. If it is wrong, please try again.');

define('FOOTER_TEXT_BODY', 'Copyright &copy; ' . date('Y') . ' <a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . STORE_NAME . '</a><br>Powered by <a href="http://www.oscommerce.com" target="_blank">osCommerce</a>');

define('ERROR_INVALID_COUPON', 'Ai introdus un Voucher invalid');
define('ERROR_USED_COUPON', 'Ai folosit deja acest Voucher');

?>
