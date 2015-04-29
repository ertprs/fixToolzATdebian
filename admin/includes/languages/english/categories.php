<?php
/*
  $Id: categories.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Categorii / Produse');
define('HEADING_TITLE_SEARCH', 'Cauta:');
define('HEADING_TITLE_GOTO', 'Dute la:');

define('TABLE_HEADING_ID', 'ID');
define('TABLE_HEADING_CATEGORIES_PRODUCTS', 'Categorii / Produse');
define('TABLE_HEADING_ACTION', 'Actiune');
define('TABLE_HEADING_STATUS', 'Status');

define('TEXT_NEW_PRODUCT', 'Produs nou in &quot;%s&quot;');
define('TEXT_CATEGORIES', 'Categorii:');
define('TEXT_SUBCATEGORIES', 'Subcategorii:');
define('TEXT_PRODUCTS', 'Produse:');
define('TEXT_PRODUCTS_PRICE_INFO', 'Pret:');
define('TEXT_PRODUCTS_TAX_CLASS', '');
define('TEXT_PRODUCTS_AVERAGE_RATING', 'Average Rating:');
define('TEXT_PRODUCTS_QUANTITY_INFO', 'Cantitate:');
define('TEXT_DATE_ADDED', 'Data adaugarii:');
define('TEXT_DATE_AVAILABLE', 'Data modificarii:');
define('TEXT_LAST_MODIFIED', 'Ultima modificare:');
define('TEXT_IMAGE_NONEXISTENT', 'IMAGINE INEXISTENTA');
define('TEXT_NO_CHILD_CATEGORIES_OR_PRODUCTS', 'Please insert a new category or product in this level.');
define('TEXT_PRODUCT_MORE_INFORMATION', 'For more information, please visit this products <a href="http://%s" target="blank"><u>webpage</u></a>.');
define('TEXT_PRODUCT_DATE_ADDED', 'Acest produs a fost adaugat in catalog la data de %s.');
define('TEXT_PRODUCT_DATE_AVAILABLE', 'This product will be in stock on %s.');

define('TEXT_EDIT_INTRO', 'Modifica categoria');
define('TEXT_EDIT_CATEGORIES_ID', 'Category ID:');
define('TEXT_EDIT_CATEGORIES_NAME', 'Numele:');
define('TEXT_EDIT_CATEGORIES_IMAGE', 'Category Image:');
define('TEXT_EDIT_SORT_ORDER', 'Pozitia in sortare');

define('TEXT_INFO_COPY_TO_INTRO', 'Alege o noua categorie unde vrei sa pui produsul');
define('TEXT_INFO_CURRENT_CATEGORIES', 'Catagorie:');

define('TEXT_INFO_HEADING_NEW_CATEGORY', 'Categorie noua');
define('TEXT_INFO_HEADING_EDIT_CATEGORY', 'Editeaza categoria');
define('TEXT_INFO_HEADING_DELETE_CATEGORY', 'Sterge categoria');
define('TEXT_INFO_HEADING_MOVE_CATEGORY', 'Muta categoria');
define('TEXT_INFO_HEADING_DELETE_PRODUCT', 'Sterge produsul');
define('TEXT_INFO_HEADING_MOVE_PRODUCT', 'Muta produsul');
define('TEXT_INFO_HEADING_COPY_TO', 'Copiaza in');

define('TEXT_DELETE_CATEGORY_INTRO', 'Esti sigur ca vrei sa stergi categoria?');
define('TEXT_DELETE_PRODUCT_INTRO', 'Esti sigur ca vrei sa stergi produsul?');

define('TEXT_DELETE_WARNING_CHILDS', '<b>ATENTIE:</b> Mai sunt %s subcategorii sub aceasta categorie!');
define('TEXT_DELETE_WARNING_PRODUCTS', '<b>ATENTIE:</b> Mai sunt %s produse in aceasta categorie!');

define('TEXT_MOVE_PRODUCTS_INTRO', 'Selecteaza unde vrei sa muti produsul<b>%s</b>');
define('TEXT_MOVE_CATEGORIES_INTRO', 'Selecteaza unde vrei sa muti categoria <b>%s</b>');
define('TEXT_LINK_CATEGORIES_INTRO', 'Selecteaza categoria care vrei sa o adaugi in <b>%s</b>');
define('TEXT_MOVE', 'Muta <b>%s</b> in:');

define('TEXT_NEW_CATEGORY_INTRO', 'Completeaza urmatoarele informatii pentru o noua categorie');
define('TEXT_CATEGORIES_NAME', 'Nume:');
define('TEXT_CATEGORIES_IMAGE', 'Category Image:');
define('TEXT_SORT_ORDER', 'Pozitia in sortare:');

define('TEXT_PRODUCTS_STATUS', 'Status produs:');
define('TEXT_PRODUCTS_DATE_AVAILABLE', 'Date Available:');
define('TEXT_PRODUCT_AVAILABLE', 'In stoc');
define('TEXT_PRODUCT_NOT_AVAILABLE', 'Nu este in stoc');
define('TEXT_PRODUCTS_MANUFACTURER', 'Products Manufacturer:');
define('TEXT_PRODUCTS_NAME', 'Nume produs:');
define('TEXT_PRODUCTS_DESCRIPTION', 'Products Description:');
define('TEXT_PRODUCTS_QUANTITY', 'Products Quantity:');
define('TEXT_PRODUCTS_MODEL', 'Products Model:');
define('TEXT_PRODUCTS_IMAGE', 'Poza produs:');
define('TEXT_PRODUCTS_URL', 'Products URL:');
define('TEXT_PRODUCTS_URL_WITHOUT_HTTP', '<small>(without http://)</small>');
define('TEXT_PRODUCTS_PRICE_NET', 'Pret produs (NET):');
define('TEXT_PRODUCTS_PRICE_GROSS', 'Pret Produs (BRUT):');
define('TEXT_PRODUCTS_WEIGHT', 'Products Weight:');

define('EMPTY_CATEGORY', 'Categorie goala');

define('TEXT_HOW_TO_COPY', 'Metoda de copiare:');
define('TEXT_COPY_AS_LINK', 'Link');
define('TEXT_COPY_AS_DUPLICATE', 'Duplicare');

define('ERROR_CANNOT_LINK_TO_SAME_CATEGORY', 'Eroare: Produsul nu se poate copia in aceasi categorie prin metoda "LINK".');
define('ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE', 'Error: Catalog images directory is not writeable: ' . DIR_FS_CATALOG_IMAGES);
define('ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST', 'Error: Catalog images directory does not exist: ' . DIR_FS_CATALOG_IMAGES);
define('ERROR_CANNOT_MOVE_CATEGORY_TO_PARENT', 'Eroare: Categoria nu poate fi mutata intr-o subcategorie.');

define('TEXT_PRODUCTS_SEO_URL', 'Products SEO URL:');
define('TEXT_EDIT_CATEGORIES_SEO_URL', 'Category SEO URL:');
define('TEXT_CATEGORIES_SEO_URL', 'Category SEO URL:');
?>