<?php
  /*
  CRE Loaded , Open Source E-Commerce Solutions
  http://www.creloaded.com
  Portions  Copyright (c) 2003 osCommerce

  Chain Reaction Works, Inc
  Copyright &copy; 2005 - 2007 Chain Reaction Works, Inc.

  Released under the GNU General Public License
  */

  require('includes/languages/' . $language . '/install_11.php');

  $dir_fs_document_root = $_POST['DIR_FS_DOCUMENT_ROOT'];
  if ((substr($dir_fs_document_root, -1) != '/') && (substr($dir_fs_document_root, -1) != '/')) {
    $where = strrpos($dir_fs_document_root, '\\');
    if (is_string($where) && !$where) {
      $dir_fs_document_root .= '/';
    } else {
      $dir_fs_document_root .= '\\';
    }
  }

  $admin_user = trim($_POST['adminuser']);
  $admin_pass = trim($_POST['adminpass']);
  $admin_first = trim($_POST['adminfirst']);
  $admin_last = trim($_POST['adminlast']);

  unset($error_msg) ;
  unset($_POST['$error_msg']) ;

  if ( (empty($_POST['adminuser'])) || (empty($_POST['adminpass'])) ) {
    $error_msg = '3';
  } elseif (osc_validate_email($admin_user) == 'false') {
    $error_msg = '1';
  } elseif ($admin_user == 'admin@localhost.com') {
    $error_msg = '2';
  } elseif ((!preg_match('/[0-9]/', $admin_pass) || !preg_match('/[A-Z]/', $admin_pass) || !preg_match('/[a-z]/', $admin_pass)) || (strlen($admin_pass) < 8)) {
    $error_msg = '8';
  }

  $db = array();
  $db['DB_SERVER'] = trim(stripslashes($_POST['DB_SERVER']));
  $db['DB_SERVER_USERNAME'] = trim(stripslashes($_POST['DB_SERVER_USERNAME']));
  $db['DB_SERVER_PASSWORD'] = trim(stripslashes($_POST['DB_SERVER_PASSWORD']));
  $db['DB_DATABASE'] = trim(stripslashes($_POST['DB_DATABASE']));

  // test connection to db
  $db_error = false;

  osc_db_connect1($db['DB_SERVER'], $db['DB_SERVER_USERNAME'], $db['DB_SERVER_PASSWORD'], $db['DB_DATABASE']);

  if ($db_error = false) {
    osc_db_test_connection($db['DB_DATABASE']);

    //----------------check for db conntection -------------------
    if ($db_error != false) {
      $error_msg = '6';
    }
  }
  //-------------------------------add admn user to db ----------------------
  if (empty($error_msg)) {
    $db_error = false;
    $admin_check = osc_db_query ("select admin_email_address from admin where admin_email_address = '" . $admin_user . "'");

    if (osc_db_num_rows($admin_check) == 0) {

      $sql_data_array = array('admin_groups_id' => '1',
        'admin_firstname' => $admin_first,
        'admin_lastname' => $admin_last,
        'admin_email_address' => $admin_user,
        'admin_password' => osc_encrypt_password($admin_pass),
        'admin_created' => 'now()',
        'admin_modified' => 'now()');

      osc_db_perform('admin', $sql_data_array, 'insert' );
    } else {
      $error_msg = '7';
    }

    // update session directory configuration value
    $session_dir = isset($_POST['DIR_FS_DOCUMENT_ROOT']) ? $_POST['DIR_FS_DOCUMENT_ROOT'] . 'tmp' : '/tmp';
    $log_destination = $session_dir . '/page_parse_time.log';
    osc_db_query("UPDATE `configuration` SET `configuration_value` = '" . $session_dir . "' WHERE `configuration_key` = 'SESSION_WRITE_DIRECTORY'");
    osc_db_query("UPDATE `configuration` SET `configuration_value` = '" . $log_destination . "' WHERE `configuration_key` = 'STORE_PAGE_PARSE_TIME_LOG'");

    // update MODULE_PAYMENT_PAYPAL_ID, MODULE_PAYMENT_PAYPAL_BUSINESS_ID values from configuration table, if paypal module is installed/exists
    $paypal_module_check = osc_db_query ("select * from `configuration` where `configuration_key` = 'MODULE_PAYMENT_PAYPAL_STATUS'");
    if (osc_db_num_rows($paypal_module_check) > 0) {
      osc_db_query("UPDATE `configuration` SET `configuration_value` = '" . $admin_user . "' WHERE `configuration_key` = 'MODULE_PAYMENT_PAYPAL_BUSINESS_ID'");
      osc_db_query("UPDATE `configuration` SET `configuration_value` = '" . $admin_user . "' WHERE `configuration_key` = 'MODULE_PAYMENT_PAYPAL_ID'");
    }

    //-------------------if error set errorto error 4-------------------
    if ($db_error != false){
      $error_msg = '4';
    }
  }

  //----------------- check for configure write --------------------
  if ( ( (file_exists($dir_fs_document_root . 'includes/configure.php')) && (!is_writeable($dir_fs_document_root . 'includes/configure.php')) ) || ( (file_exists($dir_fs_document_root . '/admin/includes/configure.php')) && (!is_writeable($dir_fs_document_root . '/admin/includes/configure.php')) ) ) {
    $error_msg = '5' ;
  }

  if ( ($db_error == false) && empty($error_msg) ){

    if (substr($_POST['HTTP_WWW_ADDRESS'], -1) != '/') $_POST['HTTP_WWW_ADDRESS'] .= '/';
    if (substr($_POST['HTTPS_WWW_ADDRESS'], -1) != '/') $_POST['HTTPS_WWW_ADDRESS'] .= '/';

    $http_url = parse_url($_POST['HTTP_WWW_ADDRESS']);
    $http_server = $http_url['scheme'] . '://' . $http_url['host'];
    $http_catalog = $http_url['path'];
    if (isset($http_url['port']) && !empty($http_url['port'])) {
      $http_server .= ':' . $http_url['port'];
    }

    if (substr($http_catalog, -1) != '/') {
      $http_catalog .= '/';
    }

    $https_server = '';
    $https_catalog = '';
    if (isset($_POST['ENABLE_SSL']) && $_POST['ENABLE_SSL'] == 'true') {
      $https_url = parse_url($_POST['HTTPS_WWW_ADDRESS']);
      $https_server = $https_url['scheme'] . '://' . $https_url['host'];
      $https_catalog = $https_url['path'];

      if (isset($https_url['port']) && !empty($https_url['port'])) {
        $https_server .= ':' . $https_url['port'];
      }

      if (substr($https_catalog, -1) != '/') {
        $https_catalog .= '/';
      }
    } else {
      $https_url = parse_url($_POST['HTTP_WWW_ADDRESS']);
      $https_server = $http_url['scheme'] . '://' . $http_url['host'];
      $https_catalog = $http_url['path'];

      if (isset($http_url['port']) && !empty($http_url['port'])) {
        $https_server .= ':' . $http_url['port'];
      }

      if (substr($https_catalog, -1) != '/') {
        $https_catalog .= '/';
      }
    }

    $enable_ssl = (isset($_POST['ENABLE_SSL']) && ($_POST['ENABLE_SSL'] == 'true') ? 'true' : 'false');
    $http_cookie_domain = $_POST['HTTP_COOKIE_DOMAIN'];
    $https_cookie_domain = (isset($_POST['HTTPS_COOKIE_DOMAIN']) ? $_POST['HTTPS_COOKIE_DOMAIN'] : $_POST['HTTP_COOKIE_DOMAIN']);
    $http_cookie_path = $_POST['HTTP_COOKIE_PATH'];
    $https_cookie_path = (isset($_POST['HTTPS_COOKIE_PATH']) ? $_POST['HTTPS_COOKIE_PATH'] : $_POST['HTTP_COOKIE_PATH']);

    $file_contents = '<?php' . "\n" .
    '/*' . "\n" .
    '  osCommerce, Open Source E-Commerce Solutions' . "\n" .
    '  http://www.oscommerce.com' . "\n" .
    '' . "\n" .
    '  Copyright (c) 2003 osCommerce' . "\n" .
    '' . "\n" .
    '  Released under the GNU General Public License' . "\n" .
    '*/' . "\n" .
    '' . "\n" .
    '// Define the webserver and path parameters' . "\n" .
    '// * DIR_FS_* = Filesystem directories (local/physical)' . "\n" .
    '// * DIR_WS_* = Webserver directories (virtual/URL)' . "\n" .
    '  define(\'HTTP_SERVER\', \'' . $http_server . '\'); // eg, http://localhost - should not be empty for productive servers' . "\n" .
    '  define(\'HTTPS_SERVER\', \'' . $https_server . '\'); // eg, https://localhost - should not be empty for productive servers' . "\n" .
    '  define(\'ENABLE_SSL\', ' . $enable_ssl . '); // secure webserver for checkout procedure?' . "\n" .
    '  define(\'HTTP_COOKIE_DOMAIN\', \'' . $http_cookie_domain . '\');' . "\n" .
    '  define(\'HTTPS_COOKIE_DOMAIN\', \'' . $https_cookie_domain . '\');' . "\n" .
    '  define(\'HTTP_COOKIE_PATH\', \'' . $http_cookie_path . '\');' . "\n" .
    '  define(\'HTTPS_COOKIE_PATH\', \'' . $https_cookie_path . '\');' . "\n" .
    '  define(\'DIR_WS_HTTP_CATALOG\', \'' . $http_catalog . '\');' . "\n" .
    '  define(\'DIR_WS_HTTPS_CATALOG\', \'' . $https_catalog . '\');' . "\n" .
    '  define(\'DIR_WS_IMAGES\', \'images/\');' . "\n" .
    '  define(\'DIR_WS_ICONS\', DIR_WS_IMAGES . \'icons/\');' . "\n" .
    '  define(\'DIR_WS_INCLUDES\', \'includes/\');' . "\n" .
    '  define(\'DIR_WS_BOXES\', DIR_WS_INCLUDES . \'boxes/\');' . "\n" .
    '  define(\'DIR_WS_FUNCTIONS\', DIR_WS_INCLUDES . \'functions/\');' . "\n" .
    '  define(\'DIR_WS_CLASSES\', DIR_WS_INCLUDES . \'classes/\');' . "\n" .
    '  define(\'DIR_WS_MODULES\', DIR_WS_INCLUDES . \'modules/\');' . "\n" .
    '  define(\'DIR_WS_LANGUAGES\', DIR_WS_INCLUDES . \'languages/\');' . "\n" .
    '' . "\n" .
    '//Added for BTS1.0' . "\n" .
    '  define(\'DIR_WS_TEMPLATES\', \'templates/\');' . "\n" .
    '  define(\'DIR_WS_CONTENT\', DIR_WS_TEMPLATES . \'content/\');' . "\n" .
    '  define(\'DIR_WS_JAVASCRIPT\', DIR_WS_INCLUDES . \'javascript/\');' . "\n" .
    '//End BTS1.0' . "\n" .
    '  define(\'DIR_WS_DOWNLOAD_PUBLIC\', \'pub/\');' . "\n" .
    '  define(\'DIR_FS_CATALOG\', \'' . $dir_fs_document_root . '\');' . "\n" .
    '  define(\'DIR_FS_DOWNLOAD\', DIR_FS_CATALOG . \'download/\');' . "\n" .
    '  define(\'DIR_FS_DOWNLOAD_PUBLIC\', DIR_FS_CATALOG . \'pub/\');' . "\n" .
    '' . "\n" .
    '// define our database connection' . "\n" .
    '  define(\'DB_SERVER\', \'' . $_POST['DB_SERVER'] . '\'); // eg, localhost - should not be empty for productive servers' . "\n" .
    '  define(\'DB_SERVER_USERNAME\', \'' . $_POST['DB_SERVER_USERNAME'] . '\');' . "\n" .
    '  define(\'DB_SERVER_PASSWORD\', \'' . $_POST['DB_SERVER_PASSWORD']. '\');' . "\n" .
    '  define(\'DB_DATABASE\', \'' . $_POST['DB_DATABASE']. '\');' . "\n" .
    '  define(\'USE_PCONNECT\', \'' . (($_POST['USE_PCONNECT'] == 'true') ? 'true' : 'false') . '\'); // use persistent connections?' . "\n" .
    '  define(\'STORE_SESSIONS\', \'' . (($_POST['STORE_SESSIONS'] == 'files') ? '' : 'mysql') . '\'); // leave empty \'\' for default handler or set to \'mysql\'' . "\n" .
    '?>';

    $fp = fopen($dir_fs_document_root . 'includes/configure.php', 'w');
    fputs($fp, $file_contents);
    fclose($fp);

    if ($enable_ssl == 'true') {
      $my_http_server = $https_server;
    } else {
      $my_http_server = $http_server;
    }

    $file_contents = '<?php' . "\n" .
    '/*' . "\n" .
    '  osCommerce, Open Source E-Commerce Solutions' . "\n" .
    '  http://www.oscommerce.com' . "\n" .
    '' . "\n" .
    '  Copyright (c) 2003 osCommerce' . "\n" .
    '' . "\n" .
    '  Released under the GNU General Public License' . "\n" .
    '*/' . "\n" .
    '' . "\n" .
    '// Define the webserver and path parameters' . "\n" .
    '// * DIR_FS_* = Filesystem directories (local/physical)' . "\n" .
    '// * DIR_WS_* = Webserver directories (virtual/URL)' . "\n" .
    '  define(\'HTTP_SERVER\', \'' . $my_http_server . '\'); // eg, http://localhost - should not be empty for productive servers' . "\n" .
    '  define(\'HTTP_CATALOG_SERVER\', \'' . $http_server . '\');' . "\n" .
    '  define(\'HTTPS_CATALOG_SERVER\', \'' . $https_server . '\');' . "\n" .
    '  define(\'HTTPS_SERVER\', \'' . $https_server . '\'); // eg, https://localhost - should not be empty for productive servers' . "\n" .
    '  define(\'HTTPS_ADMIN_SERVER\', \'' . $https_server . '\');' . "\n" .
    '  define(\'HTTP_COOKIE_DOMAIN\', \'' . $http_cookie_domain . '\');' . "\n" .
    '  define(\'HTTPS_COOKIE_DOMAIN\', \'' . $https_cookie_domain . '\');' . "\n" .
    '  define(\'HTTP_COOKIE_PATH\', \'' . $http_cookie_path . '\');' . "\n" .
    '  define(\'HTTPS_COOKIE_PATH\', \'' . $https_cookie_path . '\');' . "\n" .
    '  define(\'ENABLE_SSL\',  \'' . $enable_ssl . '\'); // secure webserver for checkout procedure?' . "\n" .
    '  define(\'ENABLE_SSL_CATALOG\', \'' . $enable_ssl . '\'); // secure webserver for catalog module' . "\n" .
    '  define(\'DIR_WS_HTTP_ADMIN\',  \'' . $http_catalog . 'admin/\');' . "\n" .
    '  define(\'DIR_WS_HTTPS_ADMIN\',  \'' . $https_catalog . 'admin/\');' . "\n" .
    '  define(\'DIR_FS_DOCUMENT_ROOT\', \'' . $dir_fs_document_root . '\'); // where the pages are located on the server' . "\n" .
    '  define(\'DIR_FS_ADMIN\', \'' . $dir_fs_document_root . 'admin/\'); // absolute path required' . "\n" .
    '  define(\'DIR_WS_CATALOG\', \'' . $http_catalog . '\'); // absolute path required' . "\n" .
    '  define(\'DIR_WS_HTTP_CATALOG\', \'' . $http_catalog . '\');' . "\n" .
    '  define(\'DIR_WS_HTTPS_CATALOG\', \'' . $https_catalog . '\');' . "\n" .
    '  define(\'DIR_FS_CATALOG\', \'' . $dir_fs_document_root . '\'); // absolute path required' . "\n" .
    '  define(\'DIR_WS_IMAGES\', \'images/\');' . "\n" .
    '  define(\'DIR_WS_ICONS\', DIR_WS_IMAGES . \'icons/\');' . "\n" .
    '  define(\'DIR_WS_CATALOG_IMAGES\', DIR_WS_CATALOG . \'images/\');' . "\n" .
    '  define(\'DIR_WS_INCLUDES\', \'includes/\');' . "\n" .
    '  define(\'DIR_WS_BOXES\', DIR_WS_INCLUDES . \'boxes/\');' . "\n" .
    '  define(\'DIR_WS_FUNCTIONS\', DIR_WS_INCLUDES . \'functions/\');' . "\n" .
    '  define(\'DIR_WS_CLASSES\', DIR_WS_INCLUDES . \'classes/\');' . "\n" .
    '  define(\'DIR_WS_MODULES\', DIR_WS_INCLUDES . \'modules/\');' . "\n" .
    '  define(\'DIR_WS_LANGUAGES\', DIR_WS_INCLUDES . \'languages/\');' . "\n" .
    '  define(\'DIR_WS_CATALOG_LANGUAGES\', DIR_WS_CATALOG . \'includes/languages/\');' . "\n" .
    '  define(\'DIR_FS_CATALOG_LANGUAGES\', DIR_FS_CATALOG . \'includes/languages/\');' . "\n" .
    '  define(\'DIR_FS_CATALOG_IMAGES\', DIR_FS_CATALOG . \'images/\');' . "\n" .
    '  define(\'DIR_FS_CATALOG_MODULES\', DIR_FS_CATALOG . \'includes/modules/\');' . "\n" .
    '  define(\'DIR_FS_BACKUP\', DIR_FS_ADMIN . \'backups/\');' . "\n" .
    '' . "\n" .

    '// Added for Templating' . "\n" .
    '  define(\'DIR_FS_CATALOG_MAINPAGE_MODULES\', DIR_FS_CATALOG_MODULES . \'mainpage_modules/\');' . "\n" .
    '  define(\'DIR_WS_TEMPLATES\', DIR_WS_CATALOG . \'templates/\');' . "\n" .
    '  define(\'DIR_FS_TEMPLATES\', DIR_FS_CATALOG . \'templates/\');' . "\n" .
    '' . "\n" . 

    '// define our database connection' . "\n" .
    '  define(\'DB_SERVER\', \'' . $_POST['DB_SERVER'] . '\'); // eg, localhost - should not be empty for productive servers' . "\n" .
    '  define(\'DB_SERVER_USERNAME\', \'' . $_POST['DB_SERVER_USERNAME'] . '\');' . "\n" .
    '  define(\'DB_SERVER_PASSWORD\', \'' . $_POST['DB_SERVER_PASSWORD']. '\');' . "\n" .
    '  define(\'DB_DATABASE\', \'' . $_POST['DB_DATABASE']. '\');' . "\n" .
    '  define(\'USE_PCONNECT\', \'' . (($_POST['USE_PCONNECT'] == 'true') ? 'true' : 'false') . '\'); // use persisstent connections?' . "\n" .
    '  define(\'STORE_SESSIONS\', \'' . (($_POST['STORE_SESSIONS'] == 'files') ? '' : 'mysql') . '\'); // leave empty \'\' for default handler or set to \'mysql\'' . "\n" .
    '?>';

    $fp = fopen($dir_fs_document_root . 'admin/includes/configure.php', 'w');
    fputs($fp, $file_contents);
    fclose($fp);

    $http_url = parse_url($_POST['HTTP_WWW_ADDRESS']);
    $http_server = $http_url['scheme'] . '://' . $http_url['host'];
    $http_catalog = $http_url['path'];
    if (isset($http_url['port']) && !empty($http_url['port'])) {
      $http_server .= ':' . $http_url['port'];
    }

    require('includes/languages/' . $language . '/install_9.php');

    if ($_SERVER["HTTP_HOST"] != 'localhost'){ 
      $message = 'Hello ' . $admin_first . ' ' . $admin_last . ', ' . "\n\n" .
      'Your login to ' . $http_server . $http_catalog . 'admin/index.php' . "\n\n" .
      'Login: ' . $admin_user . "\n" .
      'Password: ' . $admin_pass . "\n\n";

      $headers = 'From: ' . $admin_user . "\r\n" .
      'Reply-To: ' . $admin_user . "\r\n" .
      'X-Mailer: PHP/' . phpversion();
      @mail($admin_user, 'Your store login', $message, $headers);
    }
    function curPageURL() {
      $pageURL = 'http';
      if ($_SERVER["HTTPS"] == "on") {
        $pageURL .= "s";
      }
      $pageURL .= "://";
      if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
      } else {
        $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
      }
      return $pageURL;
    }
  ?>

  <table align="center">
    <tr>
      <td align="center">
        <h1>You're Almost Done!</h1>
      </td>
    </tr>
    <tr>
      <td>
        <p>You're Loaded Commerce experience is nearly complete. We have worked with major US and International payment partners to provide merchant services processing for Loaded Commerce merchants at great rates.</p>
        <p>Just complete this simple form and our payments team will contact you and start your application over the phone.</p>
        <p>Rates start at just $15.95 a month with no additional gateway fees for a full service merchant service account processing VISA, MasterCard, Discover and American Express.</p>
        <p style="width:315px; margin:0 auto 0 auto; padding:20px;"><img src="http://www.loadedcommerce.com/images/LPlogoWideGreen.png" border="0" /></p>
      </td>
    </tr>
    <tr>
      <td>
        <form name="install_12" action="install.php" method="post">
          <input type="hidden" name="step" value="12">
        </form>

        <form action="http://www.loadedcommerce.com/payleapProcess.php" id="fSignup" method="post" name="WebToLeadForm">
          <table border="0" cellpadding="5" cellspacing="0" style="width:400px; margin:0 auto 0 auto;" align="center">
            <tbody><tr>
                <td width="100"><label class="Labels" for="first_name">First Name:</label></td>
                <td><input id="first_name" maxlength="40" name="first_name" tabindex="1" type="text"></td>
              </tr>
              <tr>
                <td><label class="Labels" for="last_name">Last Name:</label></td>
                <td><input id="last_name" maxlength="40" name="last_name" tabindex="2" type="text"></td>
              </tr>
              <tr>
                <td><label class="Labels" for="email">Email:</label></td>
                <td><input id="email" maxlength="40" name="email" tabindex="3" type="text"></td>
              </tr>
              <tr>
                <td><label class="Labels" for="phone">Phone:</label></td>
                <td><input id="phone" maxlength="40" name="phone" tabindex="4" type="text"></td>
              </tr>
              <tr>
                <td><label class="Labels" for="country">Country:</label></td>
                <td>
                  <select id="country" name="country">
                    <option value="United States">United States</option>
                    <option value="United Kingdom">United Kingdom</option>
                    <option value="Canada">Canada</option>
                    <option value="France">France</option>
                    <option value="Afghanistan">Afghanistan</option>
                    <option value="Albania">Albania</option>
                    <option value="Algeria">Algeria</option>
                    <option value="American Samoa">American Samoa</option>
                    <option value="Andorra">Andorra</option>
                    <option value="Angola">Angola</option>
                    <option value="Anguilla">Anguilla</option>
                    <option value="Antarctica">Antarctica</option>
                    <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                    <option value="Argentina">Argentina</option>
                    <option value="Armenia">Armenia</option>
                    <option value="Aruba">Aruba</option>
                    <option value="Australia">Australia</option>
                    <option value="Austria">Austria</option>
                    <option value="Azerbaijan">Azerbaijan</option>
                    <option value="Bahamas">Bahamas</option>
                    <option value="Bahrain">Bahrain</option>
                    <option value="Bangladesh">Bangladesh</option>
                    <option value="Barbados">Barbados</option>
                    <option value="Belarus">Belarus</option>
                    <option value="Belgium">Belgium</option>
                    <option value="Belize">Belize</option>
                    <option value="Benin">Benin</option>
                    <option value="Bermuda">Bermuda</option>
                    <option value="Bhutan">Bhutan</option>
                    <option value="Bolivia">Bolivia</option>
                    <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                    <option value="Botswana">Botswana</option>
                    <option value="Bouvet Island">Bouvet Island</option>
                    <option value="Brazil">Brazil</option>
                    <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                    <option value="Brunei Darussalam">Brunei Darussalam</option>
                    <option value="Bulgaria">Bulgaria</option>
                    <option value="Burkina Faso">Burkina Faso</option>
                    <option value="Burundi">Burundi</option>
                    <option value="Cambodia">Cambodia</option>
                    <option value="Cameroon">Cameroon</option>
                    <option value="Cape Verde">Cape Verde</option>
                    <option value="Cayman Islands">Cayman Islands</option>
                    <option value="Central African Republic">Central African Republic</option>
                    <option value="Chad">Chad</option>
                    <option value="Chile">Chile</option>
                    <option value="China">China</option>
                    <option value="Christmas Island">Christmas Island</option>
                    <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
                    <option value="Colombia">Colombia</option>
                    <option value="Comoros">Comoros</option>
                    <option value="Congo">Congo</option>
                    <option value="Congo, The Democratic Republic of The">Congo, The Democratic Republic of The</option>
                    <option value="Cook Islands">Cook Islands</option>
                    <option value="Costa Rica">Costa Rica</option>
                    <option value="Cote D'ivoire">Cote D'ivoire</option>
                    <option value="Croatia">Croatia</option>
                    <option value="Cuba">Cuba</option>
                    <option value="Cyprus">Cyprus</option>
                    <option value="Czech Republic">Czech Republic</option>
                    <option value="Denmark">Denmark</option>
                    <option value="Djibouti">Djibouti</option>
                    <option value="Dominica">Dominica</option>
                    <option value="Dominican Republic">Dominican Republic</option>
                    <option value="Ecuador">Ecuador</option>
                    <option value="Egypt">Egypt</option>
                    <option value="El Salvador">El Salvador</option>
                    <option value="Equatorial Guinea">Equatorial Guinea</option>
                    <option value="Eritrea">Eritrea</option>
                    <option value="Estonia">Estonia</option>
                    <option value="Ethiopia">Ethiopia</option>
                    <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
                    <option value="Faroe Islands">Faroe Islands</option>
                    <option value="Fiji">Fiji</option>
                    <option value="Finland">Finland</option>
                    <option value="French Guiana">French Guiana</option>
                    <option value="French Polynesia">French Polynesia</option>
                    <option value="French Southern Territories">French Southern Territories</option>
                    <option value="Gabon">Gabon</option>
                    <option value="Gambia">Gambia</option>
                    <option value="Georgia">Georgia</option>
                    <option value="Germany">Germany</option>
                    <option value="Ghana">Ghana</option>
                    <option value="Gibraltar">Gibraltar</option>
                    <option value="Greece">Greece</option>
                    <option value="Greenland">Greenland</option>
                    <option value="Grenada">Grenada</option>
                    <option value="Guadeloupe">Guadeloupe</option>
                    <option value="Guam">Guam</option>
                    <option value="Guatemala">Guatemala</option>
                    <option value="Guinea">Guinea</option>
                    <option value="Guinea-bissau">Guinea-bissau</option>
                    <option value="Guyana">Guyana</option>
                    <option value="Haiti">Haiti</option>
                    <option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option>
                    <option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
                    <option value="Honduras">Honduras</option>
                    <option value="Hong Kong">Hong Kong</option>
                    <option value="Hungary">Hungary</option>
                    <option value="Iceland">Iceland</option>
                    <option value="India">India</option>
                    <option value="Indonesia">Indonesia</option>
                    <option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option>
                    <option value="Iraq">Iraq</option>
                    <option value="Ireland">Ireland</option>
                    <option value="Israel">Israel</option>
                    <option value="Italy">Italy</option>
                    <option value="Jamaica">Jamaica</option>
                    <option value="Japan">Japan</option>
                    <option value="Jordan">Jordan</option>
                    <option value="Kazakhstan">Kazakhstan</option>
                    <option value="Kenya">Kenya</option>
                    <option value="Kiribati">Kiribati</option>
                    <option value="Korea, Democratic People's Republic of">Korea, Democratic People's Republic of</option>
                    <option value="Korea, Republic of">Korea, Republic of</option>
                    <option value="Kuwait">Kuwait</option>
                    <option value="Kyrgyzstan">Kyrgyzstan</option>
                    <option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option>
                    <option value="Latvia">Latvia</option>
                    <option value="Lebanon">Lebanon</option>
                    <option value="Lesotho">Lesotho</option>
                    <option value="Liberia">Liberia</option>
                    <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
                    <option value="Liechtenstein">Liechtenstein</option>
                    <option value="Lithuania">Lithuania</option>
                    <option value="Luxembourg">Luxembourg</option>
                    <option value="Macao">Macao</option>
                    <option value="Macedonia, The Former Yugoslav Republic of">Macedonia, The Former Yugoslav Republic of</option>
                    <option value="Madagascar">Madagascar</option>
                    <option value="Malawi">Malawi</option>
                    <option value="Malaysia">Malaysia</option>
                    <option value="Maldives">Maldives</option>
                    <option value="Mali">Mali</option>
                    <option value="Malta">Malta</option>
                    <option value="Marshall Islands">Marshall Islands</option>
                    <option value="Martinique">Martinique</option>
                    <option value="Mauritania">Mauritania</option>
                    <option value="Mauritius">Mauritius</option>
                    <option value="Mayotte">Mayotte</option>
                    <option value="Mexico">Mexico</option>
                    <option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
                    <option value="Moldova, Republic of">Moldova, Republic of</option>
                    <option value="Monaco">Monaco</option>
                    <option value="Mongolia">Mongolia</option>
                    <option value="Montserrat">Montserrat</option>
                    <option value="Morocco">Morocco</option>
                    <option value="Mozambique">Mozambique</option>
                    <option value="Myanmar">Myanmar</option>
                    <option value="Namibia">Namibia</option>
                    <option value="Nauru">Nauru</option>
                    <option value="Nepal">Nepal</option>
                    <option value="Netherlands">Netherlands</option>
                    <option value="Netherlands Antilles">Netherlands Antilles</option>
                    <option value="New Caledonia">New Caledonia</option>
                    <option value="New Zealand">New Zealand</option>
                    <option value="Nicaragua">Nicaragua</option>
                    <option value="Niger">Niger</option>
                    <option value="Nigeria">Nigeria</option>
                    <option value="Niue">Niue</option>
                    <option value="Norfolk Island">Norfolk Island</option>
                    <option value="Northern Mariana Islands">Northern Mariana Islands</option>
                    <option value="Norway">Norway</option>
                    <option value="Oman">Oman</option>
                    <option value="Pakistan">Pakistan</option>
                    <option value="Palau">Palau</option>
                    <option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option>
                    <option value="Panama">Panama</option>
                    <option value="Papua New Guinea">Papua New Guinea</option>
                    <option value="Paraguay">Paraguay</option>
                    <option value="Peru">Peru</option>
                    <option value="Philippines">Philippines</option>
                    <option value="Pitcairn">Pitcairn</option>
                    <option value="Poland">Poland</option>
                    <option value="Portugal">Portugal</option>
                    <option value="Puerto Rico">Puerto Rico</option>
                    <option value="Qatar">Qatar</option>
                    <option value="Reunion">Reunion</option>
                    <option value="Romania">Romania</option>
                    <option value="Russian Federation">Russian Federation</option>
                    <option value="Rwanda">Rwanda</option>
                    <option value="Saint Helena">Saint Helena</option>
                    <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                    <option value="Saint Lucia">Saint Lucia</option>
                    <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
                    <option value="Saint Vincent and The Grenadines">Saint Vincent and The Grenadines</option>
                    <option value="Samoa">Samoa</option>
                    <option value="San Marino">San Marino</option>
                    <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                    <option value="Saudi Arabia">Saudi Arabia</option>
                    <option value="Senegal">Senegal</option>
                    <option value="Serbia and Montenegro">Serbia and Montenegro</option>
                    <option value="Seychelles">Seychelles</option>
                    <option value="Sierra Leone">Sierra Leone</option>
                    <option value="Singapore">Singapore</option>
                    <option value="Slovakia">Slovakia</option>
                    <option value="Slovenia">Slovenia</option>
                    <option value="Solomon Islands">Solomon Islands</option>
                    <option value="Somalia">Somalia</option>
                    <option value="South Africa">South Africa</option>
                    <option value="South Georgia and The South Sandwich Islands">South Georgia and The South Sandwich Islands</option>
                    <option value="Spain">Spain</option>
                    <option value="Sri Lanka">Sri Lanka</option>
                    <option value="Sudan">Sudan</option>
                    <option value="Suriname">Suriname</option>
                    <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
                    <option value="Swaziland">Swaziland</option>
                    <option value="Sweden">Sweden</option>
                    <option value="Switzerland">Switzerland</option>
                    <option value="Syrian Arab Republic">Syrian Arab Republic</option>
                    <option value="Taiwan, Province of China">Taiwan, Province of China</option>
                    <option value="Tajikistan">Tajikistan</option>
                    <option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
                    <option value="Thailand">Thailand</option>
                    <option value="Timor-leste">Timor-leste</option>
                    <option value="Togo">Togo</option>
                    <option value="Tokelau">Tokelau</option>
                    <option value="Tonga">Tonga</option>
                    <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                    <option value="Tunisia">Tunisia</option>
                    <option value="Turkey">Turkey</option>
                    <option value="Turkmenistan">Turkmenistan</option>
                    <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
                    <option value="Tuvalu">Tuvalu</option>
                    <option value="Uganda">Uganda</option>
                    <option value="Ukraine">Ukraine</option>
                    <option value="United Arab Emirates">United Arab Emirates</option>
                    <option value="United Kingdom">United Kingdom</option>
                    <option value="United States">United States</option>
                    <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
                    <option value="Uruguay">Uruguay</option>
                    <option value="Uzbekistan">Uzbekistan</option>
                    <option value="Vanuatu">Vanuatu</option>
                    <option value="Venezuela">Venezuela</option>
                    <option value="Viet Nam">Viet Nam</option>
                    <option value="Virgin Islands, British">Virgin Islands, British</option>
                    <option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option>
                    <option value="Wallis and Futuna">Wallis and Futuna</option>
                    <option value="Western Sahara">Western Sahara</option>
                    <option value="Yemen">Yemen</option>
                    <option value="Zambia">Zambia</option>
                    <option value="Zimbabwe">Zimbabwe</option>
                  </select>
                </td>
              </tr>
              <tr>
                <td valign="top"><label class="Labels" for="comments">Comments:</label></td>
                <td><textarea cols="30" id="00NA0000003cTKQ" name="Comments__c" rows="7" tabindex="4"></textarea></td>
              </tr>
              <tr>
                <td colspan="2" style="text-align: right; padding-top: 10px;">
                  <div style="float:right;">
                    <input id="Submit" name="Submit" tabindex="5" src="http://www.payleap.com/landingpages/cre/images/getstartedform.png" type="image">
                  </div>
                  <div style="float:right; padding-top:10px;">
                    <a href="javascript:void(0)" onclick="document.install_12.submit(); return false;">Skip This Step</a>&nbsp;&nbsp;&nbsp;
                  </div>
                  <input id="campaign_id" name="campaign_id" value="f01fe860-6eee-29ad-08e5-4a54c9377e4e" type="hidden">
                  <input id="redirect_url" name="redirect_url" value="http://www.loadedcommerce.com/thanks-for-your-interest-in-loaded-payments-pg-400.html?CDpath=293" type="hidden">
                  <input id="assigned_user_id" name="assigned_user_id" value="1" type="hidden">
                  <input id="oid" name="oid" value="00DA0000000YAWg" type="hidden">
                  <input id="retURL" name="retURL" value="<?php echo curPageURL(); ?>?step=lp" type="hidden">  
                  <input id="lead_source" name="lead_source" value="LoadedCom279 - Pro" type="hidden">
                </td>
              </tr></tbody>
          </table>
        </form>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
        <script type="text/javascript">
          //form validation
          var emailExpression = /[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/g;
          $(document).ready(function () {

              var fSignup = $("#fSignup");

              $("#lead_source").val("LoadedCom279 - Pro");
              //$("#retURL").val(window.location.href.replace(/(http.+\/).*/, "$1confirmation.html"));

              fSignup.submit(function () {
                  var fName = $("#first_name").val();
                  var lName = $("#last_name").val();
                  var email = $("#email").val();
                  var phone = $("#phone").val();
                  var comments = $("#Comments__c").val();
                  if (!fName || !lName || !email || !phone || !emailExpression.test(email)) {
                    alert("Oops! Please check your information and provide a valid First Name, Last Name, Email Address and Phone number.");
                    return false;
                  }
                  else
                    return true;
              });
          });

          function getParam(name) {
            name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
            var regexS = "[\\?&]" + name + "=([^&#]*)";
            var regex = new RegExp(regexS);
            var results = regex.exec(window.location.href);
            if(results == null)
              return "";
            else
              return decodeURIComponent(results[1].replace(/\+/g, " "));
          }
        </script>

      </td>
    </tr>
    <tr>
      <td align="center">
        <div style="width:120px;margin:0 auto 0 auto;">
          <div style="padding-top:4px;float:left;"></div>
        </div>
      </td>
    </tr>
  </table>

  <?php
  } else {
    switch ($error_msg) {
      case '1':
        $error_code =  TEXT_ERROR_11 ;
        $error_fix =  TEXT_ERROR_1_S ;
        break;
      case '2':
        $error_code =  TEXT_ERROR_12 ;
        $error_fix =  TEXT_ERROR_2_S ;
        break;
      case '3':
        $error_code =  TEXT_ERROR_13 ;
        $error_fix =  TEXT_ERROR_3_S ;
        break;
      case '4':
        $error_code =  TEXT_ERROR_14 ;
        $error_fix =  TEXT_ERROR_4_S ;
        break;
      case '5':
        $error_code =  TEXT_ERROR_15 ;
        $error_fix =  (sprintf(TEXT_ERROR_5_S , $dir_fs_document_root)) ;
        break;
      case '6':
        $error_code =  TEXT_ERROR_16 ;
        $error_fix =  TEXT_ERROR_6_S ;
        break;
      case '7':
        $error_code =  TEXT_ERROR_17 ;
        $error_fix =  TEXT_ERROR_7_S ;
        break;
      case '8':
        $error_code =  TEXT_ERROR_18 ;
        $error_fix =  TEXT_ERROR_8_S ;
        break;
    }
  ?>
  <!-- install error -->
  <form name="install_9b" action="install.php?step=8" method="post">

    <p><?php echo TEXT_INSTALL_8 . $error_code ;?></p>
    <p><?php echo TEXT_INSTALL_9 . $error_fix ;?> </p>
    <?php
      echo osc_draw_hidden_field('error_msg', $error_msg);
      reset($_POST);
      while (list($key, $value) = each($_POST)) {
        if (($key != 'step') && $key != 'x' && $key != 'y') {
          if (is_array($value)) {
            for ($i=0; $i<sizeof($value); $i++) {
              echo osc_draw_hidden_field($key . '[]', $value[$i]);
            }
          } else {
            echo osc_draw_hidden_field($key, $value);
          }
        }
      }
      echo osc_draw_hidden_field(error_msg, $error_msg);
    ?>

    <p style="text-align: center;">
      <a href="javascript:void(0)" onclick="document.install_9b.submit(); return false;"><span class="installation-button-left">&nbsp;</span><span class="installation-button-middle"><?php echo BUTTON_NEW_BACK ;?></span><span class="installation-button-right">&nbsp;</span></a>
    </p>

  </form>
  <?php
  }
?>