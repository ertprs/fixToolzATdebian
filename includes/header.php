<?php

$top_cat_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '0' and c.categories_id = cd.categories_id and cd.language_id='" . (int)$languages_id ."' order by sort_order, cd.categories_name");

?>

<header class="container">
    <div id="first-row" class="row">
        <div id="logo" class="col-md-3">
             <?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_image(DIR_WS_IMAGES . 'store_logo.gif', STORE_NAME) . '</a>'; ?>

        </div>
        <div class="col-md-9">
            <div id="banner-produse" class="col-md-6">
                <div class="phone col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <span class="glyphicon glyphicon-earphone"></span>
                    <span class="numar">+4 0368 004 674</span>
                    <span class="retea">(romtelecom)</span>
                </div>
                <div class="phone col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <span class="glyphicon glyphicon-earphone"></span>
                    <span class="numar">+4 0748 106 900</span>
                    <span class="retea">(orange)</span>
                </div>
                <div class="phone col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <span class="glyphicon glyphicon-earphone"></span>
                    <span class="numar">+4 0727 387 799</span>
                    <span class="retea">(vodafone)</span>
                </div>
                <div class="phone col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <span class="glyphicon glyphicon-earphone"></span>
                    <span class="numar">+4 0368 004 674</span>
                    <span class="retea">(fax)</span>
                </div>
            </div>
            <div class="col-md-6 transport">
                <ul>
                    <li>Transport <span class="alternativ">GRATUIT</span> pentru comenzi mai mari de 350 lei</li>
                    <li>Livrare in <span class="alternativ">24 ore</span> pentru produsele disponibile in stoc</li>
                </ul>
            </div>
        </div><!--md-9-->
        <div class="col-md-9">
            <div class="col-md-8 searchbox">
                <?	echo tep_draw_form('quick_find', tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, '', 'NONSSL', false), 'get');?>
                <div class="input-group">
                    <? echo tep_draw_input_field('keywords', '', 'id="searchField" class="form-control" placeholder="Cauta in peste 110 mii de produse.."');?>
                    <div class="input-group-btn">
                        <button type="submit" class="btn btn-danger">Cauta</button>
                        <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" tabindex="-1">
                            <span class="caret"></span>
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu pull-right" role="menu">
                            <li><a href="<?=tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, '', 'NONSSL', false)?>">Cautare avansata</a></li>
                        </ul>
                    </div>
                </div>
                </form>
            </div>
            <?php if (!tep_session_is_registered('customer_id')) {?>
                <div class="col-md-4">
                    <div id="cartnclient" class="">
                            <div id="buttons">
                                <div class="btn-group btn-xl btn-group-justified">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-default" data-toggle="modal" data-target="#loginModal"><span class="glyphicon glyphicon-user"></span><span> Bine ai venit,<br><strong>Contul meu</strong></span> </button>
                                    </div>

                                    <div class="btn-group">
                                        <button type="button" class="btn btn-default"><span class="glyphicon glyphicon-shopping-cart"></span> <span>Cosul meu<br>
              <strong>Nu ai produse</strong>
            </span>
                                        </button>
                                    </div>
                                </div>
                            </div><!--buttons-->
                        <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <?php echo tep_draw_form('login', tep_href_link(FILENAME_LOGIN, 'action=process', 'SSL')); ?>
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="exampleModalLabel">Autentificare:</h4>
                                    </div>
                                    <div class="modal-body">
                                            <div class="form-group">
                                                <label for="user" class="control-label">Adresa de email</label>
                                                <input id="user" class="form-control" type="text" name="email_address">
                                            </div>
                                            <div class="form-group">
                                                <label for="parola" class="control-label">Parola:</label>
                                                <input type="password" class="form-control" name="password" id="parola">
                                            </div>
                                        <?php echo '<a href="' . tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL') . '"><img alt="cont nou" src="images/header/cont_nou.gif"></a>&nbsp;<a href="' . tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL') . '"><img alt="parola uitata" src="images/header/pass.gif"></a>'; ?>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Inchide</button>
                                        <button type="button" class="btn btn-primary">Login</button>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?} else {?>
            <a href="<?php echo tep_href_link(FILENAME_ACCOUNT, '', 'SSL'); ?>"><img src="images/icons/account.gif" align="left"><?php echo HEADER_TITLE_MY_ACCOUNT; ?></a>
            <a href="<?php echo tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'); ?>"><img src="images/icons/checkout.gif">Casa</a>
            <a href="<?php echo tep_href_link(FILENAME_LOGOFF, '', 'SSL'); ?>"><img src="images/icons/logout.gif">Iesire cont</a>
        <?}?>
    </div>
    <div id="second-row" class="row">
        <div class="main-nav-group">
            <nav id="main-nav" class="navbar navbar-default nav-top" role="navigation">
                <div class="container-fluid">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav navbar-left">

                            <li class="active"><a  href="<?echo tep_href_link(FILENAME_DEFAULT);?>">Acasa</a></li>
                            <li><a href="<?echo tep_href_link(FILENAME_ABOUT);?>">Despre ToolsZone</a></li>
                            <li><a href="<?echo tep_href_link(FILENAME_HOW_I_ORDER);?>">Cum comand</a></li>
                            <li ><a href="<?echo tep_href_link(FILENAME_CONTACT_US);?>" >Contact</a></li>
                            <li><a href="#" onclick="alert('Forumul temporat dezactivat,\nVa rugam sa reveniti');return false;">Forum</a></li>
                            <li class="dropdown">
                                <a class=" dropdown-toggle" data-toggle="dropdown">Contul meu <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li ><a href="#">Lista favorite</a></li>
                                    <li ><a href="#">Comenzile mele</a></li>
                                    <li ><a href="#">Agenda</a></li>
                                    <li class="divider"></li>
                                    <li><a href="#">Logout</a></li>
                                </ul>
                            </li>
                            <li><a href="<?echo tep_href_link(FILENAME_FAST_BUY);?>">Comanda rapida</a></li>
                        </ul>
                        <ul class="nav navbar-nav navbar-right navbar_r_bg">
                            <li ><a >Oferte speciale</a></li>
                            <li ><a >Solduri</a></li>
                        </ul>

                    </div><!-- /.navbar-collapse -->

                </div><!-- /.container-fluid -->
            </nav>
            <div class="subnav">
                <nav id="specialisti-nav" class="navbar navbar-default nav-bottom navbar_b_bg" role="navigation">
                    <div class="container-fluid">
                        <ul class="nav navbar-nav navbar-left">
                            <li class="categorii"><a href="<?echo tep_href_link(FILENAME_DEFAULT, 'cPath=1');?>">Categorii produse</a></li>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            <?
                            $j=0;
                            while ($categories = tep_db_fetch_array($top_cat_query))  {
                                if($categories['categories_id']!=1 && $categories['categories_name']!='Solduri'){
                                    $cPath_new = 'cPath=0_' . $categories['categories_id'];
                                    $categories_string = '<li><a href="'.tep_href_link(FILENAME_DEFAULT, $cPath_new) . '">'.$categories['categories_name'].'</a></li>'."";
                                    //echo ($j!=0?'|':'').'.'.$categories_string."";
                                    echo $categories_string;
                                    $j++;
                                }
                            }
                            ?>
                            <li><a href="#">Agricultori</a></li>
                            <li><a href="#">Sudori</a></li>

                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</header>

    
