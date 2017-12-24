<?php
/*
  $Id: manufacturers.php,v 1.2 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
$manufacture = new box_manufacturers();
$number_of_rows =  count($manufacture->rows);
if ($number_of_rows > 0) {
  ?>
  <!-- manufacturers //-->
  <script>
  $(document).ready(function() {
    $('.box-manufacturers-select').addClass('form-input-width');
  });
  $('.box-manufacturers-selection').addClass('form-group full-width');
  $('.box-manufacturers-select').addClass('form-control');
</script>
     <div class="well"  style="text-transform:uppercase">
      <div class="box-header small-margin-bottom small-margin-left"><?php echo  BOX_HEADING_MANUFACTURERS ; ?></div>
      <form role="form" class="form-inline no-margin-bottom" name="manufacturers" action="<?php echo tep_href_link(FILENAME_DEFAULT, '', 'NONSSL', false)?>" method="get">
          <?php
        $manufacturers_array = array();
        if (MAX_MANUFACTURERS_LIST < 2) {
          $manufacturers_array[] = array('id' => '', 'text' => PULL_DOWN_DEFAULT);
        }
        foreach ($manufacture->rows as $manufacturers) {
          $manufacturers_name = ((strlen($manufacturers['manufacturers_name']) > MAX_DISPLAY_MANUFACTURER_NAME_LEN) ? substr($manufacturers['manufacturers_name'], 0, MAX_DISPLAY_MANUFACTURER_NAME_LEN) . '..' : $manufacturers['manufacturers_name']);
          $manufacturers_array[] = array('id' => $manufacturers['manufacturers_id'],
                                         'text' => $manufacturers_name);
        }

          echo '<ul class="box-information_pages-ul list-unstyled list-indent-large"><li>' . tep_draw_pull_down_menu('manufacturers_id', $manufacturers_array, (isset($_GET['manufacturers_id']) ? (int)$_GET['manufacturers_id'] : ''), 'onChange="this.form.submit();" size="' . MAX_MANUFACTURERS_LIST . '"class="box-manufacturers-select form-control form-input-width" style="width: 100%"') . tep_hide_session_id() . '</select><li></ul>';
         ?>
     </form>
    </div>
  <!-- manufacturers eof//-->
  <?php
}
?>