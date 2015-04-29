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

  $breadcrumb->add("Cum comand?", tep_href_link(FILENAME_HOW_I_ORDER));
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo "Cum comand? - ".TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" onLoad="MM_preloadImages('<?echo $cur_page=="1" ? '' : 'images/menu/menu_01_over.gif';?>','<?echo $cur_page=="2" ? '' : 'images/menu/menu_02_over.gif';?>','<?echo $cur_page=="3" ? '' : 'images/menu/menu_03_over.gif';?>','<?echo $cur_page=="4" ? '' : 'images/menu/menu_04_over.gif';?>','<?echo $cur_page=="5" ? '' : 'images/menu/menu_05_over.gif';?>','<?echo $cur_page=="6" ? '' : 'images/menu/menu_06_over.gif';?>')">
<div id="wrapper"><!-- header //-->
<?php
$cur_page="3";
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
		<td colspan="2" class="infoBoxHeading2" height="32" valign="top" align="left">&nbsp;&nbsp;Cum comand?</td>
		<td height="32" valign="top" width="8"><img src="images/infobox/corner_right_top.gif" alt="" border="0" height="32" width="8"></td>
	  </tr> 
      <tr>
		<td class="boxBgLeft" height="5">&nbsp;</td><td width="10"></td>
        <td class="main">
        	<br>
			<h1>1. Preturile ToolsZone.ro</h1>
			<ul>
				<li><a href="how_i_order.php#protocol_plata" >Care este protocolul de plata?</a></li>
				<li><a href="how_i_order.php#info_preturi" >Informatii despre preturile practicate</a></li>
			</ul>
			<h1>2. Ghidul utilizatorului</h1>
			<ul>
				<li><a href="how_i_order.php#creare_cont" >Cum creez un cont?</a></li>
				<li><a href="how_i_order.php#editare_cont" >Cum editez contul?</a></li>
				<li><a href="how_i_order.php#facilitati" >Facilitati si bonificatii aferente activitatii pe site</a></li>
			</ul>
			
			<h1>3. Protocol de cumparare</h1>
			<ul>
				<li><a href="how_i_order.php#gasire_produs" >Cum gasesc produsul?</a></li>
				<li><a href="how_i_order.php#comanda" >Cum comand online?</a></li>
				<li><a href="how_i_order.php#livrare" >Livrarea comenzii</a></li>
				<li><a href="how_i_order.php#contramandare" >Cum contramandez comanda?</a></li>
				<li><a href="how_i_order.php#voucher" >Cum folosesc Voucher-ul?</a></li>
			</ul>
			
			<h1>4. Protocol pentru returnari de produse</h1>
			<ul>
				<li><a href="how_i_order.php#returnare" >Protocol de returnare</a></li>
				<li><a href="how_i_order.php#conditii_returnare" >Conditii de returnare</a></li>
				<li><a href="how_i_order.php#certificare_returnare" >Certificarea returnarii</a></li>
			</ul>
			
			<h1>5. Serviciul clienti</h1>
			<ul>
				<li><a href="<?echo tep_href_link(FILENAME_CONTACT_US);?>" >Trimiteti email la serviciul clienti</a></li>
			</ul>
 			<br><br>   

			<h1>1. Preturile ToolsZone.ro</h1>
			
      
      <ul>
				<li>
          <a name="info_preturi" >Informatii despre preturile practicate</a><br><br>
				  Preturile afisate pe ToolsZone.ro includ TVA (24%), taxele de livrare sunt detaliate pe factura aferenta produselor dumneavoastra pentru comenzi mai mici de 200 RON, iar protocolul de discount este explicat in amanunt <a href="how_i_order.php#facilitati">aici</a>.
				  <br><br>Pentru comenzi mai mari de 200 RON, ToolsZone.ro va suporta integral cheltuielile de transport. De asemenea, toate produsele care au preturi mai mari de 200 RON vor beneficia automat de transport gratuit, dupa cum arata si simbolul care le insoteste. <img src="images/icons/14.png" /><br><br>
				</li>
				<li>
					<a name="protocol_plata" >Care este protocolul de plata?</a><br><br>
					Pentru a facilita accesul la produsele propuse de noi, ToolsZone.ro va ofera 3 modalitati de plata dupa cum urmeaza:<br><br>
					<ul>
					<li>1. Cu card de credit<br><br></li>
					<li>2. Plata ramburs la primirea produselor.<br><br></li>
					<li>3. Plata prin Ordin de Plata. Dupa ce ati primit factura proforma pentru comanda dumneavoastra , puteti achita prin ordin de plata. In momentul in care primim confirmarea de plata, produsele vor fi livrate catre dumneavoastra.<br><br></li>
					</ul>	
<br><b>1. Cum platesc cu card de credit pe ToolsZone.ro?</b> 
<br><br>ToolsZone.ro vinde on-line produsele sale prin intermediul serviciilor de plata on-line ale www.epayment.ro. In acest fel putem asigura clientilor nostri un serviciu sigur si eficient de comanda si plata on-line.
<br><br>Puteti cumpara produsele noastre on-line urmand 3 pasi:
<br><br>a. Selectati produsul pe care doriti sa-l achizitionati si apasati pe linkul "Plateste!"
<br><br>b. Introduceti datele dvs. personale.
<br><br>Aceste informatii sunt necesare pentru procesarea corecta si eficienta a comenzii plasate de catre dumneavoastra. Introducerea datelor personale se face utilizand o conexiune criptata SSL pe 128 biti. Mai multe informatii sunt disponibile in rubrica "Cat de sigur este pentru mine sa platesc on-line?". Informatiile personale nu sunt transferate catre alte terte parti, nici de catre ToolsZone.ro, nici de catre ePayment. Aveti grija sa completati formularul corect si sa indicati persoanele la care se va face facturarea si livrarea. Adresa de e-mail are importanta extrema deoarece informatiile legate de comanda si produsele comandate vor fi transmise via e-mail. O adresa de e-mail incorecta incetineste intregul proces de comanda a produsului. Mai mult de atat, ToolsZone.ro si ePayment nu vor putea sa va contacteze si sa va informeze asupra starii comenzii dumneavoastra.

<br><br>Daca adresa de livrare nu este aceeasi ca adresa de facturare, indicati acest lucru prin selectarea meniului de la sfarsitul formularului. Alegeti metoda dorita de plata si confirmati datele alegand "Continua".

<br><br>c. Introduceti datele despre credit/debit card:
<br><br>Daca ati ales metoda de plata "Card credit/debit" este necesar sa completati un formular cu informatiile despre cardul dumneavoastra. Pentru platile cu carduri de credit/debit emise sub sigla Visa si MasterCard (Visa/Visa Electron si Mastercard/Maestro) este folosit sistemul "3-D Secure" elaborat de aceste organizatii care asigura tranzactiilor on-line acelasi nivel de securitate ca cele realizate la bancomat sau in mediul fizic, la comerciant. "3-D Secure" asigura in primul rand ca <b>nici o informatie legata de cardul dumneavoastra nu este transferata sau stocata, la nici un moment de timp, pe serverele noastre sau pe serverele ePayment</b>, aceste date fiind direct introduse in sistemele Visa si MasterCard. In plus, "3-D Secure" este un sistem de autentificare a identitatii posesorilor de carduri in mediu electronic. Procesul de autentificare a posesorului cardului se face pe baza codului de securitate cunoscut numai de posesor si se desfasoara numai pe serverele Visa sau MasterCard, dupa caz. Sistemul "3-D Secure" permite efectuarea de cumparaturi on-line cu orice card emis sub licenta Visa sau MasterCard, inclusiv cele de debit si cele electronice Maestro si Visa Electron. Sunt acceptate si cardurile virtuale emise de aceste organizatii. 
<br><br>Daca tranzactia prin card a fost reusita, urmatorul ecran va multumeste pentru comanda pe site-ul nostru si va ofera informatiile relevante pentru comanda. Aceste informatii le veti primi de asemenea prin e-mail.
<br><br><b>Metode de plata</b>
<br><br>Puteti alege dintre urmatoarele metode de plata: card de credit/debit, transfer bancar/ordin de plata. Daca ati selectat card de credit/debit ca metoda de plata, este necesar sa completati un formular cu datele despre acesta. Daca tranzactia prin card a fost reusita, urmatorul ecran va multumeste pentru comanda pe site-ul nostru si va ofera informatiile relevante pentru comanda. Aceste informatii le veti primi de asemenea prin e-mail.

<br><br>Acceptam urmatoarele carti de credit/debit: Visa, Visa Electron, Mastercard/Eurocard, Maestro, inclusiv cardurile virtuale Visa sau MasterCard. Folosind ca metoda de plata card-ul bancar de credit/debit comanda dumneavoastra va fi procesata imediat. Pentru comenzi pentru care ati selectat metoda de plata prin transfer bancar, procesarea comenzii se va face doar dupa confirmarea creditarii contului ePayment cu suma transferata, introducandu-se astfel un decalaj de 2-4 zile, de la caz la caz.
        <br>  
          		
        </li>
			</ul>
			
			
			
			<h1>2. Ghidul utilizatorului</h1>
			<ul>
				<li><a name="creare_cont" >Cum creez un cont?</a><br><br>
				In partea dreapta sus a paginii accesati optiunea <a href="<?=tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL')?>">cont nou</a> care va va redirectiona catre un formular de inscriere pe site. Campurile marcate cu asterisc sunt obligatorii. 
				<br><br>
				</li>
				<li><a name="editare_cont" >Cum editez contul?</a><br><br>
				In meniul aferent contului dumneavoastra aveti optiunea <a href="<?php echo tep_href_link(FILENAME_ACCOUNT, '', 'SSL'); ?>">Editeaza cont</a>. Aceasta optiune va ajuta sa schimbati datele inregistrate la crearea contului. 
				<br><br></li>
				<li><a name="facilitati" >Facilitati si bonificatii aferente activitatii pe site</a><br><br>
<b>1.	Discount special pentru valoare pe comanda:</b> Orice comanda care depaseste suma de 5.000 RON va primi un discount de 3% pentru factura repectiva, orice comanda care depaseste suma de 10.000 RON va primi un discount de 5% pentru factura respectiva.<br><br>
<b>2.	Discount de fideliate in 3 trepte de valoare.</b> Pentru o clientii fideli care cumuleaza mai multe facturi cu sume care depasesc 20.000, 50.000 respectiv 70.000, se vor acorda discounturi dupa cum urmeaza:<br><br>
<ul>
<li>Valori cumulate de peste 20.000 RON primesc automat un discount de 3% pentru urmatoarele comenzi, pana la urmatoarea treapta de discount.<br><br></li>
<li>Valori cumulate de peste 50.000 RON primesc automat un discount de 5% pentru urmatoarele comenzi, pana la urmatoarea treapta de discount.<br><br></li>
<li>Valori cumulate de peste 70.000 RON primesc automat un discount de 7% pentru toate comenzile care vor urma.<br><br></li>
</ul>
<!--<b>3.	Discount special pentru valoare pe comanda.</b> Orice comanda care depaseste suma de 2.500 RON va primi un discount de 3% pentru factura repectiva, orice factura care depaseste suma de 5.000 RON va primi un discount de 5% pentru factura respectiva. <br><br>-->
<b>3.	Voucher valoric pentru recomandare.</b> La comanda primita din partea unui client nou care cumpara de pe site-ul nostru la recomandarea dumneavoastra si specifica acest lucru completand campul aferent din formularul de inregistrare pe site, dumneavostra veti primi un voucher valoric de 10 RON cu care puteti cumpara produse sau puteti acoperi o parte din sumele urmatoarelor facturi. <br><br>
				<br></li>
			</ul>
			
			<h1>3. Protocol de cumparare</h1>
			<ul>
				<li><a name="gasire_produs" >Cum gasesc produsul?</a><br><br>
				Pentru a putea gasi produsele care va intereseaza, puteti naviga prin meniul din dreapta aferent Categoriei de produse,de asemenea sa navigati prin sectiunile caracteristice specialistilor intr-unul dintre domeniile: Instalatori, Constructori, Electricieni, Mecanici, Electronisti sau Tamplari.<br><br>
        Optiunea <a href="<?php echo tep_href_link(FILENAME_ADVANCED_SEARCH); ?>">Cautare</a> va sta la dispozitie pentru a identifica produsul pe care il cautati in functie de denumirea, de codul de comanda sau de preturi.<br><br>
        In functie de disponbilitatea produselor in stoc sau de integrarea lor in promotii, vor fi atasate simboluri dupa cum urmeaza:
        <br><br> -	Produs aflat in solduri <img src="images/icons/4.png" />
        <br><br> -	Produs aflat in promotie <img src="images/icons/1.png" />
        <br><br> -	Produs nou pe site <img src="images/icons/5.png" />
        <br><br> -	Limita de stoc <img src="images/icons/2.png" />
        <br><br> -	Produs care nu se afla in stoc, dar se poate aduce la comanda <img src="images/icons/3.png" />
        <br><br> -	Produsul nu se afla in stoc momentan <img src="images/icons/12.png" />
				<br><br></li>
				<li><a name="comanda" >Cum comand online?</a><br><br>
				Dupa identificarea produselor care va intereseaza si selectarea numarului de produse pe care doriti sa le comandati, puteti opta pentru una dintre variantele de plata descrise mai sus in sectiunea <a href="how_i_order.php#protocol_plata">Protocol de plata</a>.
				<br><br>
				Produsele selectate de dumneavoastra spre cumparare vor fi detaliate in modulul Cos de Cumparaturi pentru a putea sti in orice clipa atat produsele selectate, cat si suma la care ajung cumparaturile dumneavoastra. Meniul corespunzator cosului de cumparaturi va pemite clientilor sa aiba control deplin asupra cumparaturilor pe care le efectueaza, facilitand fie o noua selectie a produselor aflate in cos, fie contramandarea totala a comenzii, fie cumpararea efectiva a produselor accesand butonul "Plateste".
				
				<br><br></li>
				<li><a name="livrare" >Livrarea comenzii</a><br><br>
				Livrarea comenzii se va face prin curierat, in functie de destinatia produselor. Pentru cazurile in care comenzile sunt mai mici de 200 de RON, taxele de livrare vor fi suportate in totalitate de catre comparator. Pentru comenzi mai mari de 200 RON taxele de livrare vor fi suportate in totalitate de ToolsZone.ro.
				<br><br>Taxele de livrare sunt evidentiate in factura si se calculeaza in conformitate cu exigentele firmelor de curierat cu care colaboram. Pentru mai multe detalii despre tarifele percepute pentru transport apasati <a target="_blank" href="http://www.posta-romana.ro/services/tarrif/marfuri_99/colete-postale-interne-cu-ramburs_18">aici</a>.
				<br><br>Produsele se afla in ambalajul original, sigilat, al producatorului si sunt asigurate pe tot parcursul transportului pana la livrare.
				<br><br>Conditii de livrare: Livrarea comenzilor se desfasoara dupa urmatorul program in ordinea sosirii lor:
				<br><br>- comenzile primite telefonic sau online <b>pana la ora 14.00</b>, se livreaza in aceeasi zi, in functie de disponibiliatea produselor in stoc, de numarul comenzilor si de ordinea sosirii lor. In cazuri exceptionale, produsele care nu se pot livra in aceeasi zi, din cauze independente de noi, se vor livra a doua zi in prima parte a zilei. 
				<br><br>- comenzile primite telefonic sau online <b>dupa ora 14.00</b>, se livreaza incepand cu urmatoarea zi, in functie de disponibilitatea produselor in stoc, numarul comenzilor si in ordinea sosirii lor.
				
				<br><br>Termen de livrare
				<br><br>- la adresa indicata de dumneavoastra si in termenul corespunzator categoriei de produse marcate cu unul dintre simbolurile:

<br><br>-	Livrare in 24 de ore <img src="images/icons/6.png" />
<br><br>-	Livrare in 48 de ore <img src="images/icons/8.png" />
<br><br>-	Livrare in 72 de ore <img src="images/icons/7.png" />
<br><br>-	Livrare in 7 zile <img src="images/icons/9.png" />
<br><br>-	Livrare in 14 zile <img src="images/icons/10.png" />
<br><br>-	Livrare in 30 de zile <img src="images/icons/11.png" /> <br><br>
</li>
				<li><a name="contramandare" >Cum contramandez comanda?</a><br><br>
				Pentru contramandarea unui produs care este deja adaugat in lista dumneavoastra de cumparaturi trebuie sa debifati produsul si sa reactualizati comanda prin apasarea butonului Actualizare.<br><br> 
De asemenea, pentru schimbarea numarului de bucati pe care vreti sa le cumparati, va rugam sa completati in casuta corespunzatoare Cantitatii numarul de bucati dorit de dumneavoastra.<br><br> 
Acelasi lucru este necesar si pentru contramandarea totala a comenzii, respectiv debifati toate produsele selectate si apasati butonul Actualizare. 
 
				<br><br></li>
				<li><a name="voucher" >Cum folosesc Voucher-ul?</a><br><br>
Voucherul de cumparaturi ToolsZone.ro va putea fi folosit momentan numai pe site-ul nostru. Pentru a putea intra in posesia unui Voucher, tot ce trebuie sa faceti este sa fiti client ToolsZone.ro, sa recomandati site-ul nostru unui cunoscut care sa cumpere produse de la noi in valoare de 300 RON. Pentru a recomanda website-ul ToolsZone.ro, va rugam sa accesati optiunea Recomanda Voucher din caseta de descriere a voucherului.<br><br> 
In momentul in care comanda facuta la recomandarea dumneavoastra se va finaliza, voucher-ul ToolsZone.ro va fi automat trimis pe mail, iar la urmatoarea comanda pe care o veti face, puteti beneficia de reducerile aferente numarului de vouchere pe care le detineti.<br><br> 
Fiecare voucher va avea inserat un cod unic pe care dumneavoastra urmeaza sa-l introduceti in formularul de comanda. Numarul de vouchere pe care le poate castiga un utilizator este nelimitat, in functie de recomandarile facute.<br><br> 
Daca nu se mentioneaza altfel, un voucher este valabil pentru o singura comanda. Voucher-ul este considerat folosit in cazul in care comanda a fost finalizata prin facturare. In cazul in care comanda la care ati folosit un voucher este anulata, voucherul este considerat nefolosit si poate fi incarcat in alta comanda.

				<br><br></li>
			</ul>
			
			<h1>4. Protocol pentru returnari de produse</h1>
			<ul>
				<li><a name="returnare" >Protocol de returnare</a><br><br>
Protocolul de baza pentru returnarile de produse este urmatorul:
<br><br>- Returnarile sunt acceptate pentru majoritatea produselor nedeteriorate daca acestea sunt returnate in termen de 10 de zile lucratoare de la data primirii coletului.  
<br><br>- Daca ToolsZone.ro a facut o eroare de livrare, vom fi mai mult decat bucurosi sa va inapoiem banii pentru produsele cumparate.
<br><br>- Unele dintre produsele noastre nu pot fi returnate conform protocolului producatorilor, aceste produse sunt marcate cu simbolul <img src="images/icons/13.png" />. 
<br><br>- Daca nu sunteti eligibili pentru returnare va rugam sa contactati direct producatorul sau sa verificati instructiunile de pe garantia aferenta.  
				
				<br><br></li>
				<li><a name="conditii_returnare" >Conditii de returnare</a><br><br>
Toate returnarile se vor face in urmatoarele conditii:
<br><br>1.	Produsul se va putea returna doar insotit de ambalajul original de protectie: cutie de carton, pungi de plastic, folie de protectie etc.
<br><br>2.	Codul de bare trebuie sa fie intact, ca atare nu deteriorati codul de pe cutiile de produse inainte de a verifica si testa produsele cumparate. In cazul deteriorarii acestora, produsele nu se vor putea inapoia. 
<br><br>3.	Produsele returnate vor fi insotite de toate accesoriile care le insotesc la livrare (transformatoare, baterii, manere etc.)
<br><br>4.	De asemenea, produsele returnate vor fi insotite de documentele aferente (manual tehnic, garantie, certificat de conformitate etc.) 
<br><br>Nota 1: Pentru a putea returna produsele, va rugam sa verificati cu atentie toate detaliile descrise mai sus. In cazul in care produsele pe care doriti sa le returnati nu indeplinesc toate conditiile de mai sus, ne pare rau sa va informam ca nu le veti putease mai pot returna.
<br><br>Nota 2: ToolsZone.ro nu se obliga sa plateasca taxele de transport in cazul returnarii nejustificate a produselor, acestea vor cadea in sarcina cumparatorului.
				
				<br><br></li>
				<li><a name="certificare_returnare" >Certificarea returnarii</a><br><br>
- Sunteti rugati sa asteptati 15 zile pentru a putea la randul nostru sa returnam produsele producatorului.
<br><br>- Perioada de returnare a produselor depinde de disponibilitatea lor in stoc.
<br><br>- In functie de cele mentionate anterior, la returnarea unui produs aveti posibilitatea fie sa cereti o inlocuire a produsului, fie sa optati pentru creditarea contului dumneavoastra cu suma corespunzatoare produselor cumparate, fie sa optati pentru returnarea integrala a sumei corespunzatoare costului. 

				
				<br><br></li>
			</ul>
			 			    
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