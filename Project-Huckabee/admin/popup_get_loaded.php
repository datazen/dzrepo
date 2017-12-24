<?php
/*
  $Id: popup_get_loaded.php,v 1.0.0.0 2008/06/16 23:38:52 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');
$page = (isset($_GET['page'])) ? $_GET['page'] : '';
  ?>
<!DOCTYPE html>
<head>
    <meta charset="utf-8" />
    <title><?php echo TITLE; ?> | Login Page</title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    <meta content="noindex" name="index" />
    <meta content="" name="author" />
    
    <!-- ================== BEGIN BASE CSS STYLE ================== -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link href="assets/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css" rel="stylesheet" />
    <link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
    <link href="assets/css/animate.min.css" rel="stylesheet" />
    <link href="assets/css/style.min.css" rel="stylesheet" />
    <link href="assets/css/style-responsive.min.css" rel="stylesheet" />
    <link href="assets/css/theme/blue.css" rel="stylesheet" id="theme" />
    <!-- ================== END BASE CSS STYLE ================== -->
    
    <!-- ================== BEGIN BASE JS ================== -->
    <script src="assets/plugins/pace/pace.min.js"></script>
    <!-- ================== END BASE JS ================== -->
</head>
<body>
    <!-- begin #page-loader -->
    <div id="page-loader" class="fade in"><span class="spinner"></span></div>
    <!-- end #page-loader -->
    
    <div class="login-cover">
        <div class="login-cover-image"><img src="assets/img/login-bg/bg-1.jpg" data-id="login-cover-image" alt="" /></div>
        <div class="login-cover-bg"></div>
    </div>
    <!-- begin #page-container -->
    <div id="page-container" class="fade">
        <!-- begin login -->
        <div class="login col-md-6 pull-none" data-pageload-addclass="animated fadeIn" style="margin: 168px auto; position: relative;">
        <div class="news-caption">
<?php
    if ($page == 'login') {
    if (isset($_SESSION['from_login'])) unset($_SESSION['from_login']);
    $email_address = isset($_POST['email_address']) ? $_POST['email_address'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
 ?>
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h4 class="panel-title"><strong>Get Loaded</strong></h4>
              </div>
                <div class="panel-body">
                   <iframe name="fr1" src="messages.php?s=login" scrolling="no" allowtransparency="true" frameborder="0" marginheight="0" marginwidth="0" width="100%" align="center"></iframe>
                    <p class="text-center m-t-40">
                      <a href="<?php echo tep_href_link(FILENAME_DEFAULT, '', 'SSL'); ?>" class="btn btn-primary btn-lg m-r-5"><?php echo IMAGE_BUTTON_CONTINUE; ?></a>
                    </p>
                </div>
            </div>
  <?php
} else if ($page == 'new_admin_member' || $page == 'new_admin_group') {
  ?>
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h4 class="panel-title"><strong>Get Loaded</strong></h4>
              </div>
                <div class="panel-body"> 
  <table border="0" cellpadding="0" cellspacing="0" style="margin: 0 auto; width: 600px;" align="center">
     <tr>
        <td class="box-left"">&nbsp;</td>
        <td style="background: #f0f8fc url(images/window-right.png) repeat-y right;" width="600" colspan="2">
          <?php 
        if ($page == 'new_admin_member') {
          ?>
            <img src="images/window-multi-admin.png" align="left" />
            <?php 
        } else if ($page == 'new_admin_group') { 
          ?>
            <img src="images/window-admin-groups.png" align="left" />
            <?php 
        } 
        ?>
          <img src="images/window-pro.png" align="right" /><img src="images/window-available.png" align="right" style="margin-right: 4px;"   />
        </td>
     </tr>
     <tr>
        <td class="box-left">&nbsp;</td>
        <td class="box-content" valign="top">
          <div class="box-text" style="padding: 1em 0; line-height: 1.8em; font-size: 14px; color: #333;">
          <?php if ($page == 'new_admin_member') { ?>
Multiple Administrators allow unique admin access for you, your webmaster, your staff and anyone else you want.  Upgrade to Loaded Commerce Pro and make running your online business easier.
          <?php } else if ($page == 'new_admin_group') { ?>
Multiple Admin Groups allow you control over user access. Decide who gets access to orders and shipping and who can change the templates.  Upgrade to Loaded Commerce Pro and stay in control of who can do what.         
          <?php } ?>
          </div>
        </td> 
        <td class="box-right" style="width: 48px;">&nbsp;</td>
      </tr>
     <tr>
        <td class="box-left">&nbsp;</td>
        <td align="right" class="box-content" valign="bottom">
        <div style="padding-top: 1em;">
        <a href="javascript:void(0)" onclick="hideNewAdminMember()">No Thanks</a> &nbsp; &nbsp;
        <a href="http://www.loadedcommerce.com/" onclick="hideNewAdminMember()" target="_blank"><img src="images/window-more.png" /></a>
        </div>
        </td>
        <td class="box-right" style="width: 48px;">&nbsp;</td>
     </tr>
  </table>
  </div>
            </div>
  <?php
} else {
?>
              <div class="panel panel-primary">
              <div class="panel-heading">
                <h4 class="panel-title"><strong>Oops!</strong></h4>
              </div>
                <div class="panel-body">
                   <h3>Feature not available in Standard version.</h3>
                    <p class="text-center m-t-40">
                      <a href="<?php echo tep_href_link(FILENAME_DEFAULT, '', 'SSL'); ?>" class="btn btn-primary btn-lg m-r-5"><?php echo IMAGE_BUTTON_CONTINUE; ?></a>
                    </p>
                </div>
            </div>
<?php
  }
?>
           </div>
        <!-- end login -->
        
    </div>
    <!-- end page container -->
    
    <!-- ================== BEGIN BASE JS ================== -->
    <script src="assets/plugins/jquery/jquery-1.9.1.min.js"></script>
    <script src="assets/plugins/jquery/jquery-migrate-1.1.0.min.js"></script>
    <script src="assets/plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <!--[if lt IE 9]>
        <script src="assets/crossbrowserjs/html5shiv.js"></script>
        <script src="assets/crossbrowserjs/respond.min.js"></script>
        <script src="assets/crossbrowserjs/excanvas.min.js"></script>
    <![endif]-->
    <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="assets/plugins/jquery-cookie/jquery.cookie.js"></script>
    <!-- ================== END BASE JS ================== -->
    
    <!-- ================== BEGIN PAGE LEVEL JS ================== -->
    <script src="assets/js/login-v2.demo.min.js"></script>
    <script src="assets/js/apps.min.js"></script>
    <!-- ================== END PAGE LEVEL JS ================== -->

    <script>
        $(document).ready(function() {
            App.init();
            LoginV2.init();
        });
    </script>
    
    

<?php
    require('includes/application_bottom.php');
?>
</body>
</html>