<?php
/*
  $Id: html_output.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2007 osCommerce

  Released under the GNU General Public License
*/

//// 
// ULTIMATE Seo Urls 5 by FWR Media 
// The HTML href link wrapper function 
  function tep_href_link($page = '', $parameters = '', $connection = 'NONSSL', $add_session_id = true, $search_engine_safe = true) {
    global $seo_urls, $languages_id, $request_type, $session_started, $sid;                 
    if ( !is_object($seo_urls) ){ 
      include_once DIR_WS_MODULES . 'ultimate_seo_urls5' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'usu.php'; 
      $seo_urls = new usu($languages_id, $request_type, $session_started, $sid); 
    } 
    return $seo_urls->href_link($page, $parameters, $connection, $add_session_id);
  } 


////
// The HTML image wrapper function
  function tep_image($src, $alt = '', $width = '', $height = '', $parameters = '') {
    if ( (empty($src) || ($src == DIR_WS_IMAGES)) && (IMAGE_REQUIRED == 'false') ) {
      return false;
    }

    if(!file_exists($src)){
      $src=DIR_WS_IMAGES . "default.gif";
    }
// alt is added to the img tag even if it is null to prevent browsers from outputting
// the image filename as default
    $image = '<img src="' . tep_output_string($src) . '" alt="' . tep_output_string($alt) . '"';

    if (tep_not_null($alt)) {
      $image .= ' title=" ' . tep_output_string($alt) . ' "';
    }

    if ( (CONFIG_CALCULATE_IMAGE_SIZE == 'true') && (empty($width) || empty($height)) ) {
      if ($image_size = @getimagesize($src)) {
        if (empty($width) && tep_not_null($height)) {
          $ratio = $height / $image_size[1];
          $width = intval($image_size[0] * $ratio);
        } elseif (tep_not_null($width) && empty($height)) {
          $ratio = $width / $image_size[0];
          $height = intval($image_size[1] * $ratio);
        } elseif (empty($width) && empty($height)) {
          $width = $image_size[0];
          $height = $image_size[1];
        }
      } elseif (IMAGE_REQUIRED == 'false') {
        return false;
      }
    }

    if (tep_not_null($width) && tep_not_null($height)) {
      $image .= ' width="' . tep_output_string($width) . '" height="' . tep_output_string($height) . '"';
    }

    if (tep_not_null($parameters)) $image .= ' ' . $parameters;

    $image .= '>';

    return $image;
  }

////
// The HTML form submit button wrapper function
// Outputs a button in the selected language
  function tep_image_submit($image, $alt = '', $parameters = '') {
    global $language;

    $image_submit = '<input type="image" src="' . tep_output_string(DIR_WS_LANGUAGES . $language . '/images/buttons/' . $image) . '" border="0" alt="' . tep_output_string($alt) . '"';

    if (tep_not_null($alt)) $image_submit .= ' title=" ' . tep_output_string($alt) . ' "';

    if (tep_not_null($parameters)) $image_submit .= ' ' . $parameters;

    $image_submit .= '>';

    return $image_submit;
  }

////
// Output a function button in the selected language
  function tep_image_button($image, $alt = '', $parameters = '') {
    global $language;

    return tep_image(DIR_WS_LANGUAGES . $language . '/images/buttons/' . $image, $alt, '', '', $parameters);
  }

////
// Output a separator either through whitespace, or with an image
  function tep_draw_separator($image = 'pixel_black.gif', $width = '100%', $height = '1') {
    return tep_image(DIR_WS_IMAGES . $image, '', $width, $height);
  }

////
// Output a form
  function tep_draw_form($name, $action, $method = 'post', $parameters = '') {
    $form = '<form name="' . tep_output_string($name) . '" action="' . tep_output_string($action) . '" method="' . tep_output_string($method) . '" ';

    if (tep_not_null($parameters)) $form .= ' ' . $parameters;

    $form .= '>';

    return $form;
  }

////
// Output a form input field
  function tep_draw_input_field($name, $value = '', $parameters = '', $type = 'text', $reinsert_value = true) {
    global $_GET, $_POST;

    $field = '<input type="' . tep_output_string($type) . '" name="' . tep_output_string($name) . '"';

    if ( ($reinsert_value == true) && ( (isset($_GET[$name]) && is_string($_GET[$name])) || (isset($_POST[$name]) && is_string($_POST[$name])) ) ) {
      if (isset($_GET[$name]) && is_string($_GET[$name])) {
        $value = stripslashes($_GET[$name]);
      } elseif (isset($_POST[$name]) && is_string($_POST[$name])) {
        $value = stripslashes($_POST[$name]);
      }
    }

    if (tep_not_null($value)) {
      $field .= ' value="' . tep_output_string($value) . '"';
    }

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= '>';

    return $field;
  }

////
// Output a form password field
  function tep_draw_password_field($name, $value = '', $parameters = 'maxlength="40"') {
    return tep_draw_input_field($name, $value, $parameters, 'password', false);
  }

////
// Output a selection field - alias function for tep_draw_checkbox_field() and tep_draw_radio_field()
  function tep_draw_selection_field($name, $type, $value = '', $checked = false, $parameters = '') {
    global $_GET, $_POST;

    $selection = '<input type="' . tep_output_string($type) . '" name="' . tep_output_string($name) . '"';

    if (tep_not_null($value)) $selection .= ' value="' . tep_output_string($value) . '"';

    if ( ($checked == true) || (isset($_GET[$name]) && is_string($_GET[$name]) && (($_GET[$name] == 'on') || (stripslashes($_GET[$name]) == $value))) || (isset($_POST[$name]) && is_string($_POST[$name]) && (($_POST[$name] == 'on') || (stripslashes($_POST[$name]) == $value))) ) {
      $selection .= ' CHECKED';
    }

    if (tep_not_null($parameters)) $selection .= ' ' . $parameters;

    $selection .= '>';

    return $selection;
  }

////
// Output a form checkbox field
  function tep_draw_checkbox_field($name, $value = '', $checked = false, $parameters = '') {
    return tep_draw_selection_field($name, 'checkbox', $value, $checked, $parameters);
  }

////
// Output a form radio field
  function tep_draw_radio_field($name, $value = '', $checked = false, $parameters = '') {
    return tep_draw_selection_field($name, 'radio', $value, $checked, $parameters);
  }

////
// Output a form textarea field
  function tep_draw_textarea_field($name, $wrap, $width, $height, $text = '', $parameters = '', $reinsert_value = true) {
    global $_GET, $_POST;

    $field = '<textarea name="' . tep_output_string($name) . '" wrap="' . tep_output_string($wrap) . '" cols="' . tep_output_string($width) . '" rows="' . tep_output_string($height) . '"';

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= '>';

    if ( ($reinsert_value == true) && ( (isset($_GET[$name]) && is_string($_GET[$name])) || (isset($_POST[$name]) && is_string($_POST[$name])) ) ) {
      if (isset($_GET[$name]) && is_string($_GET[$name])) {
        $field .= tep_output_string_protected(stripslashes($_GET[$name]));
      } elseif (isset($_POST[$name]) && is_string($_POST[$name])) {
        $field .= tep_output_string_protected(stripslashes($_POST[$name]));
      }
    } elseif (tep_not_null($text)) {
      $field .= tep_output_string_protected($text);
    }

    $field .= '</textarea>';

    return $field;
  }

////
// Output a form hidden field
  function tep_draw_hidden_field($name, $value = '', $parameters = '') {
    global $_GET, $_POST;

    $field = '<input type="hidden" name="' . tep_output_string($name) . '"';

    if (tep_not_null($value)) {
      $field .= ' value="' . tep_output_string($value) . '"';
    } elseif ( (isset($_GET[$name]) && is_string($_GET[$name])) || (isset($_POST[$name]) && is_string($_POST[$name])) ) {
      if ( (isset($_GET[$name]) && is_string($_GET[$name])) ) {
        $field .= ' value="' . tep_output_string(stripslashes($_GET[$name])) . '"';
      } elseif ( (isset($_POST[$name]) && is_string($_POST[$name])) ) {
        $field .= ' value="' . tep_output_string(stripslashes($_POST[$name])) . '"';
      }
    }

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= '>'."\n";

    return $field;
  }

////
// Hide form elements
  function tep_hide_session_id() {
    global $session_started, $SID;

    if (($session_started == true) && tep_not_null($SID)) {
      return tep_draw_hidden_field(tep_session_name(), tep_session_id());
    }
  }

////
// Output a form pull down menu
  function tep_draw_pull_down_menu($name, $values, $default = '', $parameters = '', $required = false) {
    global $_GET, $_POST;

    $field = '<select name="' . tep_output_string($name) . '"';

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= '>';

    if (empty($default) && ( (isset($_GET[$name]) && is_string($_GET[$name])) || (isset($_POST[$name]) && is_string($_POST[$name])) ) ) {
      if (isset($_GET[$name]) && is_string($_GET[$name])) {
        $default = stripslashes($_GET[$name]);
      } elseif (isset($_POST[$name]) && is_string($_POST[$name])) {
        $default = stripslashes($_POST[$name]);
      }
    }

    for ($i=0, $n=sizeof($values); $i<$n; $i++) {
      $field .= '<option value="' . tep_output_string($values[$i]['id']) . '"';
      if ($default == $values[$i]['id']) {
        $field .= ' SELECTED';
      }

      $field .= '>' . tep_output_string($values[$i]['text'], array('"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => '&gt;')) . '</option>';
    }
    $field .= '</select>';

    if ($required == true) $field .= TEXT_FIELD_REQUIRED;

    return $field;
  }
////
// Output a table with product options
  function tep_draw_product_table($name, $values, $products_id, $default = '', $parameters = '', $required = false) {
    global $_GET, $_POST;
    $field = tep_draw_hidden_field(tep_output_string($name), tep_output_string($values[0]['id']), 'id="'.tep_output_string($name).'"') .
    	tep_draw_hidden_field('cart_quantity', '1', 'id="cart_quantity"') .
    	'<table class="optionTable" width="100%">';

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;

    if (empty($default) && ( (isset($_GET[$name]) && is_string($_GET[$name])) || (isset($_POST[$name]) && is_string($_POST[$name])) ) ) {
      if (isset($_GET[$name]) && is_string($_GET[$name])) {
        $default = stripslashes($_GET[$name]);
      } elseif (isset($_POST[$name]) && is_string($_POST[$name])) {
        $default = stripslashes($_POST[$name]);
      }
    }


      $um = $values[0]['um'];
      $um_text = $values[0]['is_set']?"":("(". $values[0]['um'] .")");
      //if ($values[1]['um'] != 'inch') // daca primul articol din lista e in toli nu afisam un header metric !!
      $field .= '<tr>' .
      				'<th class="optTh"></th>' .
      				'<th class="optTh">' . tep_output_string($values[0]['cod_unic'], array('"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => '&gt;')) . '</th>' .
      				'<th class="optTh">' . tep_output_string($values[0]['text'].$um_text, array('"' => '&quot;', '\'' => '&#039;')) . '</th>' .
      				'<th class="optTh">' . tep_output_string($values[0]['car_teh_1'], array('"' => '&quot;', '\'' => '&#039;')) . '</th>' .
      				'<th class="optTh">' . tep_output_string($values[0]['car_teh_2'], array('"' => '&quot;', '\'' => '&#039;')) . '</th>' .
      				'<th class="optTh">Cantitate</th>'.
      				'<th class="optTh" id="th_price">' . tep_output_string($values[0]['price'], array('"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => '&gt;')) . '</th>'.
      				'<th class="optTh" id="th_price_2">' . tep_output_string($values[0]['price_2'], array('"' => '&quot;', '\'' => '&#039;')) . '</th>'.
      				'<th class="optTh" id="th_price_3">' . tep_output_string($values[0]['price_3'], array('"' => '&quot;', '\'' => '&#039;')) . '</th>';
      			'</tr>';

    for ($i=1; $i<sizeof($values); $i++) {

	if(!$values[0]['is_set'] && $values[$i]['um']!="" && $um!="" && $values[$i]['um']!=$um){
	      $um = $values[$i]['um'];
	      $um_text = "(". $values[$i]['um'].")";
	      $field .= '<tr>' .
					'<th class="optTh"></th>' .
					'<th class="optTh">' . tep_output_string($values[0]['cod_unic'], array('"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => '&gt;')) . '</th>' .
					'<th class="optTh">' . tep_output_string($values[0]['text'].$um_text, array('"' => '&quot;', '\'' => '&#039;')) . '</th>' .
					'<th class="optTh">' . tep_output_string($values[0]['car_teh_1'], array('"' => '&quot;', '\'' => '&#039;')) . '</th>' .
					'<th class="optTh">' . tep_output_string($values[0]['car_teh_2'], array('"' => '&quot;', '\'' => '&#039;')) . '</th>' .
					'<th class="optTh">Cantitate</th>'.
					'<th class="optTh" id="th_price">' . tep_output_string($values[0]['price'], array('"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => '&gt;')) . '</th>'.
					'<th class="optTh" id="th_price_2">' . tep_output_string($values[0]['price_2'], array('"' => '&quot;', '\'' => '&#039;')) . '</th>'.
					'<th class="optTh" id="th_price_3">' . tep_output_string($values[0]['price_3'], array('"' => '&quot;', '\'' => '&#039;')) . '</th>';
				'</tr>';
	}

      $special = $values[$i]['new_price'];
      $field .= '<tr>' .
      				'<td class="subcateg" rowspan="3" '. (($default && $values[$i]['id']==$default)?'style="border-left:4px solid #e01e00;" valign="middle"><img src="images/icons/selected.gif" />':'>&nbsp;').'</td>' .
      				'<td class="mainProduct">' . tep_output_string($values[$i]['cod_unic'], array('"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => '&gt;')) . '</td>' .
      				'<td class="mainProduct">' . tep_output_string($values[$i]['text'], array('"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => '&gt;')) . '</td>' .
      				'<td class="mainProduct">' . tep_output_string($values[$i]['car_teh_1'], array('"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => '&gt;')) . '</td>' .
      				'<td class="mainProduct">' . tep_output_string($values[$i]['car_teh_2'], array('"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => '&gt;')) . '</td>' .
      				'<td style="vertical-align:bottom;padding-top:20px;"><input style="width:30px" id="cant_'.tep_output_string($values[$i]['id']).'" class="input" type="text" size="1" value="1"></td>' .
      				'<td class="mainPrice" align="left" style="vertical-align:'.($special?'bottom':'bottom').'">'.
      					'<span id="price_'. $i .'">' . tep_output_prices($values[$i]['price'], $values[$i]['price_special'], $special ) . '</span>' .
      					'<span style="visibility:hidden;font-size:0px" id="price_notax_'. $i .'">' . tep_output_prices($values[$i]['price_notax'], $values[$i]['price_special_notax'], $special ) . '</span>' .
      				'</td>' .
     				'<td class="mainPrice" align="left" style="vertical-align:'.($special?'bottom':'bottom').'">'.
     					'<span id="price_2_'. $i .'">' . (($special || $values[$i]['price_2']==$values[$i]['price'])?"-":tep_output_prices($values[$i]['price_2'], $values[$i]['price_special'], $special )) . '</span>' .
     					'<span style="visibility:hidden;font-size:0px" id="price_2_notax_'. $i .'">' . (($special || $values[$i]['price_2']==$values[$i]['price'])?"-":tep_output_prices($values[$i]['price_2_notax'], $values[$i]['price_special_notax'], $special ) ). '</span>' .
     				'</td>'.
     				'<td class="mainPrice" align="left" style="vertical-align:'.($special?'bottom':'bottom').'">'.
     					'<span id="price_3_'. $i .'">' . (($special || $values[$i]['price_3']==$values[$i]['price'])?"-":tep_output_prices($values[$i]['price_3'], $values[$i]['price_special'], $special )) . '</span>' .
     					'<span style="visibility:hidden;font-size:0px" id="price_3_notax_'. $i .'">' . (($special || $values[$i]['price_3']==$values[$i]['price'])?"-":tep_output_prices($values[$i]['price_3_notax'], $values[$i]['price_special_notax'], $special ) ). '</span>' .
     				'</td>';
      $field .= '</tr>';
      $field .= '<tr>'.
      		    	'<td align="right" colspan="8" height="5">'. tep_draw_hidden_field('products_id', $products_id) . '<input type="submit" class="btn btn-danger btn-sm" value="Adauga in cos" class="addToCart" onclick="document.getElementById(\''.tep_output_string($name).'\').value = '.tep_output_string($values[$i]['id']).';' .
      		    	'document.getElementById(\'cart_quantity\').value = document.getElementById(\'cant_'.tep_output_string($values[$i]['id']).'\').value "></td>' .
      			'</tr>';
      $field .= '<tr>'.
      				'<td colspan="8" class="subcateg" style="font-size:1px">&nbsp;</td>' .
      			'</tr>';

    }
	$field .= '</table>';

    if ($required == true) $field .= TEXT_FIELD_REQUIRED;

    return $field;
  }

////
// Output product prices
  function tep_output_prices($price, $special_price, $special = false) {
    if($special){
   		$field = '<div class="old_price"><s>' . tep_output_string($price, array('"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => '&gt;')) . '</s></div>'.tep_output_string($special_price, array('"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => '&gt;'));
    }else{
    	$field = tep_output_string($price, array('"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => '&gt;'));
    }
	
    return $field;
  
  }
////
// Output a table with product set content
  function tep_draw_product_table_set($values) {
    $field = '<table cellspacing="0" cellpadding="0" width="100%">';
    $field .= '<tr><th class="optTh">Componenta</th></tr> ';
    for ($i=0; $i<sizeof($values); $i++) {
      $field .= '<tr>' .
      				'<td class="main" height="20">&nbsp;&nbsp;' . $values[$i]['text'] . '</td>' .
      			'</tr>';

    }  
    
	$field .= '</table>';
	
    return $field;
  
  }
////
// Creates a pull-down list of countries
  function tep_get_country_list($name, $selected = '', $parameters = '') {
    $countries_array = array(array('id' => '', 'text' => PULL_DOWN_DEFAULT));
    $countries = tep_get_countries();

    for ($i=0, $n=sizeof($countries); $i<$n; $i++) {
      $countries_array[] = array('id' => $countries[$i]['countries_id'], 'text' => $countries[$i]['countries_name']);
    }

    return tep_draw_pull_down_menu($name, $countries_array, $selected, $parameters);
  }
?>