<?php
/*
  $Id: address_book_details.php,v 1.2 2004/03/05 00:36:42 ccwjr Exp $

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
        <h3><?php echo NEW_ADDRESS_TITLE; ?></h3>

			<?php
			  if (ACCOUNT_GENDER == 'true') {
				if (isset($gender)) {
				  $male = ($gender == 'm') ? true : false;
				} else {
				  $male = (isset($entry['entry_gender']) && $entry['entry_gender'] == 'm') ? true : false;
				}
				$female = !$male;
			?>

					 <div class="form-group full-width margin-bottom"><label class="sr-only"></label>  <?php echo ENTRY_GENDER; ?>
						<?php echo tep_draw_radio_field('gender', 'm', $male) . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . tep_draw_radio_field('gender', 'f', $female) . '&nbsp;&nbsp;' . FEMALE . '&nbsp;' . (tep_not_null(ENTRY_GENDER_TEXT) ? '<span class="inputRequirement">' . ENTRY_GENDER_TEXT . '</span>': ''); ?>
					</div>
			<?php
			  }
			?>

            <div class="form-group full-width margin-bottom"><label class="sr-only"></label>
            <?php echo ENTRY_FIRST_NAME; ?>
            <?php echo tep_draw_input_field('firstname', (isset($entry['entry_firstname']) ? $entry['entry_firstname'] : '')) . '' . (tep_not_null(ENTRY_FIRST_NAME_TEXT) ? '<span class="inputRequirement"></span>': ''); ?>
            </div>

            <div class="form-group full-width margin-bottom"><label class="sr-only"></label>

            <?php echo ENTRY_LAST_NAME; ?>
            <?php echo tep_draw_input_field('lastname', (isset($entry['entry_lastname']) ? $entry['entry_lastname'] : '')) . '' . (tep_not_null(ENTRY_LAST_NAME_TEXT) ? '<span class="inputRequirement"></span>': ''); ?>
            </div>

            <div class="form-group full-width margin-bottom"><label class="sr-only"></label>

                    <?php echo ENTRY_EMAIL_ADDRESS; ?>
                    <?php echo tep_draw_input_field('email_address', (isset($entry['entry_email_address']) ? $entry['entry_email_address'] : '')) . '' . (tep_not_null(ENTRY_EMAIL_ADDRESS_TEXT) ? '<span class="inputRequirement"></span>': ''); ?>
			</div>

            <div class="form-group full-width margin-bottom"><label class="sr-only"></label>

                     <?php echo ENTRY_TELEPHONE_NUMBER; ?>
                     <?php echo tep_draw_input_field('telephone', (isset($entry['entry_telephone']) ? $entry['entry_telephone'] : '')) . '' . (tep_not_null(ENTRY_TELEPHONE_NUMBER_TEXT) ? '<span class="inputRequirement"></span>': ''); ?>

			</div>
            <div class="form-group full-width margin-bottom"><label class="sr-only"></label>
                     <?php echo ENTRY_FAX_NUMBER; ?>
                     <?php echo tep_draw_input_field('fax', (isset($entry['entry_fax']) ? $entry['entry_fax'] : '')) . '' . (tep_not_null(ENTRY_FAX_NUMBER_TEXT) ? '<span class="inputRequirement"></span>': ''); ?>
			</div>
	  </div>
	 </div>


<div class="col-sm-6 col-lg-6 margin-top clearfix">
	 <div class="well no-padding-top">
					<h3></h3>
						<div class="form-group full-width margin-bottom"><label class="sr-only"></label>

						 <?php echo ENTRY_STREET_ADDRESS; ?>
						 <?php echo tep_draw_input_field('street_address', (isset($entry['entry_street_address']) ? $entry['entry_street_address'] : '')) . '' . (tep_not_null(ENTRY_STREET_ADDRESS_TEXT) ? '<span class="inputRequirement"></span>': ''); ?>
						</div>
			<?php
			  if (ACCOUNT_SUBURB == 'true') {
			?>
						<div class="form-group full-width margin-bottom"><label class="sr-only"></label>

						 <?php echo ENTRY_SUBURB; ?>
						 <?php echo tep_draw_input_field('suburb', (isset($entry['entry_suburb']) ? $entry['entry_suburb'] : '')) . '' . (tep_not_null(ENTRY_SUBURB_TEXT) ? '<span class="inputRequirement"></span>': ''); ?>
					   </div>
			<?php
			  }
			?>
						<div class="form-group full-width margin-bottom"><label class="sr-only"></label>

						 <?php echo ENTRY_POST_CODE; ?>
						 <?php echo tep_draw_input_field('postcode', (isset($entry['entry_postcode']) ? $entry['entry_postcode'] : ''),'maxlength="10"') . '' . (tep_not_null(ENTRY_POST_CODE_TEXT) ? '<span class="inputRequirement"></span>': ''); ?>

						</div>

							<div class="form-group full-width margin-bottom"><label class="sr-only"></label>

							 <?php echo ENTRY_CITY; ?>
							 <?php echo tep_draw_input_field('city', (isset($entry['entry_city']) ? $entry['entry_city'] : '')) . '' . (tep_not_null(ENTRY_CITY_TEXT) ? '<span class="inputRequirement"></span>': ''); ?>
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
						$zones_array[] = array('id' => '', 'text' => '');
						$zones_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' order by zone_name");
						while ($zones_values = tep_db_fetch_array($zones_query)) {
						  $zones_array[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
						}
						echo tep_draw_pull_down_menu('state', $zones_array,'class="form-control"');
					  } else {
						echo tep_draw_input_field('state','class="form-control"');
					  }
					} else {
					  echo tep_draw_input_field('state', tep_get_zone_name((isset($entry['entry_country_id']) ? $entry['entry_country_id'] : 0), (isset($entry['entry_zone_id']) ? $entry['entry_zone_id'] : 0 ), (isset($entry['entry_state']) ? $entry['entry_state'] : 0)),'class="form-control"');
					}

					if (tep_not_null(ENTRY_STATE_TEXT));
				?>
					   </div>
				<?php
				  }
				?>
							<div class="form-group full-width margin-bottom"><label class="sr-only"></label>

							 <?php echo ENTRY_COUNTRY; ?>
							 <?php echo tep_get_country_list('country' ,(isset($entry['entry_country_id']) ? $entry['entry_country_id'] : ''), 'class="form-control"') . '' . (tep_not_null(ENTRY_COUNTRY_TEXT) ? '': ''); ?>
							</div>
				<?php
				  if ((isset($_GET['edit']) && (isset($_SESSION['customer_default_address_id'])) &&  ($_SESSION['customer_default_address_id'] != $_GET['edit'])) || (isset($_GET['edit']) == false) ) {
				?>

							<div class="checkbox small-margin-left">


							 <?php echo tep_draw_checkbox_field('primary', 'on', false, 'id="primary"') . ' ' . SET_AS_PRIMARY; ?>
							</div>

				<?php
				  }
				?>
  </div>
 </div>
</div>
<?php
 if (ACCOUNT_COMPANY == 'true') {
?>
 <div class="row">

<div class="col-sm-12 col-lg-12 margin-top clearfix">
  <div class="well no-padding-top">

   <h3><?php echo CATEGORY_COMPANY; ?></h3>

		<div class="form-group full-width margin-bottom"><label class="sr-only"></label>

						 <?php echo ENTRY_COMPANY; ?>
						 <?php echo tep_draw_input_field('company', (isset($entry['entry_company']) ? $entry['entry_company'] : '')) . '' . (tep_not_null(ENTRY_COMPANY_TEXT) ? '<span class="inputRequirement"></span>': ''); ?>
						</div>
				   <!-- Eversun mod for sppc and qty price breaks -->
			<?php
			   if (isset($entry['entry_company_tax_id']) && tep_not_null($entry['entry_company_tax_id'])) {
			   ?>
						<div class="form-group full-width margin-bottom"><label class="sr-only"></label>

						 <?php echo ENTRY_COMPANY_TAX_ID; ?>
						 <?php echo $entry['entry_company_tax_id'] ; ?>
						</div>
			 <?php
			   } else { // end if (tep_not_null($entry['entry_company_tax_id']))
			 ?>
						<div class="form-group full-width margin-bottom"><label class="sr-only"></label>
						 <?php echo ENTRY_COMPANY_TAX_ID; ?>
						 <?php echo tep_draw_input_field('company_tax_id') . '&nbsp;' . (tep_not_null(ENTRY_COMPANY_TAX_ID_TEXT) ? '<span class="inputRequirement"></span>': ''); ?>
						</div>
			 <?php
			   } // end else
			?><!--Eversun mod end for sppc and qty price breaks -->

  </div>
 </div>
</div>
<?php
  }
?>


