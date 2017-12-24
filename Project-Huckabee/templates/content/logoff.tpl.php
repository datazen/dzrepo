   <?php
    // RCI code start
    echo $cre_RCI->get('global', 'top');
    echo $cre_RCI->get('logoff', 'top');
    // RCI code eof
    ?>
<div class="row">
  <div class="col-sm-12 col-lg-12">
    <h1 class="no-margin-top"><?php echo HEADING_TITLE; ?></h1>
    <div class="well">
      <p><?php echo TEXT_MAIN; ?></p>
    </div>
      <div class="button-set clearfix">
      <form action="<?php echo tep_href_link(FILENAME_DEFAULT); ?>" method="post"><button class="pull-right btn btn-lg btn-primary">Continue</button></form>

    </div>
  </div>
</div>

<?php /*  <table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB;?>">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td><?php echo tep_image(DIR_WS_IMAGES . 'table_background_man_on_board.gif', HEADING_TITLE); ?></td>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="pageHeading" align="center"><?php echo HEADING_TITLE; ?></td>
              </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo TEXT_MAIN; ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <?php
      // RCI code start
      echo $cre_RCI->get('logoff', 'menu');
      // RCI code eof
      ?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_template_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table>*/?>
   <?php
    // RCI code start
    echo $cre_RCI->get('logoff', 'bottom');
    echo $cre_RCI->get('global', 'bottom');
    // RCI code eof
    ?>