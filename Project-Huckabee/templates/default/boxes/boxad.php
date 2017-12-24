<?php
/*
  $Id: boxad.php,v 1.2 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- boxad //-->
  <tr>
    <td>
      <?php
      $bannerstring = tep_display_banner('static', $banner);
      $info_box_contents = array();
      $info_box_contents[] = array('align' => 'left',
                                   'text'  => '<font color="' . $font_color . '">' . BOX_AD_LP_HEADING . '</font>'
                                  );
      new $infobox_template_heading($info_box_contents, '', ((isset($column_location) && $column_location !='') ? $column_location : '') );
      $info_box_contents = array();
      $info_box_contents[] = array('align' => 'center',
                                   'text'  => '<a href="https://www.loadedcommerce.com/loaded-payments-pg-381.html?CDpath=0_282" target="_blank">
                                                 <img src="https://www.loadedcommerce.com/images/LPlogoWideGreen.png" border="0" width="150" alt="Loaded Payments - The Easiest Way to Accept Credit Cards Online" title="An all-in-one payment solution for Loaded Commerce Clients" />
                                               </a><br>
                                               <p>The Easiest Way to Accept Credit Cards Online</p>'
                                  );
      new $infobox_template($info_box_contents, true, true, ((isset($column_location) && $column_location !='') ? $column_location : '') );
      if (TEMPLATE_INCLUDE_FOOTER =='true'){
        $info_box_contents = array();
        $info_box_contents[] = array('align' => 'left',
                                     'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                                    );
        new $infobox_template_footer($info_box_contents, ((isset($column_location) && $column_location !='') ? $column_location : '') );
      }  
      ?>
    </td>
  </tr>
<!-- boxad eof//-->