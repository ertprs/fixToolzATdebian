                                        <?php
/*
  $Id: coupons.php,v 1.01 2008/02/20 00:00:00 burt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce
  Copyright (c) 2008 Club osCommerce www.clubosc.com

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  
  $action = (isset($_GET['action']) ? $_GET['action'] : '');
  if (tep_not_null($action)) {
    switch ($action) {
      case 'setflag':
        tep_set_coupon_status($_GET['id'], $_GET['flag']);

        //tep_redirect(tep_href_link(FILENAME_COUPONS, (isset($_GET['page']) ? 'page=' . $_GET['page'] . '&' : '') . 'cID=' . $_GET['id'], 'NONSSL'));
        break;
      case 'insert':
        $coupon_code = tep_db_prepare_input($_POST['coupon_code']);
        $coupon_type = tep_db_prepare_input($_POST['coupon_type']);
        $coupon_amount = tep_db_prepare_input($_POST['coupon_amount']);
        $coupon_use = tep_db_prepare_input($_POST['coupon_use']);

        tep_db_query("insert into " . TABLE_COUPONS . " (coupon_code, coupon_type, coupon_amount, coupon_use, coupon_status) values ('" . tep_db_input($coupon_code) . "', '" . tep_db_input($coupon_type) . "', '" . tep_db_input($coupon_amount) . "', '" . tep_db_input($coupon_use) . "', '1')");

        //tep_redirect(tep_href_link(FILENAME_COUPONS, 'page=' . $_GET['page']));
        break;
      case 'save':
        $coupon_id = tep_db_prepare_input($_GET['cID']);
        $coupon_code = tep_db_prepare_input($_POST['coupon_code']);
        $coupon_type = tep_db_prepare_input($_POST['coupon_type']);
        $coupon_amount = tep_db_prepare_input($_POST['coupon_amount']);
        $coupon_use = tep_db_prepare_input($_POST['coupon_use']);

        tep_db_query("update " . TABLE_COUPONS . " set coupon_amount = '" . tep_db_input($coupon_amount) . "', coupon_code = '" . tep_db_input($coupon_code) . "', coupon_type = '" . tep_db_input($coupon_type) . "', coupon_use = '" . tep_db_input($coupon_use) . "' where coupon_id = '" . (int)$coupon_id . "'");

        //tep_redirect(tep_href_link(FILENAME_COUPONS, 'page=' . $_GET['page'] . '&cID=' . $coupon_id));
        break;
      case 'deleteconfirm':
        $coupon_id = tep_db_prepare_input($_GET['cID']);

        tep_db_query("delete from " . TABLE_COUPONS . " where coupon_id = '" . (int)$coupon_id . "'");

        //tep_redirect(tep_href_link(FILENAME_COUPONS, 'page=' . $_GET['page']));
        break;
    }
  }
  ?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_COUPON_CODE; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_COUPON_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  $coupons_query_raw = "select coupon_id, coupon_code, coupon_status, coupon_type, coupon_amount, coupon_use from " . TABLE_COUPONS;
  $coupons_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $coupons_query_raw, $coupons_query_numrows);
  $coupons_query = tep_db_query($coupons_query_raw);
  while ($coupons = tep_db_fetch_array($coupons_query)) {
    if ((!isset($_GET['cID']) || (isset($_GET['cID']) && ($_GET['cID'] == $coupons['coupon_id']))) && !isset($cInfo)) {
      $cInfo = new objectInfo($coupons);
    }

    if (isset($cInfo) && is_object($cInfo) && ($coupons['coupon_id'] == $cInfo->coupon_id)) {
      echo '                  <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_COUPONS, 'cID=' . $coupons['coupon_id']) . '\'">' . "\n";
    } else {
      echo '                  <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_COUPONS, 'page=' . $_GET['page'] . '&cID=' . $coupons['coupon_id']) . '\'">' . "\n";
    }
?>
                <td class="dataTableContent"><?php echo $coupons['coupon_code']; ?></td>
                <td  class="dataTableContent" align="center">
<?php
      if ($coupons['coupon_status'] == '1') {
        echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_COUPONS, 'action=setflag&flag=0&id=' . $coupons['coupon_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
      } else {
        echo '<a href="' . tep_href_link(FILENAME_COUPONS, 'action=setflag&flag=1&id=' . $coupons['coupon_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
      }                
?></td>
                <td class="dataTableContent" align="right"><?php if (isset($cInfo) && is_object($cInfo) && ($coupons['coupon_id'] == $cInfo->coupon_id)) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif'); } else { echo '<a href="' . tep_href_link(FILENAME_COUPONS, 'page=' . $_GET['page'] . '&cID=' . $coupons['coupon_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
  }
?>
              <tr>
                <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $coupons_split->display_count($coupons_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_COUPONS); ?></td>
                    <td class="smallText" align="right"><?php echo $coupons_split->display_links($coupons_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
                  </tr>
                </table></td>
              </tr>
<?php
  if (empty($action)) {
?>
              <tr>
                <td align="right" colspan="3" class="smallText"><?php echo '<a href="' . tep_href_link(FILENAME_COUPONS, 'page=' . $_GET['page'] . '&cID=' . $cInfo->coupon_id . '&action=new') . '">' . tep_image_button('button_insert.gif', IMAGE_INSERT) . '</a>'; ?></td>
              </tr>
<?php
  }
?>
            </table></td>
<?php
  $heading = array();
  $contents = array();

      $coupon_use_array = array(array('id' => '1', 'text' => ENTRY_MULTI),
                                array('id' => '0', 'text' => ENTRY_ONCE));

      $coupon_type_array = array(array('id' => '1', 'text' => ENTRY_FIXED),
                                 array('id' => '0', 'text' => ENTRY_PERCENT));
                              
  switch ($action) {
    case 'new':
      $heading[] = array('text' => '<b>' . TEXT_HEADING_NEW_COUPON . '</b>');

      $contents = array('form' => tep_draw_form('coupons', FILENAME_COUPONS, 'action=insert'));
      $contents[] = array('text' => TEXT_NEW_INTRO);
      $contents[] = array('text' => '<br>' . TEXT_COUPON_CODE . '<br>' . tep_draw_input_field('coupon_code'));      
      $contents[] = array('text' => '<br>' . TEXT_COUPON_AMOUNT . '<br>' . tep_draw_input_field('coupon_amount'));
      $contents[] = array('text' => '<br>' . TEXT_COUPON_TYPE . '<br>' . tep_draw_pull_down_menu('coupon_type', $coupon_type_array));
      $contents[] = array('text' => '<br>' . TEXT_COUPON_USAGE . '<br>' . tep_draw_pull_down_menu('coupon_use', $coupon_use_array));
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_COUPONS, 'page=' . $_GET['page'] . '&cID=' . $_GET['cID']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'edit_coupon':                 
      $heading[] = array('text' => '<b>' . TEXT_HEADING_EDIT_COUPON . '</b>');

      $contents = array('form' => tep_draw_form('coupons', FILENAME_COUPONS, 'page=' . $_GET['page'] . '&cID=' . $cInfo->coupon_id . '&action=save'));
      $contents[] = array('text' => TEXT_EDIT_INTRO);
      $contents[] = array('text' => '<br>' . TEXT_COUPON_CODE . '<br>' . tep_draw_input_field('coupon_code', $cInfo->coupon_code));      
      $contents[] = array('text' => '<br>' . TEXT_COUPON_TYPE . '<br>' . tep_draw_pull_down_menu('coupon_type', $coupon_type_array, $cInfo->coupon_type));
      $contents[] = array('text' => '<br>' . TEXT_COUPON_AMOUNT . '<br>' . tep_draw_input_field('coupon_amount', $cInfo->coupon_amount));
      $contents[] = array('text' => '<br>' . TEXT_COUPON_USAGE . '<br>' . tep_draw_pull_down_menu('coupon_use', $coupon_use_array, $cInfo->coupon_use));
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_COUPONS, 'page=' . $_GET['page'] . '&cID=' . $cInfo->coupon_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_HEADING_DELETE_COUPON . '</b>');

      $contents = array('form' => tep_draw_form('coupon', FILENAME_COUPONS, 'page=' . $_GET['page'] . '&cID=' . $cInfo->coupon_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_DELETE_INTRO);
      $contents[] = array('text' => '<br><b>' . $cInfo->coupon_code . '</b>');
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_COUPONS, 'page=' . $_GET['page'] . '&cID=' . $cInfo->coupon_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (isset($cInfo) && is_object($cInfo)) {
        $heading[] = array('text' => '<b>' . strtoupper($cInfo->coupon_code) . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_COUPONS, 'cID=' . $cInfo->coupon_id . '&action=edit_coupon') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_COUPONS, 'page=' . $_GET['page'] . '&cID=' . $cInfo->coupon_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
        $contents[] = array('text' => '<br>' . TEXT_COUPON_TYPE . '<br />' . tep_get_coupon_type($cInfo->coupon_type));
        $contents[] = array('text' => '<br>' . TEXT_COUPON_AMOUNT . '<br />' . $cInfo->coupon_amount);
        $contents[] = array('text' => '<br>' . TEXT_COUPON_USAGE . '<br />' . tep_get_coupon_use($cInfo->coupon_use));
      }
      break;
  }

  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
