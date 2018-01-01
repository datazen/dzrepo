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
//tep_session_destroy();
unset($_SESSION['login_id']);
unset($_SESSION['login_firstname']);
unset($_SESSION['login_groups_id']);
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
  $theme = (defined('ADMIN_THEME') && ADMIN_THEME != '') ? ADMIN_THEME : 'default';
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
        <div class="login-content-heading"><?php echo HEADING_TITLE; ?></div>        
        <?php echo '<div class="login-user-message mb-4 mt-3">' . TEXT_MAIN . '</div>'; ?>
        <div class="checkbox m-b-20">
          <button class="btn btn-success btn-block btn-lg" onClick="location.href='<?php echo HTTP_CATALOG_SERVER . DIR_WS_HTTP_CATALOG; ?>'"><?php echo TEXT_VISIT_CATALOG; ?></button>
          <button class="btn btn-primary btn-block btn-lg" onClick="location.href='<?php echo HTTP_CATALOG_SERVER . DIR_WS_HTTP_ADMIN; ?>'"><?php echo TEXT_RETURN_TO_ADMIN; ?></button>
        </div>
      </div>
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