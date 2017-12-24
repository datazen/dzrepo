<?php
/*
  $Id: authors.php,v 1.2 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
$authors = new box_authors();
if (count($authors->rows) > 0) {
  ?>
  <!-- authors //-->
<?php/*  <tr>
    <td>
      <?php
      $info_box_contents = array();
      $info_box_contents[] = array('text' =>'<font color="' . $font_color . '">' . BOX_HEADING_AUTHORS . '</font>');
      new $infobox_template_heading($info_box_contents, '', ((isset($column_location) && $column_location !='') ? $column_location : '') );
      if (count($authors->rows) <= MAX_DISPLAY_AUTHORS_IN_A_LIST) {
        // Display a list
        $authors_list = '';
        foreach ($authors->rows as $authors) {
          $authors_name = ((strlen($authors['authors_name']) > MAX_DISPLAY_AUTHOR_NAME_LEN) ? substr($authors['authors_name'], 0, MAX_DISPLAY_AUTHOR_NAME_LEN) . '..' : $authors['authors_name']);
          if (isset($_GET['authors_id']) && ($_GET['authors_id'] == $authors['authors_id'])) $authors_name = '<b>' . $authors_name .'</b>';
          $authors_list .= '<a href="' . tep_href_link(FILENAME_ARTICLES, 'authors_id=' . $authors['authors_id']) . '">' . $authors_name . '</a><br>';
        }
        $authors_list = substr($authors_list, 0, -4);
        $info_box_contents = array();
        $info_box_contents[] = array('text' => $authors_list);
      } else {
        // Display a drop-down
        $authors_array = array();
        if (MAX_AUTHORS_LIST < 2) {
          $authors_array[] = array('id' => '', 'text' => PULL_DOWN_DEFAULT);
        }
        foreach ($authors->rows as $authors) {
          $authors_name = ((strlen($authors['authors_name']) > MAX_DISPLAY_AUTHOR_NAME_LEN) ? substr($authors['authors_name'], 0, MAX_DISPLAY_AUTHOR_NAME_LEN) . '..' : $authors['authors_name']);
          $authors_array[] = array('id' => $authors['authors_id'],
                                   'text' => $authors_name);
        }
        $info_box_contents = array();
        $info_box_contents[] = array('form' => tep_draw_form('authors', tep_href_link(FILENAME_ARTICLES, '', 'NONSSL', false), 'get'),
                                     'text' => tep_draw_pull_down_menu('authors_id', $authors_array, (isset($_GET['authors_id']) ? $_GET['authors_id'] : ''), 'onChange="this.form.submit();" size="' . MAX_AUTHORS_LIST . '" style="width: 100%"') . tep_hide_session_id());
      }
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
  </tr>*/?>


    <script>
    $(document).ready(function() {
      $('.box-manufacturers-select').addClass('form-input-width');
    });
    $('.box-manufacturers-selection').addClass('form-group full-width');
    $('.box-manufacturers-select').addClass('form-control');
  </script>
       <div class="well">
        <div class="box-header small-margin-bottom small-margin-left"><?php echo  BOX_HEADING_AUTHORS ; ?></div>
        <form role="form" class="form-inline no-margin-bottom" name="authors" action="<?php echo tep_href_link(FILENAME_ARTICLES, '', 'NONSSL', false)?>" method="get">
            <?php
        $authors_array = array();
        if (MAX_AUTHORS_LIST < 2) {
          $authors_array[] = array('id' => '', 'text' => PULL_DOWN_DEFAULT);
          }
        foreach ($authors->rows as $authors) {
          $authors_name = ((strlen($authors['authors_name']) > MAX_DISPLAY_AUTHOR_NAME_LEN) ? substr($authors['authors_name'], 0, MAX_DISPLAY_AUTHOR_NAME_LEN) . '..' : $authors['authors_name']);
          $authors_array[] = array('id' => $authors['authors_id'],
                                   'text' => $authors_name);
          }

            echo '<ul class="box-information_pages-ul list-unstyled list-indent-large"><li>' . tep_draw_pull_down_menu('authors_id', $authors_array, (isset($_GET['authors_id']) ? $_GET['authors_id'] : ''), 'onChange="this.form.submit();" size="' . MAX_MANUFACTURERS_LIST . '"class="box-manufacturers-select form-control form-input-width" style="width: 100%"') . tep_hide_session_id() . '</select><li></ul>';
           ?>
       </form>
      </div>

  <!-- authors_eof //-->
  <?php
}
?>