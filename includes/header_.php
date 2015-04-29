<?php

$top_cat_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '0' and c.categories_id = cd.categories_id and cd.language_id='" . (int)$languages_id ."' order by sort_order, cd.categories_name");

?>
<link rel="stylesheet" href="css/lightbox.css" type="text/css" media="screen" />
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<link rel="stylesheet" href="http://jqueryui.com/resources/demos/style.css" />
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script src="js/anylinkmenu.js" type="text/javascript"></script>
<!--[if lte IE 8]>
<style type='text/css'>
</style>
<![endif]-->

<!--<script src="js/prototype.js" type="text/javascript"></script><script src="js/scriptaculous.js?load=effects" type="text/javascript"></script><script src="js/lightbox++.js" type="text/javascript"></script>-->
<style>
    .ui-autocomplete-category {
        font-weight: bold;
        padding: .2em .4em;
        margin: .8em 0 .2em;
        line-height: 1.5;
    }
</style>
<script>
    var anylinkmenu2={divclass:'anylinkmenu', inlinestyle:'display:none;width:126px; background:#ff0000', linktarget:''} //Second menu variable. Same precaution.
    anylinkmenu2.items=[
        ["Contul Meu", "<?php echo tep_href_link(FILENAME_ACCOUNT, '', 'SSL'); ?>"],
        ["Lista favorite", "<?=tep_href_link(FILENAME_FAV, '', 'SSL')?>"],
        ["Comenzile mele", "<?=tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL')?>"],
        ["Agenda adrese", "<?=tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL')?>"],["Agenda adrese", "<?=tep_href_link('test', '', 'SSL')?>"],
        ["Logout", "<?=tep_href_link(FILENAME_LOGOFF, '', 'SSL')?>"]
    ]
</script>
<script>
    $.widget( "custom.catcomplete", $.ui.autocomplete, {
        _renderMenu: function( ul, items ) {
            var that = this,
                currentCategory = "";
            $.each( items, function( index, item ) {
                if ( item.category != currentCategory ) {
                    ul.append( "<li class='ui-autocomplete-category'>" + item.category + "</li>" );
                    currentCategory = item.category;
                }
                that._renderItemData( ul, item );
            });
        }
    });
</script>
<script>
    $(function() {
        var cache = {};
        $( "#searchField" ).catcomplete({
            minLength: 2,
            source: function( request, response ) {
                var term = request.term;
                if ( term in cache ) {
                    response( cache[ term ] );
                    return;
                }

                $.getJSON( "ajax_search.php", request, function( data, status, xhr ) {
                    cache[ term ] = data;
                    response( data );
                });
            },
            select: function( event, ui ) {
                window.location.href = ui.item.url;
            },
            position: {
                at: "right bottom"
            },
            open: function(){$('ul.ui-menu').width(480)}

        })
        .data( "customCatcomplete" )._renderItem = function( ul, item ) {
            var inner_html = '<a><div class="list_item_container"><div class="image"><img src="' + item.img + '"></div><div class="label">' + item.label + '</div><div class="price">' + item.price + ' Lei' + '</div></div></a>';
            if (item.category == 'Produse:'){
            return $("<li>")
            .append( inner_html )
            .appendTo( ul );
            } else
            {return $("<li>").append( "<a><div class='category'>" + item.label + "</div></a>" ).appendTo( ul )};
        };
    });
</script>
<!--***to be removed-->
<script type="text/JavaScript">
    <!--
    function MM_swapImgRestore() { //v3.0
        var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
    }

    function MM_preloadImages() { //v3.0
        var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
            var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
                if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
    }

    function MM_findObj(n, d) { //v4.01
        var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
            d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
        if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
        for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
        if(!x && d.getElementById) x=d.getElementById(n); return x;
    }

    function MM_swapImage() { //v3.0
        var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
            if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
    }


    //-->
</script>
<link rel="icon" type="image/png"  href="images/icons/FAVICON.png">
<table width="978" height="109" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td class="header_top" align="bottom"><?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_image(DIR_WS_IMAGES . 'store_logo.gif', STORE_NAME) . '</a>'; ?></td>
        <td class="header_top" width="512" valign="bottom" align="center">
            <script src="js/AC_RunActiveContent.js" type="text/javascript"></script>
            <script src="js/AC_ActiveX.js" type="text/javascript"></script>
            <script type="text/javascript">
                AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0','width','448','height','92','src','images/flash/comenzi-telefon','quality','high','pluginspage','http://www.macromedia.com/go/getflashplayer','movie','images/flash/header_banner' ); //end AC code
            </script><noscript><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="448" height="92">
                    <param name="movie" value="images/flash/header_banner.swf" />
                    <param name="quality" value="high" />
                    <embed src="images/flash/header_banner.swf" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="448" height="92"></embed>
                </object></noscript>


            <!--<img src="images/header/header_banner.gif">-->


        </td>
        <td class="header_top" width="220" valign="top">
            <?php if (!tep_session_is_registered('customer_id')) {?>
                <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td colspan="2" class="header_label" height="20" valign="bottom">Autentificare:</td>
                    </tr>
                    <tr>
                        <td colspan="2" height="25">
                            <?php echo tep_draw_form('login', tep_href_link(FILENAME_LOGIN, 'action=process', 'SSL')); ?>
                            <div class="space">
                                <input id="header_user" class="header" type="text" name="email_address" value="Adresa e-mail"
                                       onFocus="
								if(this.value == 'Adresa e-mail'){
									this.value='';
								}"
                                       onBlur="
								if(this.value == ''){
									this.value='Adresa e-mail';
								}">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" height="32">
                            <div class="space">
                                <input
                                    type="text"
                                    id="mockpass"
                                    class="header"
                                    value="Parola"
                                    maxlenght="40"
                                    style="height:20"
                                    onFocus="	document.getElementById('mockpass').style.display='none';
										document.getElementById('realpass').style.display=''; 
										document.getElementById('realpass').focus();
									     "
                                    >
                                <input
                                    type="password"
                                    name="password"
                                    id="realpass"
                                    class="header"
                                    maxlenght="40"
                                    style="display: none; height:20"
                                    onBlur="
									if(this.value=='') {
										document.getElementById('mockpass').style.display=''; 
										document.getElementById('realpass').style.display='none';
									}">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td align="right">
                            <input type="image" src="images/header/login_bt.gif" border="0" alt="Sign In" title=" Sign In ">
                            <!--<button type="submit" name="someName" value="someValue" style="margin: 0; padding: 0; border-width: 0; background: none; cursor: pointer;"><img src="images/header/login_bt.gif" alt="Sign In"></button>-->
                            </form>
                        </td>
                        <td width="150px" valign="top"><?php echo '<a href="' . tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL') . '">&nbsp;&nbsp;<img border="0" src="images/header/cont_nou.gif"></a>&nbsp;<a href="' . tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL') . '"><img border="0" src="images/header/pass.gif"></a>'; ?></td>
                    </tr>
                </table>

            <?} else {?>
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="50"><td height="10"></td>
                    </tr>
                    <tr>
                        <td width="50"></td><td height="25"><a href="<?php echo tep_href_link(FILENAME_ACCOUNT, '', 'SSL'); ?>"><img src="images/icons/account.gif" align="left" border="0">&nbsp;<?php echo HEADER_TITLE_MY_ACCOUNT; ?></a></td>
                    </tr>
                    <tr>
                        <td width="50"><td height="25"><a href="<?php echo tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'); ?>"><img src="images/icons/checkout.gif" align="left" border="0">&nbsp;Casa</a></td>
                    </tr>
                    <tr>
                        <td width="50"><td height="25"><a href="<?php echo tep_href_link(FILENAME_LOGOFF, '', 'SSL'); ?>"><img src="images/icons/logout.gif" align="left" border="0">&nbsp;Iesire cont</a></td>
                    </tr>
                </table>
            <?}?>
        </td>
        <td><img src="images/header/header_top_right_bg.gif"></td>
    </tr>
</table>
<table width="978" height="58" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td><img src="images/header/header_06.gif"></td>
        <td width="220" class="header_middle_left" valign="bottom">&nbsp;&nbsp;&nbsp;Cautare
            <?	echo tep_draw_form('quick_find', tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, '', 'NONSSL', false), 'get');?>
            <div style="padding-left:10;"><? echo tep_draw_input_field('keywords', '', 'id="searchField" class="header" size="5" maxlength="30" style="width: 200px"');	?></div>
        </td>
        <td width="46px"><img src="images/header/header_08.gif"></td>
        <td class="header_middle_right">
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td><a target="_blank" href="<?echo tep_href_link(FILENAME_DEFAULT);?>" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('menu_01','','images/menu/menu_01_over.gif',1)"><img src="<?echo $cur_page=="1" ? 'images/menu/menu_01_over.gif' : 'images/menu/menu_01.gif';?>" alt="Acasa" name="menu_01" border="0"></a></td>
                    <td><a href="<?echo tep_href_link(FILENAME_ABOUT);?>" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('menu_02','','images/menu/menu_02_over.gif',1)"><img src="<?echo $cur_page=="2" ? 'images/menu/menu_02_over.gif' : 'images/menu/menu_02.gif';?>" alt="Despre ToolsZone" name="menu_02" border="0"></a></td>

                    <td><a href="<?echo tep_href_link(FILENAME_HOW_I_ORDER);?>" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('menu_03','','images/menu/menu_03_over.gif',1)"><img src="<?echo $cur_page=="3" ? 'images/menu/menu_03_over.gif' : 'images/menu/menu_03.gif';?>" alt="Cum comand" name="menu_03" border="0"></a></td>
                    <td><a href="<?echo tep_href_link(FILENAME_CONTACT_US);?>" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('menu_04','','images/menu/menu_04_over.gif',1)"><img src="<?echo $cur_page=="4" ? 'images/menu/menu_04_over.gif' : 'images/menu/menu_04.gif';?>" alt="Contact" name="menu_04" border="0"></a></td>
                    <td><a href="http://www.toolszone.ro/forum3/" rel="dropmenu3" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('menu_06','','images/menu/menu_06_over.gif',1)"><img src="<?echo $cur_page=="6" ? 'images/menu/menu_06_over.gif' : 'images/menu/menu_06.gif';?>" alt="Forum" name="menu_06" border="0"></a><script type="text/javascript">anylinkmenu.init("menuanchorclass")</script></td>
                    <td><a class="menuanchorclass" rel="anylinkmenu2" data-image="images/menu/menu_05.gif" data-overimage="images/menu/menu_05_over.gif"><img src="images/menu/menu_05.gif" style="border-width:0" alt="Contul Meu"/></a></td>
                </tr>
            </table>
        </td>
        <!--		<td><img src="images/header/header_10.gif"></td>-->
        <td><a href="<?echo tep_href_link(FILENAME_FAST_BUY);?>"><img border="0" src="images/header/comanda.png"></a></td>
    </tr>
</table>
<table width="978" height="46" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td><img src="images/header/header_11.gif"></td>
        <td width="220" class="header_bottom_left">&nbsp;&nbsp;&nbsp;<input type="image" src="images/header/cauta_bt.gif" border="0" alt="Cauta" title=" Cauta "></form>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?=tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, '', 'NONSSL', false)?>"><img border="0" src="images/header/button_search_advanced.gif"></a></td>
        <td><img src="images/header/header_13.gif"></td>
        <td width="697" class="header_bottom_right">
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <?
                    $j=0;
                    while ($categories = tep_db_fetch_array($top_cat_query))  {
                        if($categories['categories_id']!=1 && $categories['categories_name']!='Solduri'){
                            $cPath_new = 'cPath=0_' . $categories['categories_id'];
                            $categories_string = '<a href="'.tep_href_link(FILENAME_DEFAULT, $cPath_new) . '">'.$categories['categories_name'].'</a>'."\n";
                            echo ($j!=0?'<td><img src="images/header/separator.gif" /></td>':'').'<td class="header_label">'.$categories_string."</td>";
                            $j++;
                        }
                    }
                    ?>
                </tr>
            </table>
        </td>
        <td><img src="images/header/header_21.gif"></td>
    </tr>
</table>
<table width="978" height="32" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td rowspan="2" class="categHeader" width="230"><a href="<?echo tep_href_link(FILENAME_DEFAULT, 'cPath=1');?>">Categorii produse</a></td>
        <td height="1" style="background-color:#abbfd4"></td>
    </tr>
    <tr>
        <td class="breadcrumSep">&nbsp;&nbsp; &raquo; <?php echo $breadcrumb->trail(' &raquo; '); ?></td>
    </tr>
</table>