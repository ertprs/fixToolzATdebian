<?php
/*
  $Id: orders.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Comenzi');
define('HEADING_TITLE_SEARCH', 'ID Comanda:');
define('HEADING_TITLE_STATUS', 'Status:');

define('TABLE_HEADING_COMMENTS', 'Comentarii');
define('TABLE_HEADING_CUSTOMERS', 'Clienti');
define('TABLE_HEADING_ORDER_TOTAL', 'Total comanda');
define('TABLE_HEADING_DATE_PURCHASED', 'Data comenzii');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_ACTION', 'Actiune');
define('TABLE_HEADING_QUANTITY', 'Cantitate');
define('TABLE_HEADING_PRODUCTS_MODEL', 'Model');
define('TABLE_HEADING_PRODUCTS', 'Produse');
define('TABLE_HEADING_TAX', 'TVA');
define('TABLE_HEADING_TOTAL', 'Total');
define('TABLE_HEADING_PRICE_EXCLUDING_TAX', 'Pret (fara TVA)');
define('TABLE_HEADING_PRICE_INCLUDING_TAX', 'Pret (cu TVA)');
define('TABLE_HEADING_TOTAL_EXCLUDING_TAX', 'Total (fara TVA)');
define('TABLE_HEADING_TOTAL_INCLUDING_TAX', 'Total (cu TVA)');

define('TABLE_HEADING_CUSTOMER_NOTIFIED', 'Client notificat');
define('TABLE_HEADING_DATE_ADDED', 'Data adaugarii');

define('ENTRY_CUSTOMER', 'Client:');
define('ENTRY_SOLD_TO', 'VANDUT CATRE:');
define('ENTRY_DELIVERY_TO', 'Livrarea catre:');
define('ENTRY_SHIP_TO', 'Livrare catre:');
define('ENTRY_SHIPPING_ADDRESS', 'Adresa de livrare:');
define('ENTRY_BILLING_ADDRESS', 'Adresa de facturare:');
define('ENTRY_PAYMENT_METHOD', 'Metoda de plata:');
define('ENTRY_CREDIT_CARD_TYPE', 'Credit Card Type:');
define('ENTRY_CREDIT_CARD_OWNER', 'Credit Card Owner:');
define('ENTRY_CREDIT_CARD_NUMBER', 'Credit Card Number:');
define('ENTRY_CREDIT_CARD_EXPIRES', 'Credit Card Expires:');
define('ENTRY_SUB_TOTAL', 'Sub-Total:');
define('ENTRY_TAX', 'Taxa:');
define('ENTRY_SHIPPING', 'Shipping:');
define('ENTRY_TOTAL', 'Total:');
define('ENTRY_DATE_PURCHASED', 'Date Purchased:');
define('ENTRY_STATUS', 'Status:');
define('ENTRY_DATE_LAST_UPDATED', 'Date Last Updated:');
define('ENTRY_NOTIFY_CUSTOMER', 'Notificare client:');
define('ENTRY_NOTIFY_COMMENTS', 'Adauga comentarii:');
define('ENTRY_PRINTABLE', 'Printeaza factura');

define('TEXT_INFO_HEADING_DELETE_ORDER', 'Sterge comanda');
define('TEXT_INFO_DELETE_INTRO', 'Esti sigur ca vrei sa stergi aceasta comanda?');
define('TEXT_INFO_RESTOCK_PRODUCT_QUANTITY', 'Restock product quantity');
define('TEXT_DATE_ORDER_CREATED', 'Data comenzii:');
define('TEXT_DATE_ORDER_LAST_MODIFIED', 'Ultima modificare:');
define('TEXT_INFO_PAYMENT_METHOD', 'Metoda de plata:');

define('TEXT_ALL_ORDERS', 'Toate comenzile');
define('TEXT_NO_ORDER_HISTORY', 'Nu sunt comenzi');

define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('EMAIL_TEXT_SUBJECT', 'Notificare comanda');
define('EMAIL_TEXT_ORDER_NUMBER', 'Numarul comenzii:');
define('EMAIL_TEXT_INVOICE_URL', 'Factura detaliata:');
define('EMAIL_TEXT_DATE_ORDERED', 'Data comenzii:');
define('EMAIL_TEXT_STATUS_UPDATE', 'Statusul comenzii dumneavoastra a fost modificat.' . "\n\n" . 'Status nou: %s' . "\n\n" . 'Te rugam sa raspunzi la acest e-mail daca ai nelamuriri..' . "\n\n" . "Iti multumim ca ai folosit serviciile noastre. \nEchipa ToolsZone.ro iti doreste o zi buna.");
define('EMAIL_TEXT_COMMENTS_UPDATE', 'Comentarii' . "\n\n%s\n\n");

define('ERROR_ORDER_DOES_NOT_EXIST', 'Eroare: Comanda inexistenta.');
define('SUCCESS_ORDER_UPDATED', 'Succes: Statusul comenzi a fost schimbat.');
define('WARNING_ORDER_NOT_UPDATED', 'Atentie: Statusul comenzi nu a fost schimbat.');
?>
