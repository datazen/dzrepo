<?php
/*
  $Id: $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

	define('TEXT_INFO_OK', 'Language configuration updated');

	define('TABLE_HEADING_LNG_ITEM_TITLE', 'Detected Language String (\'' . MAILBEEZ_CONFIG_LANGUAGE_DETECT_ITEM . '\')');
	define('TABLE_HEADING_LNG_ITEM_ASSIGN', 'Assigned Language');

	define('TABLE_HEADING_COUNTRY_ITEM_TITLE', 'Country');
	define('TABLE_HEADING_COUNTRY_ITEM_ASSIGN', 'Assigned Language');	
	
	
	define('BUTTON_BACK', 'Back');
	
	$app_path_module = 'config_language';
	
	$back_url = mh_href_link(FILENAME_MAILBEEZ, 'module=' . $_GET['module']);

 	$app_action = (isset($_GET['app_action']) ? $_GET['app_action'] : '');
	$msg='';

  if (mh_not_null($app_action)) {
    switch ($app_action) {
      case 'save':
				$detected_languages_items_store_array = $_POST['language_item'];
				$country_store_array = $_POST['country'];
				
				mh_db_query("delete from " . TABLE_MAILBEEZ_LANGUAGE_CONFIGURATION . " where language_cfg_type = '" . MAILBEEZ_CONFIG_LANGUAGE_DETECT_ITEM . "'");
				foreach ($detected_languages_items_array as $lng_item) {
					//echo $lng_item['item']  . ':' .  $detected_languages_items_store_array[$lng_item['id']];
					$sql_data_array = array('language_cfg_type' => MAILBEEZ_CONFIG_LANGUAGE_DETECT_ITEM,
																	'language_cfg_content' => $lng_item['item'],
																	'language_id' => $detected_languages_items_store_array[$lng_item['id']],
																	'last_modified' => '',
																	'date_added' => 'now()');
					mh_db_perform(TABLE_MAILBEEZ_LANGUAGE_CONFIGURATION, $sql_data_array);
				}
				
				
				mh_db_query("delete from " . TABLE_MAILBEEZ_LANGUAGE_CONFIGURATION . " where language_cfg_type = 'country_code'");				
				foreach ($country_store_array as $country_store_id => $country_store_lng_id ) {
					//echo $country_store_id . ' => ' . $country_store_lng_id . '<br>';
					$sql_data_array = array('language_cfg_type' => 'country_code',
																	'language_cfg_content' => $country_store_id,
																	'language_id' => $country_store_lng_id ,
																	'last_modified' => '',
																	'date_added' => 'now()');
					mh_db_perform(TABLE_MAILBEEZ_LANGUAGE_CONFIGURATION, $sql_data_array);
				}
				$msg = TEXT_INFO_OK;
        break;
    }
  }
?>	

<?php 
	// back to sequence page
	echo mb_admin_button($back_url, BUTTON_BACK, '', 'link') . '<br><br>';
 ?>

			
				<table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
							<tr>
								<td width="100%" valign="top" class="smallText">
								<div style="border: 1px solid #909090; padding: 10px; margin-top: 10px; background-color: #e9e9e9; min-height: 300px;">
								<img src="<?php echo MH_CATALOG_SERVER  . DIR_WS_CATALOG ?>/mailhive/common/images/been_free.png" width="93" height="82" alt="" border="0" align="left" hspace="1" style="margin-right: 20px;"><h1>Uninstall MailBeez</h1>
								
								</div>

								</td>
							</tr>
              <tr>
								<td>
								<br>
								<br>
					
								</td>
              </tr>
            </table></td>
          </tr>
        </table>				