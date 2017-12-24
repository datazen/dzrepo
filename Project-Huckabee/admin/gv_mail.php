<?php
/*
  $Id: gv_mail.php,v 1.1.1.1 2004/03/04 23:38:35 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');
require(DIR_WS_CLASSES . 'currencies.php');
$currencies = new currencies();
$currency_value = $currencies->currencies[$currency]['value'];
function tep_get_current_language($language_id) {
  $languages_query = tep_db_query("select languages_id, name, code, image, directory from " . TABLE_LANGUAGES . " where languages_id = '" . $language_id . "' order by sort_order");
  while ($languages = tep_db_fetch_array($languages_query)) {
    $languages_array1 = array('id' => $languages['languages_id'],
                              'name' => $languages['name'],
                              'code' => $languages['code'],
                              'image' => $languages['image'],
                              'directory' => $languages['directory']);
  }
  return $languages_array1;
}
$languages = tep_get_languages();
$cur_language = tep_get_current_language($languages_id);
$languages_name = $cur_language['name'];
$languages_image = $cur_language['image'];
$languages_directory = $cur_language['directory'];
if ( (isset($_GET['action']) && $_GET['action'] == 'send_email_to_user') && ($_POST['customers_email_address'] || $_POST['email_to']) && (!$_POST['back_x']) ) {
  switch ($_POST['customers_email_address']) {
    case '***':
      $mail_query = tep_db_query("select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS);
      $mail_sent_to = TEXT_ALL_CUSTOMERS;
      break;
    case '**D':
      $mail_query = tep_db_query("select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS . " where customers_newsletter = '1'");
      $mail_sent_to = TEXT_NEWSLETTER_CUSTOMERS;
      break;
    default:
      $customers_email_address = strtolower(tep_db_prepare_input($_POST['customers_email_address']));
      $mail_query = tep_db_query("select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS . " where lower(customers_email_address) = '" . tep_db_input($customers_email_address) . "'");
      $mail_info = tep_db_fetch_array($mail_query);
      $mail_to_name = $mail_info['customers_firstname'] . ' ' .  $mail_info['customers_lastname'];
      $mail_sent_to = strtolower($_POST['customers_email_address']);
      if (isset($_POST['email_to']) && $_POST['email_to'] != '') {
        $mail_sent_to = strtolower($_POST['email_to']);
      }
      break;
  }
  $from = tep_db_prepare_input($_POST['from']);
  $subject = tep_db_prepare_input($_POST['subject']);
  $_POST['amount'] = str_replace($currencies->currencies[DEFAULT_CURRENCY]['symbol_left'], '', $_POST['amount']);
  $_POST['amount'] = str_replace($currencies->currencies[DEFAULT_CURRENCY]['symbol_right'], '', $_POST['amount']);
  $_POST['amount'] = trim($_POST['amount']);
  //==============  GSR START ==============
  $coupon_type = 'G';
  if (substr($_POST['amount'], -1) == '%') $coupon_type='P';
  //==============  GSR END ==============
  if (isset($mail_sent_to) && $mail_sent_to != '') {
    if (EMAIL_USE_HTML == 'false') {
      $id1 = create_coupon_code($mail_sent_to);
      $message = tep_db_encoder(tep_db_prepare_input($_POST['message']));
      //==============  GSR START ==============
      //$message .= "\n\n" . TEXT_GV_WORTH  . $currencies->format($_POST['amount'],true,DEFAULT_CURRENCY,$currency_value) . "\n\n";
      $message .= "\n\n" . TEXT_GV_WORTH  . (($coupon_type==P) ? $_POST['amount'] ."%" : $currencies->format($_POST['amount'],true,DEFAULT_CURRENCY,$currency_value)) . "\n\n";
      //==============  GSR END ==============
      $message .= TEXT_TO_REDEEM_TEXT;
      $message .= TEXT_WHICH_IS . $id1 . TEXT_IN_CASE . "\n\n";
      $message .= TEXT_OR_VISIT . '<a href="' .  HTTP_SERVER  . DIR_WS_CATALOG . '">' . HTTP_SERVER  . DIR_WS_CATALOG  . '</a>' . TEXT_ENTER_CODE;
      $message .= TEXT_TO_REDEEM1 ;
      $message .= TEXT_REMEMBER . "\n";
    } else {
      $id1 = create_coupon_code($mail_sent_to);
      $message = tep_db_encoder(tep_db_prepare_input($_POST['message']));
      //==============  GSR START ==============
      //$message .= "\n\n" . TEXT_GV_WORTH  . $currencies->format($_POST['amount'],true,DEFAULT_CURRENCY,$currency_value) . "\n\n";
      $message .= "\n\n" . TEXT_GV_WORTH  . (($coupon_type==P) ? $_POST['amount'] ."%" : $currencies->format($_POST['amount'],true,DEFAULT_CURRENCY,$currency_value)) . "\n\n";
      //==============  GSR END ==============
      $message .= TEXT_TO_REDEEM;
      $message .= TEXT_WHICH_IS . $id1 . TEXT_IN_CASE . "\n\n";
      $message .= '<a href="' . HTTP_SERVER  . DIR_WS_CATALOG . 'gv_redeem.php' . '?gv_no='.$id1 .'">' .  HTTP_SERVER  . DIR_WS_CATALOG . 'gv_redeem.php' . '?gv_no='.$id1 . '</a>' . "\n\n";
      $message .= TEXT_OR_VISIT . '<a href="' .  HTTP_SERVER  . DIR_WS_CATALOG . '">' . HTTP_SERVER  . DIR_WS_CATALOG  . '</a>' . TEXT_ENTER_CODE;
      $message .= TEXT_TO_REDEEM1 ;
      $message .= TEXT_REMEMBER . "\n";
    }
    //Let's build a message object using the email class
    $mimemessage = new email(array('X-Mailer: ' . PROJECT_VERSION . ' mailer'));
    // add the message to the object
    if (EMAIL_USE_HTML == 'false') {
      $mimemessage->add_text($message);
    } else {
      $mimemessage->add_html($message);
    }
    if($mail_to_name == '') { $mail_to_name = $mail_sent_to;}
    $sender_query = tep_db_query ("select admin_id, admin_firstname, admin_lastname from " . TABLE_ADMIN . "  where admin_id= " . $_SESSION['login_id']);
    $sender = tep_db_fetch_array($sender_query);
    $sender_name = $sender['admin_firstname'] . ' ' . $sender['admin_lastname'];
    $sender_id = $sender['admin_id'];
    $mimemessage->build_message();
    if ($_POST['customers_email_address'] == '***') {
      while($tmp_db = tep_db_fetch_array($mail_query)) {
        $mail_to_name = $tmp_db['customers_firstname']. " ".$tmp_db['customers_lastname'];
        $mail_sent_to = $tmp_db['customers_email_address'];
        $mimemessage->send($mail_to_name, $mail_sent_to, '', $from, $subject);
      }  
    } else if ($_POST['customers_email_address'] == '**D') {
      while($tmp_db = tep_db_fetch_array($mail_query)) {
        $mail_to_name = $tmp_db['customers_firstname']. " ".$tmp_db['customers_lastname'];
        $mail_sent_to = $tmp_db['customers_email_address'];
        $mimemessage->send($mail_to_name, $mail_sent_to, '', $from, $subject);
      }  
    } else {
      $mimemessage->send($mail_to_name, $mail_sent_to, '', $from, $subject);
    }
    // Now create the coupon email entry
    //==============  GSR START ==============
    //$insert_query = tep_db_query("insert into " . TABLE_COUPONS . " (coupon_code, coupon_type, coupon_amount, date_created) values ('" . $id1 . "', 'G', '" . $_POST['amount'] . "', now())");
    $insert_query = tep_db_query("insert into " . TABLE_COUPONS . " (coupon_code, coupon_type, coupon_amount, date_created) values ('" . $id1 . "', '" . $coupon_type . "', '" . $_POST['amount'] . "', now())");
    $insert_id = tep_db_insert_id();
    //==============  GSR END ==============
    $insert_query = tep_db_query("insert into " . TABLE_COUPON_EMAIL_TRACK . " (coupon_id, customer_id_sent, sent_firstname, emailed_to, date_sent) values ('" . $insert_id ."', '" . $sender_id . "', '" . $sender_name . "', '" . $mail_sent_to . "', now() )");
  }
  tep_redirect(tep_href_link(FILENAME_GV_MAIL, 'mail_sent_to=' . urlencode($mail_sent_to)));
}
if ( (isset($_GET['action']) && $_GET['action'] == 'preview') && (!$_POST['customers_email_address']) && (!$_POST['email_to']) ) {
  $messageStack->add('search', ERROR_NO_CUSTOMER_SELECTED, 'error');
}
if ( (isset($_GET['action']) && $_GET['action'] == 'preview') && (!$_POST['amount']) ) {
  $messageStack->add('search', ERROR_NO_AMOUNT_SELECTED, 'error');
}
if (isset($_GET['mail_sent_to']) && $_GET['mail_sent_to']) {
  $messageStack->add('search', sprintf(NOTICE_EMAIL_SENT_TO, $_GET['mail_sent_to']), 'success');
}
?>
<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="icon" type="image/png" href="favicon.ico" />
<script type="text/javascript" src="<?php echo (($request_type == 'SSL') ? 'https:' : 'http:'); ?>//ajax.googleapis.com/ajax/libs/jquery/<?php echo JQUERY_VERSION; ?>/jquery.min.js"></script>


  <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
                                                             <link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
  <!-- ================== BEGIN BASE CSS STYLE ================== -->
  <link href="<?php echo (($request_type == 'SSL') ? 'https:' : 'http:'); ?>//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <link href="assets/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css" rel="stylesheet" />
  <link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
  <link href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
  <link href="assets/css/animate.min.css" rel="stylesheet" />
  <link href="assets/css/style.min.css" rel="stylesheet" />
  <link href="assets/css/style-responsive.min.css" rel="stylesheet" />
  <link href="assets/css/theme/blue.css" rel="stylesheet" id="theme" />
  <!-- ================== END BASE CSS STYLE ================== -->
  
  <!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
  <link href="assets/plugins/jquery-jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" />
  <link href="assets/plugins/bootstrap-datepicker/css/datepicker.css" rel="stylesheet" />
  <link href="assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" />
    <link href="assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />  
    <link href="assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css" rel="stylesheet" />
    <link href="assets/plugins/DataTables/extensions/Select/css/select.bootstrap.min.css" rel="stylesheet" />
    <link href="assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css" rel="stylesheet" />
  <!-- ================== END PAGE LEVEL STYLE ================== -->
  <script language="javascript" src="includes/general.js"></script>
  <script type="text/javascript" src="includes/menu.js"></script>
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="includes/stylesheet-ie.css">
<![endif]-->
<script language="javascript"><!--
function disableButton(obj) {
  for (z=1; z < obj.length; z++) {
    if (obj[z].type == 'submit') {
      obj[z].disabled = true;
      break;
    }
  }
}
--></script>
<!-- Tabs code -->
<script type="text/javascript" src="includes/javascript/tabpane/local/webfxlayout.js"></script>
<link type="text/css" rel="stylesheet" href="includes/javascript/tabpane/tab.webfx.css">
<style type="text/css">
.dynamic-tab-pane-control h2 {
  text-align: center;
  width:    auto;
}

.dynamic-tab-pane-control h2 a {
  display:  inline;
  width:    auto;
}

.dynamic-tab-pane-control a:hover {
  background: transparent;
}
</style>
<script type="text/javascript" src="includes/javascript/tabpane/tabpane.js"></script>
<!-- End Tabs -->

<?php 
if (EMAIL_USE_HTML == 'true'){
  include('includes/javascript/editor.php');
  echo tep_load_html_editor();
}
?>

</head>
<body>
    <!-- begin #page-container -->
    <div id="page-container" class="fade page-sidebar-fixed page-header-fixed gradient-enabled">
    <!-- header //-->
    <?php require(DIR_WS_INCLUDES . 'header.php'); ?>
    <!-- header_eof //-->
      
    <!-- left_navigation //-->
    <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
    <!-- left_navigation_eof //-->
      
    <!-- begin #content -->
    <div id="content" class="content">
      <!-- begin breadcrumb -->
      <ol class="breadcrumb pull-right">
        <li>Create &nbsp; <a title="<?php echo BOX_MANUAL_ORDER_CREATE_ACCOUNT;?>" href="<?php echo tep_href_link(FILENAME_CREATE_ACCOUNT,'','SSL');?>" class="btn btn-xs btn-header"><i class="fa fa-user"></i><span class="label">+</span></a> <a title="<?php echo BOX_MANUAL_ORDER_CREATE_ORDER;?>" href="<?php echo tep_href_link(FILENAME_CREATE_ORDER,'','SSL');?>" class="btn btn-xs btn-header"><i class="fa fa-shopping-cart"></i><span class="label">+</span></a></li>
        <li>Search &nbsp; <a href="javascript:;" class="btn btn-header btn-xs header-popover" id="ProductsPopover">Products</a> <a href="javascript:;" class="btn btn-header btn-xs header-popover" id="CustomerPopover">Customers</a> <a href="javascript:;" class="btn btn-header btn-xs header-popover" id="OrdersPopover">Orders</a> <a href="javascript:;" class="btn btn-header btn-xs header-popover" id="PagesPopover">Pages</a></li>
      </ol>
      <!-- end breadcrumb -->
      <!-- begin page-header -->
      <h1 class="page-header"><?php echo HEADING_TITLE; ?></h1>
      <!-- end page-header -->
      
    <!-- begin panel -->
    <div class="panel panel-inverse"><div class="panel-body"><table border="0" width="100%" cellspacing="0" cellpadding="0" >
          <?php
          if ( (isset($_GET['action']) && $_GET['action'] == 'preview') && ($_POST['customers_email_address'] || $_POST['email_to']) ) {
            switch ($_POST['customers_email_address']) {
              case '***':
                $mail_sent_to = TEXT_ALL_CUSTOMERS;
                break;
              case '**D':
                $mail_sent_to = TEXT_NEWSLETTER_CUSTOMERS;
                break;
              default:
                $mail_sent_to = strtolower($_POST['customers_email_address']);
                if (tep_not_null($_POST['email_to'])) {
                  $mail_sent_to = strtolower($_POST['email_to']);
                }
                break;
            }
            $_POST['amount'] = str_replace($currencies->currencies[DEFAULT_CURRENCY]['symbol_left'], '', $_POST['amount']);
            $_POST['amount'] = str_replace($currencies->currencies[DEFAULT_CURRENCY]['symbol_right'], '', $_POST['amount']);
            $_POST['amount'] = trim($_POST['amount']);
            //==============  GSR START ==============
            $coupon_type = 'G';
            if (substr($_POST['amount'], -1) == '%') $coupon_type='P';
            //==============  GSR END ==============
            ?>
            <tr><?php echo tep_draw_form('mail', FILENAME_GV_MAIL, 'action=send_email_to_user', 'post', 'onsubmit="disableButton(this);"'); ?>
              <td><table border="0" width="100%" cellpadding="2" cellspacing="2">
                <tr>
                  <td class="smallText"><b><?php echo TEXT_CUSTOMER; ?></b><br><?php echo $mail_sent_to; ?></td>
                </tr>
                <tr>
                  <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                </tr>
                <tr>
                  <td class="smallText"><b><?php echo TEXT_FROM; ?></b><br><?php echo tep_db_prepare_input($_POST['from']); ?></td>
                </tr>
                <tr>
                  <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                </tr>
                <tr>
                  <td class="smallText"><b><?php echo TEXT_SUBJECT; ?></b><br><?php echo tep_db_encoder(tep_db_prepare_input($_POST['subject'])); ?></td>
                </tr>
                <tr>
                  <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                </tr>
                <tr>
                  <td class="smallText"><b><?php echo TEXT_AMOUNT; ?></b><br><?php
                  //==============  GSR START ==============
                  if($coupon_type == 'P') {
                    echo tep_db_prepare_input($_POST['amount']); 
                  } else{
                    echo $currencies->format(nl2br(tep_db_prepare_input($_POST['amount'])),true,DEFAULT_CURRENCY,$currency_value); 
                  }
                  //echo $currencies->format(nl2br(tep_db_prepare_input($_POST['amount'])),true,DEFAULT_CURRENCY,$currency_value); 
                  //==============  GSR END ==============
                  ?>
                </td>
                </tr>
                <tr>
                  <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                </tr>
                <tr>
                  <td class="smallText"><b>
                    <?php echo tep_db_encoder(tep_db_prepare_input($_POST['message'])); ?>
                  </td>
                </tr>
                <tr>
                  <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                </tr>
                <tr>
                  <td>
                    <?php
                    /* Re-Post all POST'ed variables */
                    reset($_POST);
                    while (list($key, $value) = each($_POST)) {
                      if (!is_array($_POST[$key])) {
                        echo tep_draw_hidden_field($key, tep_db_encoder(tep_db_prepare_input($value))) . "\n";
                      }
                    }
                    ?>
                    <table border="0" width="100%" cellpadding="2" cellspacing="2">
                      <tr>
                        <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_GV_MAIL) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a> ' . tep_image_submit('button_send_mail.gif', IMAGE_SEND_EMAIL); ?></td>
                      </tr>
                      <tr>
                        <td class="smallText">
                          <?php 
                          if (EMAIL_USE_HTML == 'false') {
                            echo tep_image_submit('button_back.gif', IMAGE_BACK, 'name="back"');
                          } 
                          if (EMAIL_USE_HTML == 'false') {
                            echo(TEXT_EMAIL_BUTTON_HTML);
                          } else {
                            echo(TEXT_EMAIL_BUTTON_TEXT);
                          } 
                          ?>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table></td>
              </form>
            </tr>
            <?php
          } else {
            if (EMAIL_USE_HTML == 'true'){
              echo tep_insert_html_editor('message','simple','400');
            }
            echo tep_draw_separator('pixel_trans.gif', '100%', '15');
            ?>
            <div class="tab-pane" id="tabPane1">
              <script type="text/javascript">tp1 = new WebFXTabPane( document.getElementById( "tabPane1" ) );</script>
              <?php echo tep_draw_form('mail', FILENAME_GV_MAIL, 'action=preview', 'POST','class="form-horizontal"'); ?>
              <table width="100%" border="0" cellspacing="0" cellpadding="0" summary="tab table">
                <tr>
                  <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2" class="table dataTable no-footer dtr-inline">
                    <?php
                    $customers = array();
                    $customers[] = array('id' => '', 'text' => TEXT_SELECT_CUSTOMER);
                    $customers[] = array('id' => '***', 'text' => TEXT_ALL_CUSTOMERS);
                    $customers[] = array('id' => '**D', 'text' => TEXT_NEWSLETTER_CUSTOMERS);
                    $mail_query = tep_db_query("select customers_email_address, customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " order by customers_lastname");
                    while($customers_values = tep_db_fetch_array($mail_query)) {
                      $customers[] = array('id' => $customers_values['customers_email_address'],
                                           'text' => $customers_values['customers_lastname'] . ', ' . $customers_values['customers_firstname'] . ' (' . $customers_values['customers_email_address'] . ')');
                    }
                    ?>
                    <tr>
                      <td><?php echo TEXT_CUSTOMER; ?></td>
                      <td><?php echo tep_draw_pull_down_menu('customers_email_address', $customers, (isset($_GET['customer']) ? $_GET['customer'] : ''),'class="form-control"');?></td>
                    </tr>

                    <tr>
                      <td><?php echo TEXT_TO; ?></td>
                      <td><?php echo tep_draw_input_field('email_to','','class="form-control"'); ?><?php echo '&nbsp;&nbsp;' . TEXT_SINGLE_EMAIL; ?></td>
                    </tr>

                    <tr>
                      <td class="main"><?php echo TEXT_FROM; ?></td>
                      <td><?php echo tep_draw_input_field('from', STORE_OWNER_EMAIL_ADDRESS,'class="form-control"'); ?></td>
                    </tr>

                    <tr>
                      <td class="main"><?php echo TEXT_SUBJECT; ?></td>
                      <td><?php echo tep_draw_input_field('subject','','class="form-control"'); ?></td>
                    </tr>

                    <tr>
                      <td valign="top" class="main"><?php echo TEXT_AMOUNT; ?></td>
                      <td><?php echo tep_draw_input_field('amount','','class="form-control"'); ?></td>
                    </tr>

                    <tr>
                      <td valign="top" class="main"><?php echo TEXT_MESSAGE; ?></td> 
                      <td class="main"><?php echo tep_draw_textarea_field('message', 'soft', '60', '3', $message, 'class="form-control"'); ?></td>              
                    </tr>

                  </table></td>
                  <?php 
                  if (EMAIL_USE_HTML == 'true') {
                    ?>
                    <script type="text/javascript">
                      //<![CDATA[
                      setupAllTabs();
                      //]]>
                    </script>
                    <?php
                  }
                  ?>
                </tr>
                <tr>
                  <td align="center">
                    <?php 
                    if (EMAIL_USE_HTML == 'false') { 
                      echo tep_image_submit('button_send_mail.gif', IMAGE_SEND_EMAIL, 'onClick="validate();return returnVal;"');
                    } else {
                      echo tep_image_submit('button_send_mail.gif', IMAGE_SEND_EMAIL); 
                    }
                    ?>
                  </td>
                </tr>
              </table>
              </form>
            </div>
            <?php
          }
          ?>
          <!-- body_text_eof //-->
        </table></td>
      </tr>
    </table></div></div> </div>
</div>
<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>