<?php
     
  require('includes/application_top.php');
  
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title>Oferta</title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<div id="spiffycalendar" class="text"></div>
<div id="wrapper"><!-- header //-->
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
    <td width="100%" valign="top">
    <form name="newsletter" action="http://www.toolszone.ro/admin/oferta.php" method="post" target="_blank">    
    <table border="0" width="100%" cellspacing="0" cellpadding="2">

    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">Oferta</td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
          
          <tr>
          <td>
          <table>
          <tr>
            <td valign="top"><b>Coduri produse :</b><br>(separate prin <br>virgula ',')</td>
            <td><?php echo tep_draw_textarea_field('codes', 'soft', 50, 15, '' ,'class="input"'); ?></td>
          </tr>
          <tr>            
          </tr>                                      
          <tr>
            <td valign="top"></td>
            <td><input type="submit" value="Genereaza">
            </td>
          </tr>
          </table></td></tr>
        </table></td>
      </tr>
    </table></td></form>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</div><div class="boxTextLine" style="size:100%"></div><div class="bottom"><div class="bottom_plus_design "><?include('plus_design.php')?></div></div></body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>