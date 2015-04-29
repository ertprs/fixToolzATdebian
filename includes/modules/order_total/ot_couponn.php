 <?php
/*
  $Id: ot_coupon.php,v 1.2 2008/02/22 00:00:00 burt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2008 Club osCommerce www.clubosc.com

  Released under the GNU General Public License
*/

  class ot_coupon {
    var $title, $output;

    function ot_coupon() {
      $this->code = 'ot_coupon';
      $this->title = MODULE_ORDER_TOTAL_COUPON_TITLE;
      $this->description = MODULE_ORDER_TOTAL_COUPON_DESCRIPTION;
      $this->enabled = ((MODULE_ORDER_TOTAL_COUPON_STATUS == 'true') ? true : false);

      $this->sort_order = MODULE_ORDER_TOTAL_COUPON_SORT_ORDER;

      $this->output = array();
    }

    function process() {
      global $order, $currencies, $discount;

      if (!isset($_SESSION['coupon_code'])) {
        $this->enabled = false;
      } else {
        if ($_SESSION['coupon_type'] == '0') {
       	  $order_total = $order->info['subtotal'] - $discount;
          $coupon_discount = tep_round($order_total * $_SESSION['coupon_amount'] / 100, $currencies->currencies[DEFAULT_CURRENCY]['decimal_places']);
        } else {
          $coupon_discount = tep_round($_SESSION['coupon_amount'], $currencies->currencies[DEFAULT_CURRENCY]['decimal_places']);
        }        		
      	
        $order->info['total'] -= $coupon_discount;

        $this->output[] = array('title' => TEXT_COUPON . ' (' . $_SESSION['coupon_code'] . ')',
                                'text' =>  '-' . $currencies->format($coupon_discount, true, $order->info['currency'], $order->info['currency_value']),
                                'value' => '');
      }
    }
    
    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_ORDER_TOTAL_COUPON_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }

      return $this->_check;
    }

    function keys() {
      return array('MODULE_ORDER_TOTAL_COUPON_STATUS', 'MODULE_ORDER_TOTAL_COUPON_SORT_ORDER');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display Coupon', 'MODULE_ORDER_TOTAL_COUPON_STATUS', 'true', 'Do you want to display the coupon discount?', '6', '1','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ORDER_TOTAL_COUPON_SORT_ORDER', '1', 'Sort order of display.', '6', '2', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
  }
?>
