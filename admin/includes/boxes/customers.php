<?php
/*
  $Id: customers.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- customers //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_CUSTOMERS,
                     'link'  => tep_href_link(FILENAME_CUSTOMERS, 'selected_box=customers'));

  if ($selected_box == 'customers') {
    $contents[] = array('text'  => '<a href="' . tep_href_link(FILENAME_CUSTOMERS, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_CUSTOMERS_CUSTOMERS . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_ORDERS, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_CUSTOMERS_ORDERS . '</a><br>'.
                                   '<a href="' . tep_href_link('contact.php', '', 'NONSSL') . '" class="menuBoxContentLink">Mesaje de la clienti</a><br>'.
                                   '<a href="' . tep_href_link('oferta_form.php', '', 'NONSSL') . '" class="menuBoxContentLink">Formular Oferta</a><br>'.
                                   '<a href="' . tep_href_link('a_tesunam.php', '', 'NONSSL') . '" class="menuBoxContentLink">Te sunam noi</a><br>'.
                                   '<a href="' . tep_href_link('a_newsletter.php', '', 'NONSSL') . '" class="menuBoxContentLink ">Abonari newsletter</a><br>'.
  ($admin['username'] == "admin"?'<a href="' . tep_href_link(FILENAME_COUPONS) . '" class="menuBoxContentLink">' . BOX_TOOLS_COUPONS . '</a>':"") );
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- customers_eof //-->
