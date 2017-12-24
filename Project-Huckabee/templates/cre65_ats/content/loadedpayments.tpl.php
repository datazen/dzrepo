<?php
/**
  @name       loadedpayments.tpl.php   
  @version    1.0.0 | 05-21-2012 | datazen
  @author     Loaded Commerce Core Team
  @copyright  (c) 2012 loadedcommerce.com
  @license    GPL2
*/
// RCI top
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('loadedpayments', 'top');
?>
<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB;?>">
  <?php
  if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
    $header_text = '&nbsp;'
    ?>
    <tr>
      <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
       <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
          <td align="right"></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td><?php echo TEXT_ORDER_PLACED; ?></td>
    </tr>
    <?php
  } else {
    $header_text = HEADING_TITLE;
  }
  if (MAIN_TABLE_BORDER == 'yes'){
    table_image_border_top(false, false, $header_text);
  }
  ?>
  <tr>
    <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
      <tr class="infoBoxContents">
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td>
              <div id="container" style="position:relative;">
                <form name="pmtForm" id="pmtForm" action="<?php echo $_SESSION['payform_url']; ?>" target="pmtFrame" method="post"><?php rePost(); ?></form>        
                <div id="loadingContainer"  style="position: absolute; left:220px; top:100px;"><p><img border="0" src="images/lp-loading.png"></p></div>
                <iframe frameborder="0" onload="setTimeout(function() {hideLoader();},1250);" src="" id="pmtFrame" name="pmtFrame" height="300px" width="606px" scrolling="no" marginheight="0" marginwidth="0">Your browser does not support iframes.</iframe> 
              </div>
            </td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <?php
  if (MAIN_TABLE_BORDER == 'yes'){
    table_image_border_bottom();
  }
?>
</table>
<script>
function hideLoader() {
  var loadDiv = document.getElementById("loadingContainer"); 
  loadDiv.style.display = "none"; 
}

window.onload = function(){
  document.forms["pmtForm"].submit();
};     
</script>
<?php
// RCI bottom 
echo $cre_RCI->get('global', 'bottom');
echo $cre_RCI->get('loadedpayments', 'bottom');
?>