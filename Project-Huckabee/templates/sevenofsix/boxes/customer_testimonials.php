<?php
/*
  $Id: customer_testimonials.php,v 1.3 2007/09/03 meastro Exp $

  Contribution Central, Custom CRE Loaded Programming
  http://www.contributioncentral.com
  Copyright (c) 2007 Contribution Central

  Released under the GNU General Public License
*/
include(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CUSTOMER_TESTIMONIALS);
if ($random_testimonial = tep_random_select("select * FROM " . TABLE_CUSTOMER_TESTIMONIALS . " WHERE status = 1 ORDER BY rand() LIMIT 1")) {
  $mainpage_module_query = tep_db_query("select module_one, module_two, module_three, module_four, module_five, module_six from " . TABLE_TEMPLATE . " where template_id = '" . TEMPLATE_ID . "'");
  $mainpage_query = tep_db_fetch_array($mainpage_module_query);
  //print_r($mainpage_query);

  if ( $_SERVER['REQUEST_URI'] == '/index.php' && (($mainpage_query['module_one'] == 'customer_testimonials.php') || ($mainpage_query['module_two'] == 'customer_testimonials.php') || ($mainpage_query['module_three'] == 'customer_testimonials.php') || ($mainpage_query['module_four'] == 'customer_testimonials.php') || ($mainpage_query['module_five'] == 'customer_testimonials.php') || ($mainpage_query['module_six'] == 'customer_testimonials.php')) ) {
    '';
  } else {
?>
     <div class="well">
      <div class="box-header small-margin-bottom small-margin-left"><?php echo  BOX_HEADING_CUSTOMER_TESTIMONIALS ; ?></div>
            <?php

              $testimonial = substr($random_testimonial['testimonials_html_text'], 0, 100);

              echo '<a href="' . tep_href_link(FILENAME_CUSTOMER_TESTIMONIALS, 'testimonial_id=' . $random_testimonial['testimonials_id']) . '"><b><center>' . $testimonial_titulo . '</center></b><br>' . strip_tags($testimonial) . '...<br><br><b>' . TEXT_READ_MORE . '</b></a><br><br>' .  '<b>'.$random_testimonial['testimonials_name'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>';
            ?>
    </div>

<?php
  }
}
?>