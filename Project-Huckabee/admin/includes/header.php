<?php
/*
  $Id: header.php,v 6.5.4 2017/12/17 01:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2017 Loaded Commerce
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
  /*
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
*/
?>

<?php 
//  require(DIR_WS_INCLUDES . 'warnings.php'); 
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
    <ul class="nav navbar-nav navbar-right">
      <li>
        <form class="navbar-form full-width">
          <div class="form-group">
            <input type="text" class="form-control" placeholder="Enter keyword" />
            <button type="submit" class="btn btn-search"><i class="fa fa-search"></i></button>
          </div>
        </form>
      </li>
      <li class="dropdown">
        <a href="javascript:;" data-toggle="dropdown" class="dropdown-toggle f-s-14">
          <i class="fa fa-bell-o"></i>
          <span class="label">5</span>
        </a>
        <ul class="dropdown-menu media-list pull-right animated fadeInDown">
                        <li class="dropdown-header">Notifications (5)</li>
                        <li class="media">
                            <a href="javascript:;">
                                <div class="media-left"><i class="fa fa-bug media-object bg-red"></i></div>
                                <div class="media-body">
                                    <h6 class="media-heading">Server Error Reports</h6>
                                    <div class="text-muted f-s-11">3 minutes ago</div>
                                </div>
                            </a>
                        </li>
                        <li class="media">
                            <a href="javascript:;">
                                <div class="media-left"><img src="assets/img/user-1.jpg" class="media-object" alt="" /></div>
                                <div class="media-body">
                                    <h6 class="media-heading">John Smith</h6>
                                    <p>Quisque pulvinar tellus sit amet sem scelerisque tincidunt.</p>
                                    <div class="text-muted f-s-11">25 minutes ago</div>
                                </div>
                            </a>
                        </li>
                        <li class="media">
                            <a href="javascript:;">
                                <div class="media-left"><img src="assets/img/user-2.jpg" class="media-object" alt="" /></div>
                                <div class="media-body">
                                    <h6 class="media-heading">Olivia</h6>
                                    <p>Quisque pulvinar tellus sit amet sem scelerisque tincidunt.</p>
                                    <div class="text-muted f-s-11">35 minutes ago</div>
                                </div>
                            </a>
                        </li>
                        <li class="media">
                            <a href="javascript:;">
                                <div class="media-left"><i class="fa fa-plus media-object bg-green"></i></div>
                                <div class="media-body">
                                    <h6 class="media-heading"> New User Registered</h6>
                                    <div class="text-muted f-s-11">1 hour ago</div>
                                </div>
                            </a>
                        </li>
                        <li class="media">
                            <a href="javascript:;">
                                <div class="media-left"><i class="fa fa-envelope media-object bg-blue"></i></div>
                                <div class="media-body">
                                    <h6 class="media-heading"> New Email From John</h6>
                                    <div class="text-muted f-s-11">2 hour ago</div>
                                </div>
                            </a>
                        </li>
                        <li class="dropdown-footer text-center">
                            <a href="javascript:;">View more</a>
                        </li>
        </ul>
      </li>
      <li class="dropdown navbar-user">
        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
          <img src="assets/img/user-13.jpg" alt="" /> 
          <span class="hidden-xs">Adam Schwartz</span> <b class="caret"></b>
        </a>
        <ul class="dropdown-menu animated fadeInLeft">
          <li class="arrow"></li>
          <li><a href="javascript:;">Edit Profile</a></li>
          <li><a href="javascript:;"><span class="badge badge-danger pull-right">2</span> Inbox</a></li>
          <li><a href="javascript:;">Calendar</a></li>
          <li><a href="javascript:;">Setting</a></li>
          <li class="divider"></li>
          <li><a href="<?php echo tep_href_link(FILENAME_LOGOFF, '', 'SSL'); ?>">Log Out</a></li>
        </ul>
      </li>
    </ul>
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
  if ($messageStack->size('search') > 0) {
      echo '<div class="content"><div class="panel-body">';
      echo $messageStack->output('search');
      echo '</div></div>';
  }
// RCI bottom
echo $cre_RCI->get('header', 'bottom');
?>