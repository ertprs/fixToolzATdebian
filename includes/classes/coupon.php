 <?php
/*
  $Id$ coupon.php order_total module

  Copyright (c) 2008 Club osCommerce www.clubosc.com

  Released under the GNU General Public License

*/

  class osC_Coupon {
    
  	var $coupon_code;
  	var $is_valid_coupon;
  	var $coupon_details;
  	
    function osC_Coupon($coupon_code = '') {
      
    	$this->coupon_code = $coupon_code;
        $this->is_valid_coupon = $this->check_coupon();
        
        if ($this->is_valid_coupon == 'true') {
          $coupon_query = tep_db_query("select coupon_id, coupon_amount, coupon_code, coupon_type from " . TABLE_COUPONS . " where coupon_code = '" . $this->coupon_code . "'"); 
          $this->coupon_details = tep_db_fetch_array($coupon_query);
        }
    }

    function check_coupon() {
    	$coupon_query = tep_db_query("select coupon_id, coupon_code, coupon_use, coupon_status from " . TABLE_COUPONS . " where coupon_code = '" . $this->coupon_code . "'");
        $coupon_info = tep_db_fetch_array($coupon_query);

        if (isset($coupon_info['coupon_code'])) {
          if ($coupon_info['coupon_status'] == '0') {
            return false;
          }
        
          if ($coupon_info['coupon_use'] == '0') {
            if ($this->has_used($coupon_info['coupon_id']) == true) {
          	  return 'used';
            }
          }

         return 'true';
       } else {
       	return false;
      }
    }

    function has_used($coupon_id) {
      global $customer_id;
      
      $coupon_query = tep_db_query("select count(*) as total from " . TABLE_COUPONS_TO_CUSTOMER . " where coupon_id = '" . $coupon_id . "' and customer_id = '" . $customer_id . "'");
      $coupons = tep_db_fetch_array($coupon_query);

      if ($coupons['total'] >= '1') {
      	return true;
      } else {
      	return false;
      }
    }
  }
?>
