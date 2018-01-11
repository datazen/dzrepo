<style>
.toolbar { 
  position:absolute; 
  right:32px; 
  top:25px; 
  z-index:1000; 
  border: 1px dashed green;
}
@media (max-width: 767px) {
  .toolbar {
    position: relative;
    display:block;
    right:0;
    top:0;
    margin: 0 5px 10px 5px;
  }
  .page-header {
    margin: 0 0 10px;
  }
}
</style>
<div class="toolbar text-right">
  <span>This is a toolbar placeholder</span> 
  <div class="btn-group theme-switch">
    <button onclick="changeTheme('dark');" class="btn btn-black btn-xs">Dark</button>
    <button onclick="changeTheme('light');" class="btn btn-default btn-xs">Light</button>
  </div>
</div>
<script>
$(document).ready(function(){
  var mode = '<?php echo (isset($_SESSION['theme_mode'])) ? $_SESSION['theme_mode'] : 'dark'; ?>';
  changeTheme(mode);
});

function changeTheme(mode) {
  if (mode == 'light') {
    $('.dark').addClass('light').removeClass('dark');   
  } else {
    $('.light').addClass('dark').removeClass('light');   
  }
  setThemeModeSession(mode);
}

function setThemeModeSession(mode) {
  var sessid = '<?php echo session_id(); ?>';
  var rpcUrl = "./rpc.php?action=changeThemeMode&mode=MODE&osCAdminID=SESSID";
  $.get( rpcUrl.replace('MODE', mode).replace('SESSID', sessid), function( data ) {
    console.log(print_r(data, true));
  });  

}
</script>