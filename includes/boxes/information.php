<?php
/*
  $Id: information.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- information //-->
          <tr>
            <td>
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => BOX_HEADING_INFORMATION);

  new infoBoxHeading($info_box_contents, false, false);

  $info_box_contents = array();
//  $info_box_contents[] = array('text' => '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_HOW_I_ORDER) . '">Cum comand?</a><br>' .
//                                         '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_ABOUT) . '">Despre SMEX</a><br>' .
//                                         '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CONDITIONS) . '">Conditii de utilizare</a><br>' .
//                                         '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CONTACT_US) . '">' . BOX_INFORMATION_CONTACT . '</a>');
  $info_box_contents[] = array('text' => '&nbsp;&nbsp;<b>Preluare comenzi online:</b><br>' .
                                         '&nbsp;&nbsp;&nbsp;&nbsp;NON-STOP<br><br>' .
                                         '&nbsp;&nbsp;<b>Livrare comenzi:</b><br>' .
                                         '&nbsp;&nbsp;&nbsp;&nbsp;LUNI-SAMBATA<br>' .
                                         '&nbsp;&nbsp;&nbsp;&nbsp;14:00-21:00<br><br>' .
                                         '&nbsp;Pentru comenzile primite pana<br>' .
                                         '&nbsp;in ora 12:00 livrarea se face<br>' .
                                         '&nbsp;in aceasi zi.<br><br>' .
                                         '&nbsp;Livrarea este gratuita in<br>' .
                                         '&nbsp;Brasov si Stupini<br><br>' .
                                         '&nbsp;Pentru Sacele,Harman,Ghimbav<br>' .
                                         '&nbsp;Sanpetru si Cristian se percepe<br>' .
                                         '&nbsp;o taxa de transport de 5 RON<br><br>' .
                                         '&nbsp;Comanda minima de 100 RON<br>');

  new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- information_eof //-->
