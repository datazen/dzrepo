<?php
/*
  $Id: header.php,v 6.5.4 2017/12/17 01:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2017 Loaded Commerce
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

$languages = tep_get_languages();
$languages_array = array();
$languages_selected = DEFAULT_LANGUAGE;
for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
  $languages_array[] = array('id' => $languages[$i]['code'],
                             'text' => $languages[$i]['name']);
  if ($languages[$i]['directory'] == $language) {
    $languages_selected = $languages[$i]['code'];
  }
}

$my_account_query = tep_db_query ("select admin_id, admin_firstname, admin_lastname from " . TABLE_ADMIN . " where admin_id= " . $_SESSION['login_id']);
$myAccount = tep_db_fetch_array($my_account_query);
$store_admin_name = $myAccount['admin_firstname'] . ' ' . $myAccount['admin_lastname'];
// RCI top
echo $cre_RCI->get('header', 'top');

?>
<style>
.navbar-brand-open {
    width:220px; 
    background-color:#1a2229 !important;
}
.navbar-brand-closed {
    width:60px;
    background-color:#1a2229 !important;
}
.navbar-brand-text {
    position:fixed;
    top:8px;
    left:54px;
}
.navbar-brand-slogan {
    position: fixed;
    left: 90px;
    top: 21px;
    font-size: 0.7em;
    color: #b8daff;
} 
</style>
<!-- begin #header -->
<div id="header" class="header navbar navbar-default navbar-fixed-top">
  <!-- begin container-fluid -->
  <div class="container-fluid">
    <!-- begin mobile sidebar expand / collapse button -->
    <div id="header-brand" class="navbar-header navbar-brand-open p-relative">
      <a href="javascript:;" data-click="sidebar-minify" class="navbar-brand"><span class="navbar-logo"></span><span class="navbar-brand-text"> Loaded Commerce </span><small class="navbar-brand-slogan"><i>Community Edition</i></small></a>
      <button type="button" class="navbar-toggle" data-click="sidebar-toggled">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>
    <!-- end mobile sidebar expand / collapse button -->
    
    <!-- begin header navigation right -->
    <div class="w-100">    
      <ul class="nav navbar-nav navbar-right">
        <!--li>
          <form class="navbar-form full-width">
            <div class="form-group">
              <input type="text" class="form-control" placeholder="Enter keyword" />
              <button type="submit" class="btn btn-search"><i class="fa fa-search"></i></button>
            </div>
          </form>
        </li -->

        <li class="dropdown open mt-1">
          <a class="dropdown-toggle f-s-14" data-toggle="dropdown" href="javascript:;" aria-expanded="true">
            <i class="fa fa-bell-o"></i>
            <span class="label"><?php echo $messageStack->size('header');?></span>
          </a>
          <ul class="dropdown-menu media-list pull-right animated fadeInDown">
            <li class="dropdown-header">Notifications (<?php echo $messageStack->size('header'); ?>)</li>
            <li class="media">
              <div class="media-body">
              <!-- warnings //-->
              <?php
                if ($messageStack->size('header') > 0) {
                  echo $messageStack->output('header');
                }
                if (isset($_GET['error_message']) && tep_not_null($_GET['error_message'])) {
                ?>
                <table border="0" width="100%" cellspacing="0" cellpadding="2" class="table">
                  <tr class="headerError"> <td class="headerError"><?php echo htmlspecialchars(urldecode($_GET['error_message'])); ?></td> </tr>
                </table>
                <?php
                }

                if (isset($_GET['info_message']) && tep_not_null($_GET['info_message'])) {
                ?>
                <table border="0" width="100%" cellspacing="0" cellpadding="2" class="table">
                  <tr class="headerInfo"> <td class="headerInfo"><?php echo htmlspecialchars($_GET['info_message']); ?></td> </tr>
                </table>
                <?php
                }
                ?>
              <!-- warning_eof //-->
              </div>
            </li>
          </ul>
        </li>

        <li class="dropdown navbar-user">
          <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
            <img src="assets/img/user.png" alt="" /> 
            <span class="hidden-xs"><?php echo $store_admin_name;?></span> <b class="caret"></b>
          </a>
          <ul class="dropdown-menu animated fadeInLeft">
            <li class="arrow"></li>
            <li><a href="<?php echo tep_href_link(FILENAME_ADMIN_ACCOUNT,'','SSL');?>">Edit Profile</a></li>
            <li class="divider"></li>
            <li><a href="<?php echo tep_href_link(FILENAME_LOGOFF, '', 'SSL'); ?>">Log Out</a></li>
          </ul>
        </li>
      </ul>
    </div>
    <!-- end header navigation right -->
  </div>
  <!-- end container-fluid -->
</div>
<!-- end #header -->
<script>
$(document).ready(function() {
  var width = $(window).width(); 
  if (width < 768) {
    $('.navbar-brand').removeAttr('data-click');
  } else {
    $('.navbar-brand').attr('data-click', 'sidebar-minify');
  }
});
</script>
<?php
// RCI bottom
echo $cre_RCI->get('header', 'bottom');
?>