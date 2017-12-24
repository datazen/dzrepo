<?php
/*
  $Id: ticket.php,v 1.0.0.0 2008/01/10 19:05:51 maestro

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2001 osCommerce

  ContributionCentral, #1 Source for CRE Loaded & osCommerce Programming
  http://www.contributioncentral.com
  Copyright (c) 2008 ContributionCentral

  Released under the GNU General Public License
*/
  //require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_TICKETBOX);
?>
          <!-- ticket //-->
          <?php
            if ((defined('MODULE_ADDONS_CSMM_STATUS') && MODULE_ADDONS_CSMM_STATUS == 'True') && (isset($_SESSION['customer_id'])) || ((defined('MODULE_ADDONS_CSMM_STATUS') && MODULE_ADDONS_CSMM_STATUS == 'True') && (defined('SUPPORT_NO_LOGIN') && SUPPORT_NO_LOGIN == 'true'))) {  
          ?>
          <tr>
            <td>
            <?php
              if (TICKET_SHOW_SKYPE == 'true') {
            ?>
                <!-- Skype 'Call me!' button http://www.skype.com/go/skypebuttons -->
                <script type="text/javascript" src="<?php echo HTTPS_SERVER . DIR_WS_INCLUDES . '/javascript/skypeCheck.js'; ?>"></script>
            <?php
              }
              $info_box_contents = array();
              $info_box_contents[] = array('text'  => '<font color="' . $font_color . '">' . BOX_HEADING_SUPPORT . '</font>');
              new $infobox_template_heading($info_box_contents, tep_href_link(FILENAME_TICKET_SUPPORT, '', 'SSL'), ((isset($column_location) && $column_location !='') ? $column_location : '') );
            
              $info_box_contents = array();
              if (defined('SUPPORT_SHOW_MAIN_IMAGE') && SUPPORT_SHOW_MAIN_IMAGE == 'true') { 
                $info_box_contents[] = array('text' => tep_draw_separator('pixel_trans.gif', '100%', '3') .
                                                       '<center><a href="' . tep_href_link(FILENAME_TICKET_SUPPORT, '', 'SSL') . '"><img border="0" src="' . HTTPS_SERVER . DIR_WS_HTTPS_CATALOG . '/images/table_background_service.gif' . '" /></a></center>' .
                                                       tep_draw_separator('pixel_trans.gif', '100%', '3'));
              }
              $info_box_contents[] = array('text' => '<center><a href="' . tep_href_link(FILENAME_TICKET_CREATE, '', 'SSL') . '">' . BOX_TICKET_CREATE . '</a></center>' .
                                                     tep_draw_separator('pixel_trans.gif', '100%', '3') .
                                                     '<center><a href="' . tep_href_link(FILENAME_TICKET_VIEW, '', 'SSL') . '">' . BOX_TICKET_VIEW . '</a></center>');

              if ((defined('SUPPORT_SHOW_MESSAGES') && SUPPORT_SHOW_MESSAGES == 'true') && isset($_SESSION['customer_id'])) {
                  $cID = $_SESSION['customer_id'];
                  $privatemessage_query = tep_db_query("select count(*) as number_of_messages from " . TABLE_CUSTOMER_PRIVATE_MESSAGE . " where customers_id = '" . (int)$cID . "' and message_stat != 'Yes'");
                  $privatemessage = tep_db_fetch_array($privatemessage_query);
                  //print_r($privatemessage);
                  if ($privatemessage['number_of_messages'] > 0) {
                    $info_box_contents[] = array('text' => '<hr width="90%"><center><a href="' . tep_href_link(FILENAME_PRIVATE_MESSAGES, '', 'SSL') . '">' . BOX_TICKET_MESSAGES . '</a><br />' .
                                                            PRIVATE_MESSAGES_YES_START . ' ' . $privatemessage['number_of_messages'] . ' ' . PRIVATE_MESSAGES_YES_END . '</center>');
                  } else {
                    $info_box_contents[] = array('text' => '<hr width="90%"><center><a href="' . tep_href_link(FILENAME_PRIVATE_MESSAGES, '', 'SSL') . '">' . BOX_TICKET_MESSAGES . '</a><br />' .
                                                            PRIVATE_MESSAGES_NO_NEW . '</center>');
                  }
              }

              if (defined('SUPPORT_SHOW_FAQ') && SUPPORT_SHOW_FAQ == 'true') {

                  $faq_categories_query = tep_db_query("select ic.categories_id, icd.categories_name from " . TABLE_FAQ_CATEGORIES . " ic, " . TABLE_FAQ_CATEGORIES_DESCRIPTION . " icd where icd.categories_id = ic.categories_id and icd.language_id = '" . (int)$languages_id . "' and ic.categories_status = '1' order by ic.categories_sort_order, icd.categories_name");
                  $faq_query = tep_db_query("select ip.faq_id, ip.question from " . TABLE_FAQ . " ip left join " . TABLE_FAQ_TO_CATEGORIES . " ip2c on ip2c.faq_id = ip.faq_id where ip2c.categories_id = '0' and ip.language = '" . (int)$languages_id . "' and ip.visible = '1' order by ip.v_order, ip.question");
    
                  if ((tep_db_num_rows($faq_categories_query) > 0) || (tep_db_num_rows($faq_query) > 0)) {
                      $faq_string = '';
                        while ($faq_categories = tep_db_fetch_array($faq_categories_query)) {
                          //$id_string = 'cID=' . $faq_categories['categories_id'];
                          $faq_string .= '<a href="' . tep_href_link(FILENAME_FAQ . '?cID=' . $faq_categories['categories_id'], '', 'SSL') . '">' . $faq_categories['categories_name'] . '</a><br />' . tep_draw_separator('pixel_trans.gif', '100%', '3');
                        } 
                       
                        while ($faq = tep_db_fetch_array($faq_query)) {
                          //$id_string = 'fID=' . $faq['faq_id'];
                          $faq_string .= '<a href="' . tep_href_link(FILENAME_FAQ . '?fID=' . $faq['faq_id'], '', 'SSL') . '">' . $faq['question'] . '</a>';
                        }
    
                  $info_box_contents[] = array('text' => '<hr width="90%"><center><a href="' . tep_href_link(FILENAME_FAQ, '', 'SSL') . '">' . BOX_TICKET_FAQ . '</a><br />' . tep_draw_separator('pixel_trans.gif', '100%', '3') . $faq_string . '</center>');
 
                  }
              }
              
              if (SKYPE_SHOW_CALL == 'true') {
                  $skype_call_string = '<div align="center"><a href="skype:' . SKYPE_ID_NAME . '?call" onclick="return skypeCheck();"><img src="' . SKYPE_BUTTON_LINK . '" style="border: none;" alt="Call me!" /><br />' . TEXT_SKYPE_CALL . '</a></div>';
              }

              if (SKYPE_SHOW_CHAT == 'true') {
                  $skype_chat_string = '<div align="center"><a href="skype:' . SKYPE_ID_NAME . '?chat" onclick="return skypeCheck();"><img src="' . SKYPE_BUTTON_LINK . '" style="border: none;" alt="Chat with me!" /><br />' . TEXT_SKYPE_CHAT . '</a></div>';
              }

              if (TICKET_SHOW_SKYPE == 'true') {
                  $info_box_contents[] = array('text' => '<hr width="90%">' . $skype_call_string . $skype_chat_string);
              }

              $info_box_contents[] = array('text' => '<br />');

              new $infobox_template($info_box_contents, true, true, ((isset($column_location) && $column_location !='') ? $column_location : '') );

              if (TEMPLATE_INCLUDE_FOOTER =='true'){
                $info_box_contents = array();
                $info_box_contents[] = array('align' => 'left',
                                             'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                                            );
                new $infobox_template_footer($info_box_contents, ((isset($column_location) && $column_location !='') ? $column_location : '') );
              }

            ?>
            </td>
          </tr>
          <?php
            } else if ((defined('MODULE_ADDONS_CSMM_STATUS') && MODULE_ADDONS_CSMM_STATUS == 'True') && (!isset($_SESSION['customer_id'])) || ((defined('MODULE_ADDONS_CSMM_STATUS') && MODULE_ADDONS_CSMM_STATUS == 'True') && (defined('SUPPORT_NO_LOGIN') && SUPPORT_NO_LOGIN != 'true'))) {
          ?>
          <tr>
            <td>
            <?php
            
              $info_box_contents = array();
              $info_box_contents[] = array('text'  => '<font color="' . $font_color . '">' . BOX_HEADING_SUPPORT . '</font>');
              new $infobox_template_heading($info_box_contents, tep_href_link(FILENAME_LOGIN, '', 'SSL'), ((isset($column_location) && $column_location !='') ? $column_location : '') );
            
              $info_box_contents = array();
              $info_box_contents[] = array('text' => tep_draw_separator('pixel_trans.gif', '100%', '3'));
              if (defined('SUPPORT_SHOW_MAIN_IMAGE') && SUPPORT_SHOW_MAIN_IMAGE == 'true') {
                $info_box_contents[] = array('text' => '<div style="width: 95%"><center><a href="' . tep_href_link(FILENAME_LOGIN, '', 'SSL') . '">' . tep_image(DIR_WS_IMAGES . 'table_background_service.gif') . '</a></center>');
              }
              $info_box_contents[] = array('text' => '<center>You must <a href="' . tep_href_link(FILENAME_LOGIN, '', 'SSL') . '"><strong>Login</strong></a> to use the Support System</div>');
              $info_box_contents[] = array('text' => tep_draw_separator('pixel_trans.gif', '100%', '5'));
            
              new $infobox_template($info_box_contents, true, true, ((isset($column_location) && $column_location !='') ? $column_location : '') );

              if (TEMPLATE_INCLUDE_FOOTER =='true'){
                $info_box_contents = array();
                $info_box_contents[] = array('align' => 'left',
                                             'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                                            );
                new $infobox_template_footer($info_box_contents, ((isset($column_location) && $column_location !='') ? $column_location : '') );
              }
            
            ?>
            </td>
          </tr>
          <?php
            } else if (defined('MODULE_ADDONS_CSMM_STATUS') && MODULE_ADDONS_CSMM_STATUS !== 'True') {
          ?>
          <tr>
            <td>
            <?php
            
              $info_box_contents = array();
              $info_box_contents[] = array('text'  => '<font color="' . $font_color . '">' . BOX_HEADING_SUPPORT . '</font>');
              new $infobox_template_heading($info_box_contents, tep_href_link(FILENAME_CONTACT_US, '', 'SSL'), ((isset($column_location) && $column_location !='') ? $column_location : '') ); 
            
              $info_box_contents = array();
              $info_box_contents[] = array('text' => '<div style="width: 95%"><center><a href="' . tep_href_link(FILENAME_CONTACT_US, '', 'SSL') . '">' . tep_image(DIR_WS_IMAGES . 'table_background_noservice.gif') . '</a></center>' .
                                                     '<center>The Support System is currently <b>Disabled</b></center></div>' .
                                                     tep_draw_separator('pixel_trans.gif', '100%', '5'));
            
              new $infobox_template($info_box_contents, true, true, ((isset($column_location) && $column_location !='') ? $column_location : '') );

              if (TEMPLATE_INCLUDE_FOOTER =='true'){
                $info_box_contents = array();
                $info_box_contents[] = array('align' => 'left',
                                             'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                                            );
                new $infobox_template_footer($info_box_contents, ((isset($column_location) && $column_location !='') ? $column_location : '') );
              }
            
            ?>
            </td>
          </tr>
          <?php
            }
          ?>
          <!-- ticket_eof //-->