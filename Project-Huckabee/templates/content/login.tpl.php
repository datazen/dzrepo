   <?php
    // RCI code start
    echo $cre_RCI->get('global', 'top');
    echo $cre_RCI->get('login', 'top');
    // RCI code eof
    echo tep_draw_form('login', tep_href_link(FILENAME_LOGIN, '', 'SSL'),'post').tep_draw_hidden_field('action','process'); ?>
    <?php
  if ($messageStack->size('login') > 0) {
      ?>
<div class="message-success-container alert alert-success">
<?php echo $messageStack->output('login'); ?>
</div>


      <?php
  }
  if ($cart->count_contents() > 0) {
      ?>

         <?php echo TEXT_VISITORS_CART; ?>



      <?php
    }
    if (PWA_ON == 'false') {
         if ( file_exists(TEMPLATE_FS_CUSTOM_MODULES . FILENAME_PWA_ACC_LOGIN)) {
            require(TEMPLATE_FS_CUSTOM_MODULES . FILENAME_PWA_ACC_LOGIN);
        } else {
            require(DIR_WS_MODULES . FILENAME_PWA_ACC_LOGIN);
        }
    } else {
         if ( file_exists(TEMPLATE_FS_CUSTOM_MODULES . FILENAME_PWA_PWA_LOGIN)) {
            require(TEMPLATE_FS_CUSTOM_MODULES . FILENAME_PWA_PWA_LOGIN);
        } else {
            require(DIR_WS_MODULES . FILENAME_PWA_PWA_LOGIN);
        }
    }
    // RCI code start
    echo $cre_RCI->get('login', 'insideformbelowbuttons');
    // RCI code eof
    ?>
   </form>
    <?php
    // RCI code start
    echo $cre_RCI->get('login', 'bottom');
    echo $cre_RCI->get('global', 'bottom');
    // RCI code eof
    ?>