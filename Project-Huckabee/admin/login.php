<?php
/*
  $Id: login.php,v 6.5.4 2017/12/17 01:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2017 Loaded Commerce
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

require('includes/application_top.php');

if ($session_started == false) {
  echo 'Session not started';
}
$error = false;

if ( (isset($_POST['action']) && ($_POST['action'] == 'process')) && (isset($_POST['password']) && isset($_POST['email_address'])) ) {
    
  $email_address = tep_db_prepare_input($_POST['email_address']);
  $password = tep_db_prepare_input($_POST['password']);
  // Check if email exists
  $check_admin_query = tep_db_query("select admin_id as login_id, admin_groups_id as login_groups_id, admin_firstname as login_firstname, admin_email_address as login_email_address, admin_password as login_password, admin_modified as login_modified, admin_logdate as login_logdate, admin_lognum as login_lognum from " . TABLE_ADMIN . " where admin_email_address = '" . tep_db_input($email_address) . "'");
  if (!tep_db_num_rows($check_admin_query)) {
    $error = true;  
  } else {
    $check_admin = tep_db_fetch_array($check_admin_query);
    // Check that password is good
    if (!tep_validate_password($password, $check_admin['login_password'])) {
      $error = true;
    } else {
      if (isset($_SESSION['password_forgotten'])) {
        unset($_SESSION['password_forgotten']);
      }
      $login_email_address = $check_admin['login_email_address'];
      $login_logdate = $check_admin['login_logdate'];
      $login_lognum = $check_admin['login_lognum'];
      $login_modified = $check_admin['login_modified'];
      $_SESSION['login_id'] = $check_admin['login_id'];
      $_SESSION['login_groups_id'] = $check_admin['login_groups_id'];
      $_SESSION['login_firstname'] = $check_admin['login_firstname'];
      //$date_now = date('Ymd');
      tep_db_query("update " . TABLE_ADMIN . " set admin_logdate = now(), admin_lognum = admin_lognum+1 where admin_id = '" . $_SESSION['login_id'] . "'");
      $_SESSION['from_login'] = true;
      if (sizeof($navigation->snapshot) > 0) {
        $origin_href = tep_href_link($navigation->snapshot['page'], tep_array_to_string($navigation->snapshot['get'], array(tep_session_name())), $navigation->snapshot['mode']);
        $navigation->clear_snapshot();
        tep_redirect($origin_href);
      } else {
        tep_redirect(tep_href_link(FILENAME_DEFAULT, '', 'SSL'));
      }
    }
  }
}
$password = (isset($_GET['password'])) ? $_GET['password'] : '';
$email_address = (isset($_GET['email_address'])) ? $_GET['email_address'] : '';
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<head>
  <!-- favicons -->
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
  <title><?php echo TITLE; ?> | Login Page</title>
  <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
  <meta content="Create Great Looking Mobile Responsive E-commerce Sites. Designed not just for the challenges of today, but tomorrow. Open source. Unlimited Free Edition." name="description" />
  <meta content="Loaded Commerce" name="author" />
  <?php
  // themes: black, blue, default, red, orange, purple
  if (!isset($_SESSION['theme'])) $_SESSION['theme'] = (defined('ADMIN_THEME') && in_array(ADMIN_THEME, array("red","blue","default","black","purple","orange"))) ? ADMIN_THEME : 'default';
  if (isset($_GET['theme']) && in_array($_GET['theme'], array("red","blue","default","black","purple","orange"))) {
    $_SESSION['theme'] = $_GET['theme'];  
  }
  ?>
  <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
  <link href="assets/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css" rel="stylesheet" />
  <link href="assets/plugins/bootstrap4/css/bootstrap.min.css" rel="stylesheet" />
  <link href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
  <link href="assets/css/animate.min.css" rel="stylesheet" />
  <link href="assets/css/style-bs4.css" rel="stylesheet" />
  <link href="assets/css/style.css" rel="stylesheet" />
  <link href="assets/css/style-responsive.css" rel="stylesheet" />
  <link href="assets/css/theme/<?php echo $_SESSION['theme']; ?>.css" rel="stylesheet" id="theme" />
</head>

<body class="pace-top" onload="document.getElementById('email_address').focus()">
  <!-- begin #page-loader -->
  <div id="page-loader" class="fade in"><span class="spinner"></span></div>
  <!-- end #page-loader -->  
  <div class="login-cover">
    <div class="login-cover-image">
        <img src="assets/img/login-bg/bg-1.jpg" data-id="login-cover-image" class="h-100 w-100" />
    </div>
    <div class="login-cover-bg"></div>
  </div>
  <!-- begin #page-container -->
  <div id="page-container" class="fade">
    <!-- begin login -->
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
        <div class="login-content-heading">Administrator Login</div>
        <?php 
        if ($error) echo '<div class="alert-container"><div class="alert alert-danger text-white">' . TEXT_LOGIN_ERROR . '</div></div>';
        echo tep_draw_form('login', FILENAME_LOGIN, 'action=process', 'post', 'class="mb-0"', 'SSL') . tep_draw_hidden_field("action","process"); 
        ?>
        <div class="form-group m-b-20">
          <input name="email_address" id="email_address" type="text" class="form-control input-lg" placeholder="Email Address" />
        </div>
        <div class="form-group m-b-20">
          <input name="password" id="password" type="password" class="form-control input-lg" placeholder="Password" />
        </div>
        <div class="checkbox m-b-20">
             
        </div>
        <div class="login-buttons">
          <button type="submit" class="btn btn-success btn-block btn-lg">Login</button>
        </div>
        <div class="m-t-20 clearfix">
          <?php echo '<a class="login-password-forgotten mr-1" href="' . tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL') . '">' . HEADING_TITLE_FORGOTTEN . '</a>';?>
        </div>
        </form>
      </div>
      <div class="p-relative clearfix"><p class="login-brand-version"><i>v<?php echo INSTALLED_VERSION; ?></i></p></div>
    </div>
    <!-- end login -->  
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
    setTimeout(function(){ $('.alert').delay(3000).fadeOut('slow'); }, 5000);
  });
  </script>
  <?php
  require('includes/application_bottom.php');
  ?>
</body>
</html>