<?php
/*
  $Id: contact_us.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CONTACT_US);

  $error = false;
  if (isset($_GET['action']) && ($_GET['action'] == 'send')) {
    $name = tep_db_prepare_input($_POST['name']);
    $email_address = tep_db_prepare_input($_POST['email']);
    $enquiry = tep_db_prepare_input($_POST['enquiry']);

    if (tep_validate_email($email_address)) {
      tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, EMAIL_SUBJECT, $enquiry, $name, $email_address);

      tep_redirect(tep_href_link(FILENAME_CONTACT_US, 'action=success'));
    } else {
      $error = true;

      $messageStack->add('contact', ENTRY_EMAIL_ADDRESS_CHECK_ERROR);
    }
  }

  $breadcrumb->add("Termeni si conditii", tep_href_link(FILENAME_TERMS));
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo "Termeni si conditii - ".TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" onLoad="MM_preloadImages('<?echo $cur_page=="1" ? '' : 'images/menu/menu_01_over.gif';?>','<?echo $cur_page=="2" ? '' : 'images/menu/menu_02_over.gif';?>','<?echo $cur_page=="3" ? '' : 'images/menu/menu_03_over.gif';?>','<?echo $cur_page=="4" ? '' : 'images/menu/menu_04_over.gif';?>','<?echo $cur_page=="5" ? '' : 'images/menu/menu_05_over.gif';?>','<?echo $cur_page=="6" ? '' : 'images/menu/menu_06_over.gif';?>')">
<div id="wrapper"><!-- header //-->
<?php
 require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td><td class="spacingTable">&nbsp;&nbsp;</td>
<!-- body_text //-->
    <td width="100%" valign="top"><?php echo tep_draw_form('contact_us', tep_href_link(FILENAME_CONTACT_US, 'action=send')); ?>
    
    <table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">

      <tr>
		<td height="32" valign="top" width="8"><img src="images/infobox/corner_left_top.gif" alt="" border="0" height="32" width="8"></td>
		<td colspan="2" class="infoBoxHeading2" height="32" valign="top" align="left">&nbsp;&nbsp;Termeni si conditii</td>
		<td height="32" valign="top" width="8"><img src="images/infobox/corner_right_top.gif" alt="" border="0" height="32" width="8"></td>
	  </tr> 
      <tr>
		<td class="boxBgLeft" height="5">&nbsp;</td><td width="10"></td>
        <td class="main">
        	<br>

            <b>TERMENI SI CONDITII DE FOLOSINTA STABILITI INTRE TOOLSZONE.RO SI UTILIZATOR</b><br><br>
            Acest Website a fost conceput ca un serviciu gratuit pe care s il pune la dispozitia Utilizatorului. Folosirea acestui Website de catre utilizatori si acceptarea fara nici o modificare a termenilor de folosinta, a conditiilor si notificarilor stipulate prin prezenta, constituie intelegerea si acceptarea lor. Daca nu sunteti de acord cu acesti termeni si conditii, atunci nu sunteti autorizat sa utilizati acest Website.
            <br><br><b>IDENTITATEA LEGALA</b><br><br>
            ToolsZone.ro este numele unui domeniu comercial care apartine S.C. Virtual Tools S.R.L. cu sediul in Brasov, Romania.
            <br><br><b>CONFIDENTIALITATE</b><br><br>
            
            Toate datele pe care dumneavoastra le veti furniza prin colaborare cu ToolsZone.ro vor fi folosite exclusiv in scopul declarat de website-ul nostru, respectiv atat datele necesare completarii formularului de inscriere pe site, cat si cele necesare livrarii produselor comandate de dumneavoastra vor ramane confidentiale si nu vor fi transmise sub nici o forma unei terte parti, conform <a target="_blank" href="http://legislatie.resurse-pentru-democratie.org/677_2001.php">LEGII NR. 677/2001 PENTRU PROTECTIA PERSOANELOR  CU PRIVIRE LA PRELUCRAREA DATELOR CU CARACTER PERSONAL SI LIBERA CIRCULATIE A ACESTOR DATE</a> (publicata in Monitorul Oficial nr. 790 din 12 decembrie 2001).
            
            <br><br>Conform legislatiei in vigoare, utilizatorii ToolsZone.ro au dreptul sa se opuna prelucrarii datelor personale si sa solicite stergerea lor.
            
            <br><br><b>DESCARCARE DE SOFTWARE</b><br><br>
            Daca ToolsZone.ro ofera spre descarcare software, orice utilizare a acestui soft presupune luarea in considerare a conditiilor de primire a licentei de utilizare din partea furnizorului de drept sau a producatorului particular. Aceste conditii sunt transferate odat� cu programul si/sau se obtin de la furnizorul de drept/producatorul programului. Utilizatorul nu va putea instala un astfel de program inainte sau fara a accepta termenii si conditiile de primire a licentei de utilizare.
            
            <br><br>Programele care vor putea fi descarcate vor fi in general destinate utilizarii in scopuri personale sau pentru testare. Folosirea lor in alte scopuri cade sub incidenta Codului Civil si Penal. Licentele obligatorii de utilizare nu vor fi afectate de cadrul legal aplicat pe un anumit teritoriu. 
            
            <br><br>ToolsZone.ro nu se face raspunzator pentru daune care rezulta direct sau indirect din folosirea necorespunz�toare de date descarcate de pe Website-ul nostru.
            
            <br><br><b>CONDITII PERSONALE SI NONCOMERCIALE DE FOLOSINTA</b><br><br>
            La accesarea acestui Website nu aveti autoritatea sa modificati, copiati, distribuiti, transmiteti, expuneti, reproduceti, publicati, patentati, creati un model asociat cu Website-ul, transferati, sau sa vindeti informatii sau servicii obtinute prin colaborare cu ToolsZone.ro.
            <br><br><b>DISCLAIMER DE RASPUNDERE</b><br><br>
            ToolsZone.ro foloseste surse de incredere pentru a verifica acuratetea informatiilor postate pe acest Website. Trebuie notificat totusi faptul ca ToolsZone.ro nu garanteaza ca informatiile vor fi in totalitate lipsite de erori, iar utilizatorul trebuie sa stie faptul ca informatiile, produsele si serviciile furnizate de acest Website pot include neconcordante sau erori de redactare. Periodic, vor fi operate imbunatatiri. ToolsZone.ro isi atribuie privilegiul de a aduce oricand Website-ului schimbari sau adaugiri.
            <br><br>ToolsZone.ro si/sau partenerii lor nu se fac responsabili pentru eligibilitatea informatiilor, produselor si serviciilor furnizate pe Website. Echipa ToolsZone.ro va folosi toata indemanarea si priceperea de care dispune pentru a putea indeplini adecvat serviciile furnizate utilizatorilor. ToolsZone.ro se exonereaz� de orice garantii, termeni �i conditii cu privire la informatiile, produsele si serviciile furnizate, incluzand garan�iile implicite, termenii si conditiile impuse de statutul sau, directe sau indirecte, sau de cele care privesc standardul de calitate, sau compatibilitatea lor cu un anumit scop, titlu sau actiuni legale.
            <br><br>Sub nici o forma Website-ul ToolsZone.ro nu va fi responsabil pentru nici o pierdere provenita din utilizarea lui sau asociata cu utilizarea lui, ca de altfel nici de intarzierea sau imposibilitatea de a utiliza acest Website, sau fata de nici o informatie, produs sau serviciu obtinut prin intermediul Website-ului sau provenite cumva din utilizarea lui, fie ca se bazeaza pe contract, numai pe incredere sau din contra, nici macar in cazul in care Website-ul ToolsZone.ro a fost avertizat de aceasta posibilitate.
            <br><br>Niciuna dintre conditiile de utilizare descrise mai sus nu va afecta in nici un fel drepturile statutare ale utilizatorului. 
            <br><br><b>NU SE PERMITE UTILIZAREA ILEGALA SAU NEAPROBATA A WEBSITE-ULUI</b><br><br>
            Ca o conditie de accesare a acestui Website, utilizatorul garanteaza ca nu va folosi Website-ul pentru nici un scop care contravine legilor in vigoare sau care este interzis conform termenilor, conditiilor si notificarilor stipulate.
            <br><br><b>LINK-URI CATRE WEBSITE-URI PARTENERE</b><br><br>
            Acest Website poate contine hyperlink-uri detinute de alte persoane din afara site-ului ToolsZone.ro. Asemenea link-uri sunt atasate numai pentru referinta. Includerea unor asemenea linkuri pe Website-ul ToolsZone.ro, sau asocierea lui cu acestea, nu-i confera acestuia nici un drept asupra lor. ToolsZone.ro nu monitorizeaza si nici nu garanteaza continutul website-urilor indicate. ToolsZone.ro nu face garantia si nu accepta nici un fel de responsabilitate in accesarea acestor website-uri straine.
            <br><br><b>MODIFICAREA ACESTOR TERMENI SI CONDITII</b><br><br>
            ToolsZone.ro isi rezerva dreptul de a schimba termenii, conditiile si notificarile de utilizare ale Website-ului.
            



			 			    
        </td>
        <td class="boxBgRight" height="5">&nbsp;</td>
      </tr>
	  <tr>
		<td><?=tep_image(DIR_WS_IMAGES . 'infobox/corner_left_bottom.gif')?></td>  
		<td colspan="2" width="100%" style="border-bottom: 1px solid #4791d9; font-size:1px;">&nbsp;</td>  
		<td><?=tep_image(DIR_WS_IMAGES . 'infobox/corner_right_bottom.gif')?></td>      
	  </tr> 
    </table></td></tr>
        </table></form></td>
<td class="spacingTable">&nbsp;&nbsp;</td><!-- body_text_eof //-->
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
<!-- right_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
<!-- right_navigation_eof //-->
    </table></td>
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->

<!-- footer_eof //-->
<br>
</div><?php require(DIR_WS_INCLUDES . 'footer.php'); ?></body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
