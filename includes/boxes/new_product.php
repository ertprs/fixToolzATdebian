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
  $info_box_contents[] = array('text' => 'Produs la comanda');

  new infoBoxHeading($info_box_contents, false, false);

  $info_box_contents = array();
  $info_box_contents[] = array('text' => '<table width="100%"><tr><td align="center"><a class="main"href="' . tep_href_link(FILENAME_NEW_PRODUCT) . '"><img src="images/basket.gif" border="0" /></a></td><td class="main" align="left"><a class="main"href="' . tep_href_link(FILENAME_NEW_PRODUCT) . '"><b>Produs dorit</b></a></td></tr></table>');

  new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- information_eof //-->
<?include(DIR_WS_BOXES . 'spacing.php');?>