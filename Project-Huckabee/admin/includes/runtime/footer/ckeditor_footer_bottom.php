<?php
/*
  $Id: ckeditor_footer_bottom.php,v 1.0.0.0 2011/07/20 13:41:11 wa4u Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/

    global $current_page, $category_elements, $products_elements, $pages_blurb_elements, $pages_body_elements, $category_blurb_elements, $articles_editor_elements, $authors_description, $editor_elements;

    switch($current_page){
        case FILENAME_MAIL:
        echo tep_insert_ckeditor('message');
        break;
        
        case FILENAME_FAQ_MANAGER:
        if(isset($_GET['faq_action']) && $_GET['faq_action'] == 'Added'){
            echo tep_insert_ckeditor('question', 'BLURB', '200');
            echo tep_insert_ckeditor('answer','BODY');
        }
        break;
        
        case FILENAME_DEFINE_MAINPAGE:
        echo tep_insert_ckeditor('file_contents', 'BODY');
        break;
        
        case FILENAME_ARTICLES:
        if(tep_not_null($articles_editor_elements) && ARTICLE_WYSIWYG_ENABLE == 'Enable'){
            $cpe = explode(',',$articles_editor_elements);
            foreach ($cpe as $id => $editor_id){
                if($editor_id != ''){
                    echo tep_insert_ckeditor($editor_id,'BODY');
                }
            }
        }
        break;
        
        case FILENAME_AUTHORS:
        if(tep_not_null($authors_description) ){
            $cpe = explode(',',$authors_description);
            foreach ($cpe as $id => $editor_id){
                if($editor_id != ''){
                    echo tep_insert_ckeditor($editor_id,'BODY');
                }
            }
        }
        break;
        
        case FILENAME_CATEGORIES:
        $category_page_elements = $products_elements . $category_elements;
        if(tep_not_null($category_page_elements)){
            $cpe = explode(',',$category_page_elements);
            foreach ($cpe as $id => $editor_id){
                if($editor_id != ''){
                    echo tep_insert_ckeditor($editor_id,'BODY');
                }
            }
        }
        break;
        
        
        case FILENAME_PAGES:
        if(tep_not_null($pages_blurb_elements) && CDS_WYSIWYG_ON_PAGE_BLURB == 'Enabled'){
            $cpe = explode(',',$pages_blurb_elements);
            foreach ($cpe as $id => $editor_id){
                if($editor_id != ''){
                    echo tep_insert_ckeditor($editor_id, 'BLURB', '200');
                }
            }
        }
        if(tep_not_null($pages_body_elements) && CDS_WYSIWYG_ON_PAGE_BODY == 'Enabled'){
            $cpe = explode(',',$pages_body_elements);
            foreach ($cpe as $id => $editor_id){
                if($editor_id != ''){
                    echo tep_insert_ckeditor($editor_id,'BODY');
                }
            }
        }
        
        if(tep_not_null($category_blurb_elements) && CDS_WYSIWYG_ON_CATEGORY_BLURB == 'Enabled'){
            $cpe = explode(',',$category_blurb_elements);
            foreach ($cpe as $id => $editor_id){
                if($editor_id != ''){
                    echo tep_insert_ckeditor($editor_id, 'BLURB', '200');
                }
            }
        }
        if(tep_not_null($category_elements) && CDS_WYSIWYG_ON_CATEGORY_BODY == 'Enabled'){
            $cpe = explode(',',$category_elements);
            foreach ($cpe as $id => $editor_id){
                if($editor_id != ''){
                    echo tep_insert_ckeditor($editor_id,'BODY');
                }
            }
        }
        
        break;
        
        case FILENAME_RETURNS_TEXT:
        if(tep_not_null($editor_elements) ){
            $cpe = explode(',',$editor_elements);
            foreach ($cpe as $id => $editor_id){
                if($editor_id != ''){
                    echo tep_insert_ckeditor($editor_id,'BLURB');
                }
            }
        }
        break;
        
        case FILENAME_NEWSLETTERS:
          if(HTML_WYSIWYG_DISABLE_NEWSLETTER == 'Enable'){
               echo tep_insert_ckeditor('content','BODY');
          }
        break;
    }
?>