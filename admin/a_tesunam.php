<?php
/*
  $Id: stats_customers.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

require('includes/application_top.php');

if(isset($_GET['sterge']) && (is_numeric($_GET['id']))){

    $query = "delete from all_contacts where id='".$_GET['id']."'";
    $result = mysql_query($query);

    if (!$result) {
        die('Invalid query: ' . mysql_error());
    }
}

if(isset($_GET['xls'])){
    chdir('includes/classes/phpxls');
    require_once 'Writer.php';
    chdir('../../..');

    $workbook = new Spreadsheet_Excel_Writer();
    $worksheet =& $workbook->addWorksheet('Produse');
//$worksheet->setColumn(0,0,0)
    $workbook->setVersion(8);


    function xlsBOF() {
        echo pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);
        return;
    }

    function xlsEOF() {
        echo pack("ss", 0x0A, 0x00);
        return;
    }

    function xlsWriteNumber($Row, $Col, $Value) {
        echo pack("sssss", 0x203, 14, $Row, $Col, 0x0);
        echo pack("d", $Value);
        return;
    }

    function xlsWriteLabel($Row, $Col, $Value ) {
        global  $workbook,$worksheet;

        $format_reg =& $workbook->addFormat();
        $format_reg->setColor('black');
        $format_reg->setFontFamily('Arial');
        $format_reg->setSize(8);

        $worksheet->write($Row, $Col, $Value, $format_reg);

        return;
    }
    function xlsWriteHeader($Row, $Col, $Value ) {
        global  $workbook,$worksheet;

        $format_und =& $workbook->addFormat();
        $format_und->setBottom(2);//thick
        $format_und->setBold();
        $format_und->setColor('black');
        $format_und->setFontFamily('Arial');
        $format_und->setSize(8);

        $worksheet->write($Row, $Col, $Value, $format_und);

        return;
    }

    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");;
    header("Content-Disposition: attachment;filename=export.xls "); // a1&#129;a&#184;Ya1&#8240;a&#184;&#167;a&#184;&#8482;a&#184;&#181;a1^a&#184;&#129;a1&#8225;a&#184;&#352;a&#184;&#183;a1^a&#184;&#173;a1&#8222;a&#184;Ya&#184;Ya1O
    header("Content-Transfer-Encoding: binary ");

    // XLS Data Cell

    xlsWriteHeader(0,0,"NUME");
    xlsWriteHeader(0,1,"TELEFON");
    xlsWriteHeader(0,2,"EMAIL");
    xlsWriteHeader(0,3,"SUBIECT");
    xlsWriteHeader(0,4,"DETALII");
    xlsWriteHeader(0,5,"CAND_SUNAM");


    $xlsRow = 1;

    $customers_query_raw = "select * from all_contacts where tip=1 order by id DESC";


    $rows = 0;
    $customers_query = tep_db_query($customers_query_raw);
    while ($customers = tep_db_fetch_array($customers_query)) {
        $rows++;

        xlsWriteLabel($rows,0,$customers['nume']);
        xlsWriteLabel($rows,1,$customers['telefon']);
        xlsWriteLabel($rows,2,$customers['email']);
        xlsWriteLabel($rows,3,$customers['subiect']);
        xlsWriteLabel($rows,4,$customers['comment']);
        xlsWriteLabel($rows,5,$customers['cand_sunam']);

    }

    $workbook->send("export_te_sunam_.xls");
    $workbook->close();
}

require(DIR_WS_CLASSES . 'currencies.php');
$currencies = new currencies();
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
    <title>Mesaje de la clienti</title>
    <link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
    <script language="javascript" src="includes/general.js"></script>
    <style type="text/css">
        .button {
            font: bold 11px Arial;
            text-decoration: none;
            background-color: #EEEEEE;
            color: #333333;
            padding: 2px 6px 2px 6px;
            border-top: 1px solid #CCCCCC;
            border-right: 1px solid #333333;
            border-bottom: 1px solid #333333;
            border-left: 1px solid #CCCCCC;
        }
    </style>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
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
                </table></td><td>&nbsp;</td>
            <!-- body_text //-->
            <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <tr>
                        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td class="pageHeading">Clienti inscrisi in te sunam noi</td>
                                    <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
                                </tr>
                            </table></td>
                    </tr>
                    <tr>
                        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                                <tr>
                                    <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                                            <tr class="dataTableHeadingRow">
                                                <td class="dataTableHeadingContent">Nume</td>
                                                <td class="dataTableHeadingContent">Telefon</td>
                                                <td class="dataTableHeadingContent">Subiect</td>
                                                <td class="dataTableHeadingContent">Detalii</td>
                                                <td class="dataTableHeadingContent">E-mail&nbsp;</td>
                                                <td class="dataTableHeadingContent" align="right">Cand sunam</td>
                                                <td class="dataTableHeadingContent" align="right">Optiuni</td>
                                            </tr>
                                            <?php
                                            if (isset($_GET['page']) && ($_GET['page'] > 1)) $rows = $_GET['page'] * MAX_DISPLAY_SEARCH_RESULTS - MAX_DISPLAY_SEARCH_RESULTS;
                                            $customers_query_raw = "select * from all_contacts where tip=1 order by id DESC";
                                            $customers_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $customers_query_raw, $customers_query_numrows);
                                            // fix counted customers
                                            $customers_query_numrows = tep_db_query("select id from all_contacts where tip=2 group by id");
                                            $customers_query_numrows = tep_db_num_rows($customers_query_numrows);

                                            $rows = 0;
                                            $customers_query = tep_db_query($customers_query_raw);
                                            while ($customers = tep_db_fetch_array($customers_query)) {
                                                $rows++;

                                                if (strlen($rows) < 2) {
                                                    $rows = '0' . $rows;
                                                }
                                                ?>
                                                <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href='mailto:<?php echo $customers['email']; ?>'">
                                                    <td class="dataTableContent"><?php echo $customers['nume']; ?></td>
                                                    <td class="dataTableContent"><?php echo $customers['telefon']; ?></td>
                                                    <td class="dataTableContent"><?php echo $customers['subiect']; ?></td>
                                                    <td class="dataTableContent"><?php echo $customers['comment']; ?></td>
                                                    <td class="dataTableContent"><?php echo $customers['email']; ?></td>
                                                    <td class="dataTableContent" align="right"><?php echo $customers['cand_sunam']; ?>&nbsp;</td>
                                                    <td class="dataTableContent" align="right">&nbsp;&nbsp;<a style="color: red" href="<?=$_SERVER['PHP_SELF'].'?sterge=1&id='.$customers['id']?>">sterge[x]</a>&nbsp;&nbsp;<a href="importa">importa[&nabla;]</a></td>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        </table></td>
                                </tr>
                                <tr>
                                    <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                                            <tr>
                                                <td class="smallText" valign="top"><?php echo $customers_split->display_count($customers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_CUSTOMERS); ?></td>
                                                <td class="smallText" align="right"><?php echo $customers_split->display_links($customers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?>&nbsp;</td>
                                            </tr>
                                        </table></td>
                                </tr>
                            </table></td>
                    </tr>
                </table>
                <br>&nbsp;&nbsp;&nbsp;

                <a class="button" href="<?=$_SERVER['PHP_SELF'].'?xls=1'?>">Exporta in excel</a>

            </td>
            <!-- body_text_eof //-->
        </tr>
    </table>
    <!-- body_eof //-->

    <!-- footer //-->
    <?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
    <!-- footer_eof //-->
</div><div class="boxTextLine" style="size:100%"></div><div class="bottom"><div class="bottom_plus_design "><?include('plus_design.php')?></div></div></body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
