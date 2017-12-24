<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
    <title><?php echo TITLE; ?></title>
    <base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
  </head>
  <body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
  <style>
    body { font-family: Verdana, Arial, sans-serif; font-size: 10px; margin:auto; width: 600px; border: 1px solid #909090; padding: 30px;}
    .s { font-family: Verdana, Arial, sans-serif; font-size: 10px; background-color: #99ff00; }
    .w { font-family: Verdana, Arial, sans-serif; font-size: 10px; background-color: #ffb3b5; }
    .rn { width: 30px; float: left; font-family: Verdana, Arial, sans-serif; font-size: 10px; background-color: #e6e6e6; }
    .r { margin-left: 30px; position: relative; margin-bottom: 3px; font-family: Verdana, Arial, sans-serif; font-size: 10px; background-color: #e9e9e9; }
    .pageHeading { font-family: Arial, Verdana, sans-serif; font-size: 24px; font-weight: bold; padding-top: 10px; padding-bottom: 7px; }
  </style>
  <div class="pageHeading">MailBeez Automatic Installation<br>
    <em>Experimental</em></div>
  <hr size="1" noshade><?php
if (MH_PLATFORM == 'xtc' || MH_PLATFORM == 'gambio') {

  // gambio has removed TABLE_ADMIN_ACCESS from storefront context...
  if (!defined('TABLE_ADMIN_ACCESS')) {
    define('TABLE_ADMIN_ACCESS', 'admin_access');
  };


  echo "platform detected: " . MH_PLATFORM;
  echo "<br>automatic DB update<br>
	<br>
	";

  $sql = array();
  $sql[] = "ALTER TABLE " . TABLE_ADMIN_ACCESS . " ADD mailbeez INT(1) DEFAULT '0' NOT NULL ;";
  mh_db_add_field(TABLE_ADMIN_ACCESS, 'mailbeez', $sql);

  $field_info = mh_db_check_field_exists(TABLE_ADMIN_ACCESS, 'mailbeez');

  if ($field_info != false) {
    echo 'TABLE_ADMIN_ACCESS (' . TABLE_ADMIN_ACCESS . ') updated - added column "mailbeez"<br>';
  }

  mh_db_query("UPDATE " . TABLE_ADMIN_ACCESS . " SET mailbeez = '2' WHERE customers_id = 'groups' LIMIT 1");
  mh_db_query("UPDATE " . TABLE_ADMIN_ACCESS . " SET mailbeez = '1' WHERE customers_id = '1' LIMIT 1");
  // UPDATE `admin_access` SET mailbeez = 1;

  echo "<br>done<br><br>";
  echo "<b>please go to admin > tools > mailbeez to finish your installation</b>";
} else {
 ?>

  Please follow the installation manual on <a href="http://www.mailbeez.com/documentation/installation/">http://www.mailbeez.com/documentation/installation/</a>

  <?php
}
?>
</body>
</html>