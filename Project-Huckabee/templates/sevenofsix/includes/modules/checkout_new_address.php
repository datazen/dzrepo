<?php
/*
  $Id: checkout_new_address.php,v 1.1.1.1 2004/03/04 23:41:09 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  if (!isset($process)) $process = false;
?>
 <div class="row">
   <div class="col-sm-6 col-lg-6 large-padding-left margin-top">
   <div class="well no-padding-top">
   <h1></h1>

<?php
  if (ACCOUNT_GENDER == 'true') {
    if (isset($gender)) {
      $male = ($gender == 'm') ? true : false;
      $female = ($gender == 'f') ? true : false;
    } else {
      $male = false;
      $female = false;
    }
?>
 <div class="form-group full-width margin-bottom"><label class="sr-only"></label><?php echo ENTRY_GENDER; ?>
  <?php echo tep_draw_radio_field('gender', 'm', $male) . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . tep_draw_radio_field('gender', 'f', $female) . '&nbsp;&nbsp;' . FEMALE . '&nbsp;' . (tep_not_null(ENTRY_GENDER_TEXT) ? '<span class="inputRequirement">' . ENTRY_GENDER_TEXT . '</span>': ''); ?>
</div>
<?php
  }
?>
  <div class="form-group full-width margin-bottom"><label class="sr-only"></label><?php echo ENTRY_FIRST_NAME; ?>
    <?php echo tep_draw_input_field('firstname') . '' . (tep_not_null(ENTRY_FIRST_NAME_TEXT) ? '': ''); ?>
  </div>
 <div class="form-group full-width margin-bottom"><label class="sr-only"></label>
    <?php echo ENTRY_LAST_NAME; ?>
    <?php echo tep_draw_input_field('lastname') . '' . (tep_not_null(ENTRY_LAST_NAME_TEXT) ? '' : ''); ?></td>
  </div>
            <div class="form-group full-width margin-bottom"><label class="sr-only"></label>

                    <?php echo ENTRY_EMAIL_ADDRESS; ?>
					<?php echo tep_draw_input_field('email_address') . '' . (tep_not_null(ENTRY_EMAIL_ADDRESS_TEXT) ? '': ''); ?></div>
  </div>
</div>
<div class="col-sm-6 col-lg-6 margin-top clearfix">
  <div class="well no-padding-top">
     <h1></h1>


                <div class="form-group full-width margin-bottom"><label class="sr-only"></label>
                <?php echo ENTRY_TELEPHONE_NUMBER; ?>
                <?php echo tep_draw_input_field('telephone') . '' . (tep_not_null(ENTRY_TELEPHONE_NUMBER_TEXT) ? '': ''); ?>
              </div>
              <div class="form-group full-width margin-bottom"><label class="sr-only"></label>
              <?php echo ENTRY_FAX_NUMBER; ?>
                <?php echo tep_draw_input_field('fax') . '' . (tep_not_null(ENTRY_FAX_NUMBER_TEXT) ? '': ''); ?>
              </div>
<?php
  if (ACCOUNT_COMPANY == 'true') {
?>

            <div class="form-group full-width margin-bottom"><label class="sr-only"></label>
			 <?php echo ENTRY_COMPANY; ?>
             <?php echo tep_draw_input_field('company') . '' . (tep_not_null(ENTRY_COMPANY_TEXT) ? '<span class="inputRequirement">' . ENTRY_COMPANY_TEXT . '</span>': ''); ?>
  			</div>
<?php
  }
?>
</div>
</div>
</div>
 <div class="row">
<div class="col-sm-6 col-lg-6 margin-top clearfix">
  <div class="well no-padding-top">
     <h1></h1>

 <div class="form-group full-width margin-bottom"><label class="sr-only"></label>
  <?php echo ENTRY_STREET_ADDRESS; ?>
    <?php echo tep_draw_input_field('street_address') . '' . (tep_not_null(ENTRY_STREET_ADDRESS_TEXT) ? '': ''); ?>
  </div>
<?php
  if (ACCOUNT_SUBURB == 'true') {
?>
  <div class="form-group full-width margin-bottom"><label class="sr-only"></label>
    <?php echo ENTRY_SUBURB; ?>
    <?php echo tep_draw_input_field('suburb') . '' . (tep_not_null(ENTRY_SUBURB_TEXT) ? '': ''); ?>
  </div>
<?php
  }
?>
 <div class="form-group full-width margin-bottom"><label class="sr-only"></label>
 <?php echo ENTRY_POST_CODE; ?>
    <?php echo tep_draw_input_field('postcode','','maxlength="10"') . '' . (tep_not_null(ENTRY_POST_CODE_TEXT) ? '': ''); ?>
  </div>
</div>
</div>
<div class="col-sm-6 col-lg-6 margin-top clearfix">
  <div class="well no-padding-top">
     <h1></h1>
 <div class="form-group full-width margin-bottom"><label class="sr-only"></label>
    <?php echo ENTRY_CITY; ?>
   <?php echo tep_draw_input_field('city') . '' . (tep_not_null(ENTRY_CITY_TEXT) ? '': ''); ?>
  </div>
<?php
  if (ACCOUNT_STATE == 'true') {
?>
 <div class="form-group full-width margin-bottom"><label class="sr-only"></label>
    <?php echo ENTRY_STATE; ?>

<?php
    if ($process == true) {
      if ($entry_state_has_zones == true) {
        $zones_array = array();
        $zones_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' order by zone_name");
        while ($zones_values = tep_db_fetch_array($zones_query)) {
          $zones_array[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
        }
        echo tep_draw_pull_down_menu('state', $zones_array,'class="form-control"');
      } else {
        echo tep_draw_input_field('state');
      }
    } else {
      echo tep_draw_input_field('state');
    }

    if (tep_not_null(ENTRY_STATE_TEXT)) ;
?>

  </div>
<?php
  }
?>
 <div class="form-group full-width margin-bottom"><label class="sr-only"></label>

    <?php echo ENTRY_COUNTRY; ?>
	<?php echo tep_get_country_list('country' ,(isset($entry['entry_country_id']) ? $entry['entry_country_id'] : ''), 'class="form-control"') . '&nbsp;' . (tep_not_null(ENTRY_COUNTRY_TEXT) ? '': ''); ?>
  </div>
</div>
</div>
</div>


