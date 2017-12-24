<?php
/*
  $Id: pages_ul.php,v 2.0 2008/07/08 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/
?>
<!-- default/pages_ul //-->
<!--<tr>
  <td><h2>Site Navigation</h2><br></td>
</tr>-->
<div class="well">
  <?php
    // added for expand Products Menu on homepage - maestro
    if (!isset($_GET['CDpath']) && $content == CONTENT_INDEX_DEFAULT) {
    //  $_GET['CDpath'] = '187_214';
    }

    if ($_GET['CDpath']) {
	     $CDpath_array = array_map('tep_string_to_int', explode('_', $_GET['CDpath']));
      $pID = (isset($_GET['pID']) && $_GET['pID'] != '') ? (int)$_GET['pID'] : 0;
	     $top_cats = array_merge(array(0 => '0'),$CDpath_array);
	     $currentCD = array_pop($top_cats);
	     $top_cats = implode(',',$top_cats);
    } else {
      $top_cats = 0;
	     $currentCD = 0;
	   }

    // Get highest cat id
    $hi_cat_query = tep_db_query("SELECT categories_id FROM " . TABLE_CDS_CATEGORIES . " ORDER BY categories_id DESC LIMIT 1");
    $hi_cat = tep_db_fetch_array($hi_cat_query);
    $hi_cat = $hi_cat['categories_id'];

    // build the SQL
    $listing_sql1 = "SELECT ic.categories_id as 'ID', ic.categories_parent_id as 'parentID', ic.categories_sort_order as 'sort', ic.categories_image as 'image', icd.categories_name as 'name', ic.categories_url_override as 'url', ic.categories_url_override_target as 'target', ic.category_append_cdpath as 'append', 'c' as 'type', ic.categories_sub_category_view as 'view', ic.categories_listing_columns as 'list_columns'
                    from " . TABLE_CDS_CATEGORIES . " ic,
                    " . TABLE_CDS_CATEGORIES_DESCRIPTION . " icd
                    WHERE ic.categories_status = '1'
                    and icd.language_id = '" . (int)$languages_id . "'
                    and ic.categories_in_menu = '1'
					               and ic.categories_id = icd.categories_id
                  	and (ic.categories_parent_id IN (" . $top_cats . ") OR ic.categories_parent_id = " . $currentCD . ")
                  UNION
                  SELECT p.pages_id as 'ID', p2c.categories_id as 'parentID', p2c.page_sort_order as 'sort', p.pages_image as 'image', pd.pages_title as 'name', p.pages_url as 'url', p.pages_url_target as 'target', '' as 'append', 'p' as 'type', '' as 'view', '2' as 'list_columns'
                    from " . TABLE_CDS_PAGES . " p,
                         " . TABLE_CDS_PAGES_DESCRIPTION . "  pd,
                         " . TABLE_CDS_PAGES_TO_CATEGORIES . " p2c
                   WHERE p.pages_id = pd.pages_id
                    and pd.language_id ='" . (int)$languages_id . "'
                    and p.pages_id = p2c.pages_id
                    and p.pages_status = '1'
                    and p.pages_in_menu = '1'
                                    and (p2c.categories_id IN (" . $top_cats . ") OR p2c.categories_id = " . $currentCD . ")
                                ORDER BY sort";

    $listing_query1 = tep_db_query($listing_sql1);
    $listing_check = tep_db_num_rows($listing_query1);


    $this_listing_array = array();
    if ($listing_check > 0) {
    while ($listing_result = tep_db_fetch_array($listing_query1)) {
	     $cnt_row++;
          if($listing_result['type'] == 'c') {
        $this_listing_array[$listing_result['ID']] = array('name' => $listing_result['name'],
                                              'ID' => $listing_result['ID'],
                                              'parentID' => $listing_result['parentID'],
                                              'sort' => $listing_result['sort'],
                                              'image' => $listing_result['image'],
                                              'type' => $listing_result['type'],
                                                                  'url' => $listing_result['url'],
                                                                  'target' => $listing_result['target'],
                                                                  'append' => $listing_result['append'],
                                                                  'view' => $listing_result['view'],
                                                                  'list_columns' => $listing_result['list_columns']
                                             );
      } else {
	    $nk = $hi_cat+$listing_result['ID'];
        $this_listing_array[$nk] = array('name' => $listing_result['name'],
                                              'ID' => $listing_result['ID'],
                                              'parentID' => $listing_result['parentID'],
                                              'sort' => $listing_result['sort'],
                                              'image' => $listing_result['image'],
                                              'type' => $listing_result['type'],
                                                                  'url' => $listing_result['url'],
                                                                  'target' => $listing_result['target'],
                                                                  'append' => $listing_result['append'],
                                                                  'view' => $listing_result['view'],
                                                                  'list_columns' => $listing_result['list_columns']
                                                                );
      }
        } // end while
    }

$top_key = '1';
$nav_pages = '';

  function generate_menu_pages_li($parentID)
{

	$has_childs = false;
	global $this_listing_array,$CDpath_array,$currentCD,$top_key,$pID,$nav_pages;

 foreach($this_listing_array as $key => $value){

		if ($value['parentID'] == $parentID)
		{
			if ($has_childs === false)
			{
				$has_childs = true;
				if($top_key == '1'){
				$nav_pages .= '<ul class="box-information_pages-ul list-unstyled list-indent-large menu" style="padding-left:8px;">'. "\n";
				$top_key = '';
				}else{
				$nav_pages .= '<ul style="padding-left:23px;">'. "\n";
				}
			}
			 $current_page = '';
			 if(isset($CDpath_array) && $value['type'] == 'c' ){
			 if(in_array($value['ID'], $CDpath_array)){$current_page = 'class="parent"';}
			 if($value['ID'] == $currentCD && $pID == '0'){$current_page = 'class="selected"';}
			 }



			 if ($value['type'] == 'c') {
               $id = cre_get_cds_category_path($value['ID']);
               if ($value['url'] != '') {
                 $separator = (strpos($value['url'], '?')) ? '&amp;' : '?';
                 $li_link = ($value['append'] == true) ? $value['url'] . $separator . 'CDpath=' . $id : $value['url'];
                 $li_target = ($value['target'] != '') ? ' target="' . $value['target'] . '" ' : '';
             } else {
                 $li_link = tep_href_link(FILENAME_PAGES, 'CDpath=' . $id);
             }
             } else {
			    if($pID == $value['ID']){$current_page = 'class="selected"';}
                $li_link = tep_href_link(FILENAME_PAGES, 'pID=' . $value['ID'] . '&amp;CDpath=' . cre_get_cds_page_path($value['ID']));

           }
		    if($value['url']){
			$nav_pages .= '<li style="list-style:none"><a href="' . $li_link . '" ' . $li_target . $current_page . '>' . $value['name'] . '</a>';
			}else{
			$nav_pages .= '<li style="list-style:none"><a href="'. $li_link .'" ' . $current_page . '>' . $value['name'] . '</a>';
			}

			generate_menu_pages_li($key);
			$nav_pages .= '</li>'. "\n";
		}
	}
	if ($has_childs === true) $nav_pages .= '</ul>'. "\n";
}
generate_menu_pages_li(0);
echo $nav_pages
    ?>
</div>
<!-- pages_ul_eof //-->