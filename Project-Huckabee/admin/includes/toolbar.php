<style>
.toolbar { position:absolute; right:32px; top:25px; z-index:1000; border: 1px dashed green;}
</style>
<div class="toolbar">
  <span>This is a global toolbar</span> 
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