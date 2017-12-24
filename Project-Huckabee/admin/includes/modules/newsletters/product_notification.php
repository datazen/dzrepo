<?php
/*
  $Id: product_notification.php,v 1.1.1.1 2004/03/04 23:40:24 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  class product_notification {
    var $show_choose_audience, $title, $content;

    function product_notification($title, $content) {
      $this->show_choose_audience = true;
      $this->title = $title;
      $this->content = $content;
    }

    function choose_audience() {
      global $languages_id;

      $products_array = array();
      $products_query = tep_db_query("select pd.products_id, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.language_id = '" . $languages_id . "' and pd.products_id = p.products_id and p.products_status = '1' order by pd.products_name");
      while ($products = tep_db_fetch_array($products_query)) {
        $products_array[] = array('id' => $products['products_id'],
                                  'text' => $products['products_name']);
      }

$choose_audience_string = '<script language="javascript"><!--
function mover(move) {
  if (move == \'remove\') {
    for (x=0; x<(document.notifications.products.length); x++) {
      if (document.notifications.products.options[x].selected) {
        with(document.notifications.elements[\'chosen[]\']) {
          options[options.length] = new Option(document.notifications.products.options[x].text,document.notifications.products.options[x].value);
        }
        document.notifications.products.options[x] = null;
        x = -1;
      }
    }
  }
  if (move == \'add\') {
    for (x=0; x<(document.notifications.elements[\'chosen[]\'].length); x++) {
      if (document.notifications.elements[\'chosen[]\'].options[x].selected) {
        with(document.notifications.products) {
          options[options.length] = new Option(document.notifications.elements[\'chosen[]\'].options[x].text,document.notifications.elements[\'chosen[]\'].options[x].value);
        }
        document.notifications.elements[\'chosen[]\'].options[x] = null;
        x = -1;
      }
    }
  }
  return true;
}

function selectAll(FormName, SelectBox) {
  temp = "document." + FormName + ".elements[\'" + SelectBox + "\']";
  Source = eval(temp);

  for (x=0; x<(Source.length); x++) {
    Source.options[x].selected = "true";
  }

  if (x<1) {
    alert(\'' . JS_PLEASE_SELECT_PRODUCTS . '\');
    return false;
  } else {
    return true;
  }
}
//--></script>';

      $global_button = '<a href="' . tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $_GET['nID'] . '&action=confirm&global=true') . '" class="cssButton" style="display: block; width: 8em;">' . BUTTON_GLOBAL . '</a>';

      $cancel_button = '<a href="' . tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $_GET['nID']) . '">' . tep_image_button('button_cancel.gif', BUTTON_CANCEL) . '</a>';  
      
      /*$choose_audience_string .= '<form name="notifications" action="' . tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $_GET['nID'] . '&action=confirm') . '" method="post" onSubmit="return selectAll(\'notifications\', \'chosen[]\')"><table border="0" cellspacing="0" cellpadding="0" align="left">' . "\n" .
                                 '  <tr>' . "\n" .
                                 '    <td align="left" class="main"><b>' . TEXT_PRODUCTS . '</b><br>' . tep_draw_pull_down_menu('products', $products_array, '', 'size="20" style="width: 20em;" multiple') . '</td>' . "\n" .
                                 '    <td align="center" class="main" valign="center"><div class="button-container" style="margin-left: 8px; margin-right: 4px;">' . $global_button . '<a href="javascript:void(0)" onclick="mover(\'remove\')" class="cssButton" style="width: 8em; display: block;">' . BUTTON_SELECT . '</a><a href="javascript:void(0)" onclick="mover(\'add\')" class="cssButton" style="width: 8em; display: block;">' . BUTTON_UNSELECT . '</a></div></td>' . "\n" .
                                 '    <td align="left" class="main"><b>' . TEXT_SELECTED_PRODUCTS . '</b><br>' . tep_draw_pull_down_menu('chosen[]', array(), '', 'size="20" style="width: 20em;" multiple') . '</td>' . "\n" .
                                 '  </tr>' . "\n" .
                                 '<tr><td align="right" colspan="3" style="margin-top: 1em;"><div class="button-container">' . $cancel_button . tep_image_submit('button_submit.gif', BUTTON_SUBMIT) . '</div></td></tr>' .
                                 '</table>*/

     $choose_audience_string .= '<form name="notifications" action="' . tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $_GET['nID'] . '&action=confirm') . '" method="post" onSubmit="return selectAll(\'notifications\', \'chosen[]\')"><table border="0" cellspacing="0" cellpadding="0" align="center" width="75%">' . "\n" .
                                 '  <tr>' . "\n" .
                                 '    <td align="center" class="main" width="25%"><b>' . TEXT_PRODUCTS . '</b><br>' . tep_draw_pull_down_menu('products', $products_array, '', 'size="20" style="width: 15em;" multiple') . '</td>' . "\n" .
                                 '    <td align="center" class="main" valign="center" width="25%"><div class="button-container" style="margin-left: 8px; margin-right: 6px;">' . $global_button . '<br><br><a href="javascript:void(0)" onclick="mover(\'remove\')" class="cssButton" style="width: 8em; display: block;">' . BUTTON_SELECT . '</a><br><a href="javascript:void(0)" onclick="mover(\'add\')" class="cssButton" style="width: 8em; display: block;">' . BUTTON_UNSELECT . '</a><br><br>' .$cancel_button .'<br><br>'. tep_image_submit('button_submit.gif', BUTTON_SUBMIT) .'</div></td>' . "\n" .
                                 '    <td align="center" class="main" width="25%"><b>' . TEXT_SELECTED_PRODUCTS . '</b><br>' . tep_draw_pull_down_menu('chosen[]', array(), '', 'size="20" style="width: 15em;" multiple') . '</td>' . "\n" .
                                 '  </tr>' . "\n" .
                                 
                                 '</table>
                                 
                                 </form>';

      return $choose_audience_string;
    }

    function confirm() {
      $audience = array();

      if (isset($_GET['global']) && ($_GET['global'] == 'true')) {
        $products_query = tep_db_query("select distinct customers_id from " . TABLE_PRODUCTS_NOTIFICATIONS);
        while ($products = tep_db_fetch_array($products_query)) {
          $audience[$products['customers_id']] = '1';
        }

        $customers_query = tep_db_query("select customers_info_id from " . TABLE_CUSTOMERS_INFO . " where global_product_notifications = '1'");
        while ($customers = tep_db_fetch_array($customers_query)) {
          $audience[$customers['customers_info_id']] = '1';
        }
      } else {
        $chosen = $_POST['chosen'];

        $ids = implode(',', $chosen);

        $products_query = tep_db_query("select distinct customers_id from " . TABLE_PRODUCTS_NOTIFICATIONS . " where products_id in (" . $ids . ")");
        while ($products = tep_db_fetch_array($products_query)) {
          $audience[$products['customers_id']] = '1';
        }

        $customers_query = tep_db_query("select customers_info_id from " . TABLE_CUSTOMERS_INFO . " where global_product_notifications = '1'");
        while ($customers = tep_db_fetch_array($customers_query)) {
          $audience[$customers['customers_info_id']] = '1';
        }
      }

      $confirm_string = '<table border="0" cellspacing="0" cellpadding="2">' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td class="main"><font color="#ff0000"><b>' . sprintf(TEXT_COUNT_CUSTOMERS, sizeof($audience)) . '</b></font></td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td>' . tep_draw_separator('pixel_trans.gif', '1', '10') . '</td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td class="main"><b>' . $this->title . '</b></td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td>' . tep_draw_separator('pixel_trans.gif', '1', '10') . '</td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td class="main"><tt>' . nl2br($this->content) . '</tt></td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td>' . tep_draw_separator('pixel_trans.gif', '1', '10') . '</td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . tep_draw_form('confirm', FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $_GET['nID'] . '&action=confirm_send') . "\n" .
                        '    <td align="right">';
      if (sizeof($audience) > 0) {
        if (isset($_GET['global']) && ($_GET['global'] == 'true')) {
          $confirm_string .= tep_draw_hidden_field('global', 'true');
        } else {
          for ($i = 0, $n = sizeof($chosen); $i < $n; $i++) {
            $confirm_string .= tep_draw_hidden_field('chosen[]', $chosen[$i]);
          }
        }
        $confirm_string .= tep_image_submit('button_send.gif', IMAGE_SEND) . ' ';
      }
      $confirm_string .= '<a href="' . tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $_GET['nID'] . '&action=send') . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a> <a href="' . tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $_GET['nID']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a></td>' . "\n" .
                         '  </tr>' . "\n" .
                         '</table>';

      return $confirm_string;
    }

    function send($newsletter_id) {
      $audience = array();

      if (isset($_POST['global']) && ($_POST['global'] == 'true')) {
        $products_query = tep_db_query("select distinct pn.customers_id, c.customers_firstname, c.customers_lastname, c.customers_email_address,pn.products_id from " . TABLE_CUSTOMERS . " c, " . TABLE_PRODUCTS_NOTIFICATIONS . " pn where c.customers_id = pn.customers_id");
        while ($products = tep_db_fetch_array($products_query)) {
      $arr_prod[] = $products['products_id'];
          $audience[$products['customers_id']] = array('firstname' => $products['customers_firstname'],
                                                       'lastname' => $products['customers_lastname'],
                                                       'email_address' => $products['customers_email_address']);
        }

        $customers_query = tep_db_query("select c.customers_id, c.customers_firstname, c.customers_lastname, c.customers_email_address from " . TABLE_CUSTOMERS . " c, " . TABLE_CUSTOMERS_INFO . " ci where c.customers_id = ci.customers_info_id and ci.global_product_notifications = '1'");
        while ($customers = tep_db_fetch_array($customers_query)) {
          $audience[$customers['customers_id']] = array('firstname' => $customers['customers_firstname'],
                                                        'lastname' => $customers['customers_lastname'],
                                                        'email_address' => $customers['customers_email_address']);
        }
      } else {
        $chosen = $_POST['chosen'];

        $ids = implode(',', $chosen);

        $products_query = tep_db_query("select distinct pn.customers_id, c.customers_firstname, c.customers_lastname, c.customers_email_address,pn.products_id from " . TABLE_CUSTOMERS . " c, " . TABLE_PRODUCTS_NOTIFICATIONS . " pn where c.customers_id = pn.customers_id and pn.products_id in (" . $ids . ")");
        while ($products = tep_db_fetch_array($products_query)) {
      $arr_prod[] = $products['products_id'];
          $audience[$products['customers_id']] = array('firstname' => $products['customers_firstname'],
                                                       'lastname' => $products['customers_lastname'],
                                                       'email_address' => $products['customers_email_address']);
        }

        $customers_query = tep_db_query("select c.customers_id, c.customers_firstname, c.customers_lastname, c.customers_email_address from " . TABLE_CUSTOMERS . " c, " . TABLE_CUSTOMERS_INFO . " ci where c.customers_id = ci.customers_info_id and ci.global_product_notifications = '1'");
        while ($customers = tep_db_fetch_array($customers_query)) {
          $audience[$customers['customers_id']] = array('firstname' => $customers['customers_firstname'],
                                                        'lastname' => $customers['customers_lastname'],
                                                        'email_address' => $customers['customers_email_address']);
        }
      }

$str_prod_link = '<br>';
foreach($arr_prod as $x_val) {
  $str_prod_link .= "<a href = '".HTTP_CATALOG_SERVER.DIR_WS_HTTP_CATALOG."product_info.php?products_id=".$x_val."'>".HTTP_CATALOG_SERVER.DIR_WS_HTTP_CATALOG."product_info.php?products_id=".$x_val."</a><br>";
}

$this->content .= "<p>".$str_prod_link."<p>";

      $mimemessage = new email(array('X-Mailer: osCommerce bulk mailer'));
      if (HTML_WYSIWYG_DISABLE_NEWSLETTER == 'Disable') {
      $mimemessage->add_text($this->content);
      } else {
      $mimemessage->add_html($this->content);
      }
      $mimemessage->build_message();
      reset($audience);
      while (list($key, $value) = each ($audience)) {
        $mimemessage->send($value['firstname'] . ' ' . $value['lastname'], $value['email_address'], '', STORE_OWNER_EMAIL_ADDRESS, $this->title);
      }
      $newsletter_id = tep_db_prepare_input($newsletter_id);
      tep_db_query("update " . TABLE_NEWSLETTERS . " set date_sent = now(), status = '1' where newsletters_id = '" . tep_db_input($newsletter_id) . "'");
    }
  }
?>
