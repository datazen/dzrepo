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
?>
		<div class="col-sm-12 col-lg-12"><?php echo '<h3 class="no-margin-top">'. BOX_HEADING_CUSTOMER_TESTIMONIALS .'</h3>'; ?></div>
             <?php
				echo '<div class="col-sm-12 col-lg-12"><b><center>' . $testimonial_titulo . '</center></b><br>' . strip_tags($testimonial) . '...<br><a href="' . tep_href_link(FILENAME_CUSTOMER_TESTIMONIALS, tep_get_all_get_params(array('language', 'currency')) .'&testimonial_id=' . $random_testimonial['testimonials_id']) . '">' . TEXT_READ_MORE . ' ' . tep_image(DIR_WS_IMAGES . 'star_arrow.gif') . '</b></a><br /> <b>'.$random_testimonial['testimonials_name'].'</b></div>';

           ?>
<?php
  }
?>