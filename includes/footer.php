<?php

?>
<div class="bottom">
	<div class="bottom_center">

	</div>
</div>
<footer id="main-footer">
    <div class="container">
        <div class="container">
            <div class="row footer_g1">
                    <div class="col-md-offset-2 col-md-2 paddingFooter">
                        <h4>Suport client</h4>
                        <ul class="">
                            <li><a href="<?echo tep_href_link(FILENAME_ACCOUNT);?>">Contul meu</a></li>
                            <li><a href="<?echo tep_href_link(FILENAME_HOW_I_ORDER);?>">Cum comand</a></li>
                            <li><a href="<?echo tep_href_link(FILENAME_FAST_BUY);?>">Comanda rapida</a></li>
                            <li><a href="http://www.toolszone.ro/how_i_order.php#facilitati">Fidelizare client</a></li>
                            <li><a href="http://www.anpc.gov.ro" target="_blank" rel="nofollow">Protectia consumatorilor - A.N.P.C</a></li>
                            <li><a href="#">Inscriere newsletter</a></li>
                        </ul>
                    </div>
                    <div class="col-md-2">
                        <h4>Date companie</h4>
                        <ul class="">
                            <li><a href="<?echo tep_href_link(FILENAME_DEFAULT);?>">Acasa</a></li>
                            <li><a href="<?echo tep_href_link(FILENAME_CONTACT_US);?>">Contact</a></li>
                            <li><a href="<?echo tep_href_link(FILENAME_ABOUT);?>">Despre noi</a></li>
                            <li><a href="<?echo tep_href_link(FILENAME_TERMS);?>">Termeni si conditii</a></li>
                        </ul>
                    </div>
                    <div class="col-md-2">
                        <h4>Resurse online</h4>
                        <ul class="">
                            <li><a href="<?echo tep_href_link(FILENAME_CATEGORIES);?>">Lista categorii</a></li>
                            <li><a href="<?echo tep_href_link(FILENAME_DEFAULT, 'cPath=1');?>">Catalog</a></li>
                            <li><a href="<?echo tep_href_link(FILENAME_SPECIALS);?>">Promotii</a></li>
                            <li><a href="<?echo tep_href_link(FILENAME_DEFAULT, 'cPath=2017123009');?>">Solduri</a></li>
                            <li><a href="<?echo tep_href_link(FILENAME_NEW_PRODUCTS);?>">Produse noi</a></li>
                            <li><a href="<?echo tep_href_link(FILENAME_MANUFACTURERS);?>">Lista branduri</a></li>
                            <li><a href="<?echo tep_href_link(FILENAME_VOUCHER);?>">Voucher</a></li>
                        </ul>
                    </div>
                    <div class="col-md-2">
                        <h4>Suport tehnic</h4>
                        <ul class="">
                            <li><a href="#">Forum</a></li>
                            <li><a href="<?echo tep_href_link(FILENAME_CONVERTOR);?>">Convertor</a></li>
                            <li><a href="#">Index termeni tehnici</a></li>
                            <li><a href="#">Te sunam noi</a></li>
                            <li><a href="#"">Blog</a></li>
                        </ul>
                    </div>
            </div>
            <div class="row">
                <div class="col-lg-12 copyright footer_g2">
                    &copy; <?=date('Y')?>  Virtual Tools SRL. Toate drepturile rezervate.
                </div>
            </div>
        </div>
    </div>
</footer>
<script type="text/javascript">
    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-11900925-1']);
    _gaq.push(['_trackPageview']);
    _gaq.push(['_addIgnoredOrganic', 'www.toolszone.ro']);
    _gaq.push(['_addIgnoredOrganic', 'toolszone.ro']);
    _gaq.push(['_addIgnoredOrganic', 'toolszone']);
    _gaq.push(['_addIgnoredOrganic', 'tools zone']);
    _gaq.push(['_addIgnoredOrganic', 'sc virtual tools srl']);
    _gaq.push(['_addIgnoredOrganic', 'virtual tools srl']);

    <? if (isset($ga)){
        echo '_gaq.push('.$ga.');';
        foreach($gaq as $g){
        echo '_gaq.push('.$g.');';
        }
         echo "_gaq.push(['_trackTrans']);";
         echo "_gaq.push(['_setCustomVar',1,'role','buyer',1]);";
    }
     ?>

    (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;

        ga.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'stats.g.doubleclick.net/dc.js';

        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();
</script>
<script type="text/JavaScript">

    function getCookie(c_name) {
        if (document.cookie.length > 0) {
            c_start = document.cookie.indexOf(c_name + "=");
            if (c_start != -1) {
                c_start = c_start + c_name.length + 1;
                c_end = document.cookie.indexOf(";", c_start);
                if (c_end == -1) {
                    c_end = document.cookie.length;
                }
                return unescape(document.cookie.substring(c_start, c_end));
            }
        }
        return "";
    }

    if (!getCookie('copyEnabled')){
    var message="Can't touch this!";
    function defeatIE() {if (document.all) {(message);return false;}}
    function defeatNS(e) {
        if (document.layers||(document.getElementById&&!document.all)) {
        if (e.which==2||e.which==3) {(message);return false;}}}
    if (document.layers)
    {document.captureEvents(Event.MOUSEDOWN);document.onmousedown=defeatNS;}
    else{document.onmouseup=defeatNS;document.oncontextmenu=defeatIE;}
    document.oncontextmenu=new Function("return false");

        $('body').bind('copy paste',function(e) {
            e.preventDefault(); return false;
        });

    }
</script>

<script type="text/javascript">
    var LHCChatOptions = {};
    LHCChatOptions.opt = {widget_height:340,widget_width:300,popup_height:520,popup_width:500};
    (function() {
        var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
        var refferer = (document.referrer) ? encodeURIComponent(document.referrer) : '';
        var location  = (document.location) ? encodeURIComponent(document.location) : '';
        po.src = '//www.toolszone.ro/lhc/index.php/rom/chat/getstatus/(position)/bottom_right/(top)/350/(units)/pixels/(leaveamessage)/true/(department)/1?r='+refferer+'&l='+location;
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
    })();
</script>


<script type="text/javascript">
    $(function() {
        $('a[href$=".pdf"]').each(function() {
            $(this).prop('target', '_blank');
        });
    });
    $(function(){
        $("a").each(function(){
            $(this).attr("title", $(this).find("img").attr("alt"));
        });
    });
</script>

<script type="text/javascript">
    if (typeof jQuery != 'undefined') {
        jQuery(document).ready(function($) {
            var filetypes = /\.(zip|exe|pdf|doc*|xls*|ppt*|mp3)$/i;
            var baseHref = '';
            if (jQuery('base').attr('href') != undefined)
                baseHref = jQuery('base').attr('href');
            jQuery('a').each(function() {
                var href = jQuery(this).attr('href');
                if (href && (href.match(/^https?\:/i)) && (!href.match(document.domain))) {
                    jQuery(this).click(function() {
                        var extLink = href.replace(/^https?\:\/\//i, '');
                        _gaq.push(['_trackEvent', 'External', 'Click', extLink]);
                        if (jQuery(this).attr('target') != undefined && jQuery(this).attr('target').toLowerCase() != '_blank') {
                            setTimeout(function() { location.href = href; }, 200);
                            return false;
                        }
                    });
                }
                else if (href && href.match(/^mailto\:/i)) {
                    jQuery(this).click(function() {
                        var mailLink = href.replace(/^mailto\:/i, '');
                        _gaq.push(['_trackEvent', 'Email', 'Click', mailLink]);
                    });
                }
                else if (href && href.match(filetypes)) {
                    jQuery(this).click(function() {
                        var extension = (/[.]/.exec(href)) ? /[^.]+$/.exec(href) : undefined;
                        var filePath = href;
                        _gaq.push(['_trackEvent', 'Download', 'Click-' + extension, filePath]);
                        if (jQuery(this).attr('target') != undefined && jQuery(this).attr('target').toLowerCase() != '_blank') {
                            setTimeout(function() { location.href = baseHref + href; }, 200);
                            return false;
                        }
                    });
                }
            });
        });
    }
</script>