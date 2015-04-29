<?php
/*
  $Id: categories.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

function tep_show_category($counter) {
  global $tree, $categories_string, $cPath_array;
  
  
  for ($i=0; $i<$tree[$counter]['level']; $i++) {
      //$categories_string .= "&nbsp;&nbsp;";
    }

    $attr = (isset($cPath_array) && in_array($counter, $cPath_array))? ' class="active"':'';


	if($tree[$counter]['level']==0){
		$categories_string .= '<li'.$attr.'>';
	}
	
    $categories_string .= '<a href="';

    if ($tree[$counter]['parent'] == 0) {
      $cPath_new = 'cPath=' . $counter;
    } else {
      $cPath_new = 'cPath=' . $tree[$counter]['path'];
    }

    $categories_string .= tep_href_link(FILENAME_DEFAULT, $cPath_new) . '">';




// display category name

	if($tree[$counter]['level']==0){
		//$categories_string .= '<b>';
	}
    if (tep_has_category_subcategories($counter)) {
      //$categories_string .= '&nbsp;+ ';
    }else{
      //$categories_string .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';  
    }

    $categories_string .= $tree[$counter]['name'];


    if (isset($cPath_array) && in_array($counter, $cPath_array)) {
      $categories_string .= '';
    }

	if($tree[$counter]['level']==0){
		//$categories_string .= '</b>';
	}

    $categories_string .= '</a>';

//    if (SHOW_COUNTS == 'true') {
//      $products_in_category = tep_count_products_in_category($counter);
//      if ($products_in_category > 0) {
//        $categories_string .= '&nbsp;(' . $products_in_category . ')';
//      }
//    }

    $categories_string .= '</li>';


/*    if ($tree[$counter]['next_id'] != false) {
      tep_show_category($tree[$counter]['next_id']);
    }*/
  }
?>
<!-- categories //-->

<?php

  $categories_query = tep_db_query("SELECT count(categories_id) as categ from " . TABLE_CATEGORIES . " where parent_id=0");

  $categ_no = tep_db_fetch_array($categories_query);

  for($i=0; $i<1; $i++){
	  $tree = NULL;
	  $parent_id = NULL;
	  $first_element = NULL;
    
	  $top_cat_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '0' and c.categories_id = cd.categories_id and cd.language_id='" . (int)$languages_id ."' order by sort_order, cd.categories_name". " LIMIT ".$i.",1");
	  $top_cat = tep_db_fetch_array($top_cat_query);

	  $categories_string = '';
	  $tree = array();
	
	  $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '".$top_cat['categories_id']."' and c.categories_id = cd.categories_id and cd.language_id='" . (int)$languages_id ."' order by sort_order, cd.categories_name");
	  while ($categories = tep_db_fetch_array($categories_query))  {
          //dirty patch
          if ($categories['categories_id'] =='2017123509') continue;
          //var_dump($categories['categories_id']);
	    $tree[$categories['categories_id']] = array('name' => $categories['categories_name'],
	                                                'parent' => $categories['parent_id'],
	                                                'level' => 0,
	                                                'path' => $categories['categories_id'],
	                                                'next_id' => false);
		
      if(!tep_empty_category($categories['categories_id']))
        tep_show_category($categories['categories_id']);
	   
     
      if (isset($parent_id)) {
	      $tree[$parent_id]['next_id'] = $categories['categories_id'];
	    }
	
	    $parent_id = $categories['categories_id'];
	
	    if (!isset($first_element)) {
	      $first_element = $categories['categories_id'];
	    }
	  }
	  

 }
?>
<nav class="sec-nav " role="navigation">
    <ul class="nav nav-list sidenav">
        <?=$categories_string?>
    </ul>
</nav>

<!-- categories_eof //-->