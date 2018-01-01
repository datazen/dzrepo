<?php
/**
  @package    admin
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: rpc.php v1.0 2013-08-08 datazen $
*/
//header('Cache-Control: no-cache, must-revalidate');
//header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

//ini_set('display_errors', 0);
//ini_set('error_reporting', 0);

require('includes/application_top.php');

define('RPC_STATUS_SUCCESS', 1);
define('RPC_STATUS_ERROR', -1);
define('RPC_STATUS_NO_SESSION', -10);

/*
if ( !isset($_SESSION['login_id']) ) {
  if ( // the following need no session to proceed
  /*    isset($_GET['action']) && $_GET['action'] == 'validateLogin' || 
      isset($_GET['action']) && $_GET['action'] == 'lostPasswordConfirmEmail' || 
      isset($_GET['action']) && $_GET['action'] == 'lostPasswordConfirmKey' ||  
      isset($_GET['action']) && $_GET['action'] == 'passwordChange' ||  
      isset($_GET['action']) && $_GET['action'] == 'apiHealthCheck' ||  
      isset($_GET['action']) && $_GET['action'] == 'validateSerial' 
      ) {
  } else {     
    echo json_encode(array('rpcStatus' => RPC_STATUS_NO_SESSION));
    exit;
  }
}
*/
$result = array();
$rpcStatus = 0;
$action = (isset($_GET['action']) && $_GET['action'] != '') ? $_GET['action'] : '';

switch ($action) {
  case 'checkAdminEmailExists':
    $new_email = (isset($_GET['email']) && $_GET['email'] != '') ? $_GET['email'] : '';
    $stored_email = array();
    $check_email_query = tep_db_query("SELECT admin_email_address FROM " . TABLE_ADMIN . "");
    while ($check_email = tep_db_fetch_array($check_email_query)) {
      $stored_email[] = $check_email['admin_email_address'];
    }
    $rpcStatus = RPC_STATUS_SUCCESS;
    $exists = (in_array($new_email, $stored_email)) ? true : false;
    $result = array('rpcStatus' => 1, 'result' => $exists);
    break;
  case 'changeThemeMode':
    $mode = isset($_GET['mode']) ? $_GET['mode'] : 'dark';
    $updateMode = tep_db_query("UPDATE " . TABLE_CONFIGURATION . " SET configuration_value = '" . $mode . "' WHERE configuration_key = 'ADMIN_THEME_MODE'");
    $result = array('rpcStatus' => 1, 'result' => $updateMode);
    break;

}

header('Content-Type: application/json');
echo json_encode($result);

?>