<?php
/*
  $Id: login.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_LOGIN);

  if (isset($_GET['action']) && ($_GET['action'] == 'process')) {
    $email_address = tep_db_prepare_input($_POST['email_address']);
    $log_times = (isset($_POST['log_times']) ? $_POST['log_times']+1 : 1);
    if ( $log_times >= 4 ) {
      $_SESSION['password_forgotten'] = true;
    }

    // Check if email exists
    $check_admin_query = tep_db_query("select admin_id as check_id, admin_firstname as check_firstname, admin_lastname as check_lastname, admin_email_address as check_email_address from " . TABLE_ADMIN . " where admin_email_address = '" . tep_db_input($email_address) . "'");
    if (!tep_db_num_rows($check_admin_query)) {
      $_GET['login'] = 'fail';
    } else {
      $check_admin = tep_db_fetch_array($check_admin_query);
      $_GET['login'] = 'success';
      $makePassword = tep_create_hard_pass();
      
      tep_mail($check_admin['check_firstname'] . ' ' . $check_admin['admin_lastname'], $check_admin['check_email_address'], ADMIN_EMAIL_SUBJECT, sprintf(ADMIN_EMAIL_TEXT, $check_admin['check_firstname'], HTTP_SERVER . DIR_WS_ADMIN, $check_admin['check_email_address'], $makePassword, STORE_OWNER), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
      tep_db_query("update " . TABLE_ADMIN . " set admin_password = '" . tep_encrypt_password($makePassword) . "' where admin_id = '" . $check_admin['check_id'] . "'");
    }
  }
  
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DEFAULT);

  if  (isset($_GET['login']) && $_GET['login'] == 'success' ) {
    $success_message = TEXT_FORGOTTEN_SUCCESS;
  } elseif  (isset($_GET['login']) && $_GET['login'] == 'fail' ) {
    $info_message = TEXT_FORGOTTEN_ERROR;
  }
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<head>
  <link rel="apple-touch-icon" sizes="57x57" href="assets/img/favicons/apple-icon-57x57.png">
  <link rel="apple-touch-icon" sizes="60x60" href="assets/img/favicons/apple-icon-60x60.png">
  <link rel="apple-touch-icon" sizes="72x72" href="assets/img/favicons/apple-icon-72x72.png">
  <link rel="apple-touch-icon" sizes="76x76" href="assets/img/favicons/apple-icon-76x76.png">
  <link rel="apple-touch-icon" sizes="114x114" href="assets/img/favicons/apple-icon-114x114.png">
  <link rel="apple-touch-icon" sizes="120x120" href="assets/img/favicons/apple-icon-120x120.png">
  <link rel="apple-touch-icon" sizes="144x144" href="assets/img/favicons/apple-icon-144x144.png">
  <link rel="apple-touch-icon" sizes="152x152" href="assets/img/favicons/apple-icon-152x152.png">
  <link rel="apple-touch-icon" sizes="180x180" href="assets/img/favicons/apple-icon-180x180.png">
  <link rel="icon" type="image/png" sizes="192x192"  href="assets/img/favicons/android-icon-192x192.png">
  <link rel="icon" type="image/png" sizes="32x32" href="assets/img/favicons/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="96x96" href="assets/img/favicons/favicon-96x96.png">
  <link rel="icon" type="image/png" sizes="16x16" href="assets/img/favicons/favicon-16x16.png">
  <link rel="manifest" href="assets/img/favicons/manifest.json">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="msapplication-TileImage" content="assets/img/favicons/ms-icon-144x144.png">
  <meta name="theme-color" content="#ffffff">    
  
  <meta charset="utf-8" />
  <title><?php echo TITLE; ?> | Logoff Page</title>
  <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
  <meta content="Create Great Looking Mobile Responsive E-commerce Sites. Designed not just for the challenges of today, but tomorrow. Open source. Unlimited Free Edition." name="description" />
  <meta content="Loaded Commerce" name="author" />
  <?php
  // themes: black, blue, default, red, orange, purple
  $theme = (isset($_SESSION['theme']) && $_SESSION['theme'] != '') ? $_SESSION['theme'] : 'blue';
  ?>  
  <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
  <link href="assets/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css" rel="stylesheet" />
  <link href="assets/plugins/bootstrap4/css/bootstrap.min.css" rel="stylesheet" />
  <link href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
  <link href="assets/css/animate.min.css" rel="stylesheet" />
  <link href="assets/css/style-bs4.min.css" rel="stylesheet" />
  <link href="assets/css/style.css" rel="stylesheet" />
  <link href="assets/css/style-responsive.min.css" rel="stylesheet" />
  <link href="assets/css/theme/<?php echo $theme; ?>.css" rel="stylesheet" id="theme" />
</head>
<body> 
<style>
.login .login-content {
    padding: 40px 40px 20px 40px;
}
</style>
<!-- begin #page-loader -->
<div id="page-loader" class="fade in"><span class="spinner"></span></div>
<!-- end #page-loader -->
<div class="login-cover">
  <div class="login-cover-image"><img src="assets/img/login-bg/bg-1.jpg" data-id="login-cover-image" alt="" /></div>
  <div class="login-cover-bg"></div>
</div>
<!-- begin #page-container -->
<div id="page-container" class="fade">
  <!-- begin logoff -->
  <div class="login login-v2" data-pageload-addclass="animated fadeIn">
    <!-- begin brand -->
      <div class="login-header">
        <div class="login-brand clearfix">
          <a href="index.html" class="navbar-brand">
            <span class="login-brand-logo"></span>
            <span class="login-brand-text"> Loaded Commerce </span>
            <small class="login-brand-slogan"><i>Community Edition</i></small>
          </a>
        </div>

        <div class="icon">
          <i class="fa fa-sign-in"></i>
        </div>
      </div>
      <!-- end brand -->


      <div class="login-content">
        <div class="login-content-heading"><?php echo HEADING_TITLE_FORGOTTEN; ?></div>        

        <?php echo tep_draw_form('login', FILENAME_PASSWORD_FORGOTTEN, 'action=process', 'post', 'class="margin-bottom-0"', 'SSL') . tep_draw_hidden_field("action","process");
        if (isset($_SESSION['password_forgotten'])) {
          ?>
          <div class="form-group m-b-20"><?php echo TEXT_FORGOTTEN_FAIL; ?></div>
          <?php
          $success_message = '';
        } elseif (isset($success_message)) {
          $success_message = TEXT_FORGOTTEN_SUCCESS . '<br><br><a href="' . tep_href_link(FILENAME_LOGIN, '' , 'SSL') . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>';
        } else {
          if (isset($info_message)) {
            echo '<tr><td colspan="2"><div class="message">' . $info_message . '</div></td></tr>' . tep_draw_hidden_field('log_times', $log_times);
          } else {
            echo tep_draw_hidden_field('log_times', '0');
          }
        }


        if (!isset($success_message) && !isset($_SESSION['password_forgotten'])){
          ?>                        
          <div class="m-t-20">
            <div class="login-user-message text-justify mb-4"><?php echo TEXT_FORGOTTEN_USER_MESSAGE;?></div>
          </div>
          <div class="form-group m-b-20">
            <input name="email_address" id="email_address" type="text" class="form-control input-lg" placeholder="Email Address" />
          </div>

        <div class="checkbox m-b-20">
          <button class="btn btn-success btn-block btn-lg" type="submit"><?php echo TEXT_SEND_PASSWORD; ?></button>
          <button class="btn btn-primary btn-block btn-lg" type="button" onClick="parent.location='<?php echo tep_href_link(FILENAME_LOGIN, '' , 'SSL'); ?>'"><?php echo TEXT_BACK_TO_LOGIN; ?></button>
        </div>


          <div class="form-group m-b-20">
            <?php echo '<div class="text-justify mt-3 mb-2">' . TEXT_FORGOTTEN_SUPPORT_MESSAGE . '</div>'; ?>
          </div>          
          <?php 
        } else {
          ?>
          <div class="m-t-20">
            <?php echo $success_message; ?>
          </div>
          <?php
        }
        ?>
        </form>
      </div>
        <!-- end login -->
      <div class="p-relative clearfix"><p class="login-brand-version"><i>v<?php echo INSTALLED_VERSION; ?></i></p></div>
  </div>
  <!-- end logoff -->
</div>
<!-- end page container -->
<script src="assets/plugins/jquery/jquery-1.9.1.min.js"></script>
<script src="assets/plugins/jquery/jquery-migrate-1.1.0.min.js"></script>
<script src="assets/plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>
<script src="assets/plugins/tether/js/tether.min.js"></script>
<script src="assets/plugins/bootstrap4/js/bootstrap.min.js"></script>
<!--[if lt IE 9]>
    <script src="assets/crossbrowserjs/html5shiv.js"></script>
    <script src="assets/crossbrowserjs/respond.min.js"></script>
    <script src="assets/crossbrowserjs/excanvas.min.js"></script>
<![endif]-->
<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="assets/js/apps.js"></script>
<script>
$(document).ready(function() {
  App.init();
});
</script>
<?php
  require('includes/application_bottom.php');
?>
</body>
</html>