<?php
    function easy_discount_display() {
      global $currencies, $easy_discount;
      $discount = '';
      if ($easy_discount->count() > 0) {
        $easy_discounts = $easy_discount->get_all();
        $n = sizeof($easy_discounts);
        for ($i=0;$i < $n; $i++) {
           $discount .= $easy_discounts[$i]['description'].': - ' . $currencies->format($easy_discounts[$i]['amount'])."&nbsp;&nbsp;&nbsp;".(($i+1!=$n)?"<br>":"");
        }
      }
      return $discount;
    }
?>