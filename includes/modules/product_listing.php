<?php
/*
  $Id: product_listing.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

if(isset($_GET['allProducts'])){
  $listing_split = new splitPageResults($listing_sql, 999999, 'p.products_id');
}else{
  $listing_split = new splitPageResults($listing_sql, MAX_DISPLAY_SEARCH_RESULTS, 'p.products_id');
}


  if ($listing_split->number_of_rows > 0) {
  	
  $list_products_content = '<table border="0" width="100%" cellspacing="0" cellpadding="0">';
  
  $list_products_content .= '<tr><td class="sortTh" width="5px"></td>';	
  for ($col=0, $n=(sizeof($column_list)); $col<$n; $col++) {
  	
    $lc_width = '*';
    switch ($column_list[$col]) {
      case 'PRODUCT_LIST_MODEL':
        $lc_text = TABLE_HEADING_MODEL.'&nbsp;';
        $lc_align = '';
        break;
      case 'PRODUCT_LIST_NAME':
        $lc_text = TABLE_HEADING_PRODUCTS.'&nbsp;';
        $lc_align = '';
        $lc_width = '30%';
        break;
      case 'PRODUCT_LIST_MANUFACTURER':
        $lc_text = '&nbsp;'.TABLE_HEADING_MANUFACTURER.'&nbsp;';
        $lc_align = '';
        break;
      case 'PRODUCT_LIST_PRICE':
        $lc_text = TABLE_HEADING_PRICE.'&nbsp;';
        $lc_align = 'center';
        $lc_width = '40%';
        break;
      case 'PRODUCT_LIST_QUANTITY':
        $lc_text = TABLE_HEADING_QUANTITY;
        $lc_align = 'right';
        break;
      case 'PRODUCT_LIST_WEIGHT':
        $lc_text = TABLE_HEADING_WEIGHT;
        $lc_align = 'right';
        break;
      case 'PRODUCT_LIST_IMAGE':
        $lc_text = '&nbsp;';
        $lc_align = 'center';
        $lc_width = '20%';
        break;
      case 'PRODUCT_LIST_BUY_NOW':
        $lc_text = 'Cantitate&nbsp;';
        $lc_align = 'center';
        $lc_width = '10%';
        break;

    }


    if ( ($column_list[$col] != 'PRODUCT_LIST_BUY_NOW') && ($column_list[$col] != 'PRODUCT_LIST_IMAGE') ) {
      $lc_text = tep_create_sort_heading(isset($_GET['sort'])?$_GET['sort']:"1", $col+1, $lc_text);
      
    }
    
    $list_products_content .= '<td class="sortTh" align="'.$lc_align.'" height="30">' . $lc_text . '</td>';
    
  }
  
  	$list_products_content .= '<td class="sortTh" width="5px"></td></tr>';	
  
  
  	$breadcrumb_nav = "";
  	if(tep_session_is_registered('customer_id')) {
  		$breadcrumb_nav .= '<a href="'.tep_href_link(FILENAME_LOGOFF, '', 'SSL').'" class="headerNavigation">'.HEADER_TITLE_LOGOFF.'</a> &nbsp;|&nbsp; ';
  	}	
  	
    $rows = 0;
    $listing_query = tep_db_query($listing_split->sql_query);
    $no_rows = tep_db_num_rows($listing_query);
    while ($listing = tep_db_fetch_array($listing_query)) {
      $rows++;

  	$list_products_content .= '<tr><td width="5px"></td>';	
  	
      for ($col=0, $n=(sizeof($column_list)); $col<$n; $col++) {
        $lc_align = '';
		$lc_valign = '';
        switch ($column_list[$col]) {
          case 'PRODUCT_LIST_MODEL':
            $lc_align = '';
            $lc_text = '&nbsp;' . $listing['products_model'] . '&nbsp;';
            break;
          case 'PRODUCT_LIST_NAME':
            $lc_align = '';
            $lc_valign = 'top';
            $lc_text = '<br><a class="product" href="' . tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $listing['products_id']) . '">' . $listing['products_name'] . '</a>';

            break;
          case 'PRODUCT_LIST_MANUFACTURER':
            $lc_align = 'left';
            $lc_valign = 'top';
            $lc_text = '<br><nobr>&nbsp;<a class="product" href="' . tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $listing['products_id']) . '">' . $listing['nr_articol'] . '</a>&nbsp;</nobr>';
            
            break;
          
      	  case 'PRODUCT_LIST_BUY_NOW':  
            $lc_align = 'center';
            $lc_valign = 'middle';
            
            if(!tep_has_product_attributes($listing['products_id'])){
            	
	      	  	$lc_text = '<input style="width:30px" id="cant_'.$listing['products_id'].'" class="input" type="text" size="1" value="1" >';
            }else{
            	$lc_text = '';
            }
      	  	break;
          case 'PRODUCT_LIST_PRICE':
            $lc_align = 'right';
            $lc_valign = 'middle';
            $href= tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action', 'products_id')) . 'action=buy_now&products_id=' . $listing['products_id']);
            if(!tep_has_product_attributes($listing['products_id'])){
            
	            if (tep_not_null($listing['specials_new_products_price'])) {
	              $lc_text = '<s class="old_price" style="font-size:12px">' .  $currencies->display_price($listing['products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</s><div class="price"><nobr>'.$currencies->display_price($listing['specials_new_products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</nobr></div>'.
	              			'<div class="productPrice" style="width:100;text-align:left;"><a onclick="this.href = this.href+\'&cant=\'+document.getElementById(\'cant_'.$listing['products_id'].'\').value" id="link_'.$cur_row.'" href="'.$href.'" title="Cumpara ' . $listing['products_name'] . '"><NOBR><img align="left" src="'.DIR_WS_IMAGES .'icons/arrow.gif" border="0"/> Adauga in cos</NOBR></a></div>';
	            } else {
	              $lc_text = '<div class="price"><nobr>'.$currencies->display_price($listing['products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</nobr></div>' .
	              			 '<div class="productPrice" style="width:100;text-align:left;"><a onclick="this.href = this.href+\'&cant=\'+document.getElementById(\'cant_'.$listing['products_id'].'\').value" id="link_'.$cur_row.'" href="'.$href.'" title="Cumpara ' . $listing['products_name'] . '"><NOBR><img align="left" src="'.DIR_WS_IMAGES .'icons/arrow.gif" border="0"/> Adauga in cos</NOBR></a></div>';
	            }
            }else{
            	$lc_text = '<div style="text-align:left"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'manufacturers_id=' . $_GET['manufacturers_id'] . '&products_id=' . $listing['products_id']) . '"><NOBR><img align="left" src="'.DIR_WS_IMAGES .'icons/arrow.gif" border="0"/> Mai mult</NOBR></a></div>';
	        }
            break;
          case 'PRODUCT_LIST_QUANTITY':
            $lc_align = 'right';
            $lc_text = '&nbsp;' . $listing['products_quantity'] . '&nbsp;';
            break;
          case 'PRODUCT_LIST_WEIGHT':
            $lc_align = 'right';
            $lc_text = '&nbsp;' . $listing['products_weight'] . '&nbsp;';
            break;
          case 'PRODUCT_LIST_IMAGE':
            $lc_align = 'center';
            $lc_valign = 'middle';         
            $lc_text = '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $listing['products_id']) . '">' . tep_image(DIR_WS_IMAGES . 'mici/'.strtoupper($listing['products_image'][0]).'/' . $listing['products_image'], $listing['products_name'], 50, 50) . '</a>';
            
            break;
        }
      	
    	$list_products_content .= '<td align="'.$lc_align.'" valign="'.$lc_valign.'">' . $lc_text . '</td>';
      }
      
  	$list_products_content .= '<td width="5px"></td></tr>';	
	if($rows!=$no_rows)$list_products_content .= '<tr><td width="5px"></td><td colspan="5" class="subcateg" style="font-size:1px">&nbsp;</td><td width="5px"></td></tr>';
    }
  
	if(isset($_GET['sort']) && $_GET['sort']!=""){
		
		$exclude_param = tep_get_all_get_params(array('page', 'info', 'x', 'y'));
	}else{
		$exclude_param = tep_get_all_get_params(array('page', 'info', 'x', 'y', 'sort'));
	}
	
  
	$list_products_content .= '<tr><td class="pageResults" style="background-color:#efefef" colspan="4" height="30" valign="middle" align="left">&nbsp;&nbsp;&nbsp;'.$listing_split->display_links(MAX_DISPLAY_PAGE_LINKS, $exclude_param).'&nbsp;&nbsp;&nbsp;'.$listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS).'</td><td class="pageResults" style="background-color:#efefef"  colspan="3" align="right"><a  class="pageResults"  href="' . tep_href_link(basename($PHP_SELF),$exclude_param."&allProducts=1") .'">Afiseaza Toate Produsele</a>&nbsp;&nbsp;&nbsp;</td></tr>';
	$list_products_content .= '</table>';
	
	echo $list_products_content;
	
  } else {
                                
?>
	<table border="0" width="100%" cellspacing="0" cellpadding="0" >
      <tr>
        <td>&nbsp;&nbsp;&nbsp;<img src="images/emptycart.gif"></td>
        <td class="fieldKey"><?echo TEXT_NO_PRODUCTS;?></td>
      </tr>     
    </table> 


<?
  }


?>