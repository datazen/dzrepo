<?php
/*
  /catalog/includes/header_tags.php
  Add META TAGS and Modify TITLE
*/


require(DIR_WS_LANGUAGES . $language . '/' . 'header_tags.php');

$tags_array = array();

// Define specific settings per page:
switch (true) {
  // ALLPRODS.PHP
  case (strstr($PHP_SELF,FILENAME_ALLPRODS) ):
    if ($current_category_id != 0) {
      $the_category_query = tep_db_query("select cd.categories_name from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . $current_category_id . "' and cd.categories_id = '" . $current_category_id . "' and cd.language_id = '" . $languages_id . "'");
      $the_category = tep_db_fetch_array($the_category_query);
    } else {
      $the_category['categories_name'] = '';
    }

    if ( isset($_GET['manufacturers_id']) ) {
      $the_manufacturers_query= tep_db_query("select manufacturers_name from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "'");
      $the_manufacturers = tep_db_fetch_array($the_manufacturers_query);
    } else {
      $the_manufacturers['manufacturers_name'] = '';
    }

    if (HTDA_ALLPRODS_ON=='1') {
      $tags_array['desc']= HEAD_DESC_TAG_ALLPRODS . ' ' . HEAD_DESC_TAG_ALL;
    } else {
      $tags_array['desc']= HEAD_DESC_TAG_ALLPRODS;
    }

    if (HTKA_ALLPRODS_ON=='1') {
      $tags_array['keywords']= HEAD_KEY_TAG_ALL . ' ' . HEAD_KEY_TAG_ALLPRODS;
    } else {
      $tags_array['keywords']= HEAD_KEY_TAG_ALLPRODS;
    }

    if (HTTA_ALLPRODS_ON=='1') {
      $tags_array['title']= HEAD_TITLE_TAG_ALLPRODS . ' ' . HEAD_TITLE_TAG_ALL . " " . $the_category['categories_name'] . $the_manufacturers['manufacturers_name'];
    } else {
      $tags_array['title']= HEAD_TITLE_TAG_ALLPRODS;
    }
    break;
  
  // INDEX.PHP
  case (strstr($PHP_SELF,FILENAME_DEFAULT) ):
  
    $showCatTags = false;
    
    if ($category_depth == 'nested' || $category_depth == 'products') {
      $the_category_query = tep_db_query("select categories_name as name, categories_head_title_tag as htc_title_tag, categories_head_desc_tag as htc_desc_tag, categories_head_keywords_tag as htc_keywords_tag from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$current_category_id . "' and language_id = '" . (int)$languages_id . "'");
      $showCatTags = true;
    } else if (isset($_GET['manufacturers_id'])) { 
      $the_category_query= tep_db_query("select m.manufacturers_name as name, mi.manufacturers_htc_title_tag as htc_title_tag, mi.manufacturers_htc_desc_tag as htc_desc_tag, mi.manufacturers_htc_keywords_tag as htc_keywords_tag from " . TABLE_MANUFACTURERS . " m LEFT JOIN " . TABLE_MANUFACTURERS_INFO . " mi on m.manufacturers_id = mi.manufacturers_id where m.manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "' and mi.languages_id = '" . (int)$languages_id . "'");
      $showCatTags = true;
    } else {
      $the_category_query = tep_db_query("select categories_name as name, categories_head_title_tag as htc_title_tag, categories_head_desc_tag as htc_desc_tag, categories_head_keywords_tag as htc_keywords_tag from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$current_category_id . "' and language_id = '" . (int)$languages_id . "'");
    } 

    $the_category = tep_db_fetch_array($the_category_query);
    
    if (HTDA_DEFAULT_ON=='1') {
      if ($showCatTags == true) {
         if (HTTA_CAT_DEFAULT_ON=='1') {
           $tags_array['desc']= $the_category['htc_desc_tag'] . ' ' . HEAD_DESC_TAG_DEFAULT . ' ' . HEAD_DESC_TAG_ALL;
         } else {
           $tags_array['desc']= $the_category['htc_desc_tag'] . ' ' . HEAD_DESC_TAG_ALL;
         }
      } else {
        $tags_array['desc']= HEAD_DESC_TAG_DEFAULT . ' ' . HEAD_DESC_TAG_ALL;
      }
    } else {
      if ($showCatTags == true) {
         if (HTTA_CAT_DEFAULT_ON=='1') {
           $tags_array['desc']= $the_category['htc_desc_tag'] . ' ' . HEAD_DESC_TAG_DEFAULT;
         } else {
           $tags_array['desc']= $the_category['htc_desc_tag'];
         }
      } else {
        $tags_array['desc']= HEAD_DESC_TAG_DEFAULT;
      }  
    }

    if (HTKA_DEFAULT_ON=='1') {
      if ($showCatTags == true) {
          if (HTTA_CAT_DEFAULT_ON=='1') {
            $tags_array['keywords']= $the_category['htc_keywords_tag'] . ', ' . HEAD_KEY_TAG_ALL . ' ' . HEAD_KEY_TAG_DEFAULT;
          } else {  
            $tags_array['keywords']= $the_category['htc_keywords_tag'] .  ', ' . HEAD_KEY_TAG_DEFAULT;
          }
      } else {
        $tags_array['keywords']= HEAD_KEY_TAG_ALL . ', ' . HEAD_KEY_TAG_DEFAULT;
      }  
    } else {
      if ($showCatTags == true) {
         if (HTTA_CAT_DEFAULT_ON=='1') {
           $tags_array['keywords']= $the_category['htc_keywords_tag'] . ', ' . HEAD_KEY_TAG_DEFAULT;
         } else {
           $tags_array['keywords']= $the_category['htc_keywords_tag'];
         }  
      } else {
         $tags_array['keywords']= HEAD_KEY_TAG_DEFAULT;
      }
    }

    if (HTTA_DEFAULT_ON=='1') {
      if ($showCatTags == true) {
        if (HTTA_CAT_DEFAULT_ON=='1') {
          $tags_array['title']= (tep_not_null($the_category['htc_title_tag']) ? $the_category['htc_title_tag'] : $the_category['name']) .' '.  HEAD_TITLE_TAG_DEFAULT . " " .  $the_category['manufacturers_name'] . ' - ' . HEAD_TITLE_TAG_ALL;
        } else {
          $tags_array['title']= (tep_not_null($the_category['htc_title_tag']) ? $the_category['htc_title_tag'] : $the_category['name']) .' '.  $the_category['manufacturers_htc_title_tag'] . ' - ' . HEAD_TITLE_TAG_ALL;
        }
      } else {
        $tags_array['title']= HEAD_TITLE_TAG_DEFAULT . " " . $the_category['name'] . $the_category['manufacturers_htc_title_tag'] . ' - ' . HEAD_TITLE_TAG_ALL;
      }
    } else {
      if ($showCatTags == true) {
        if (HTTA_CAT_DEFAULT_ON=='1') {
          $tags_array['title']= (tep_not_null($the_category['htc_title_tag']) ? $the_category['htc_title_tag'] : $the_category['name']) . ' ' . HEAD_TITLE_TAG_DEFAULT;
        } else {
          $tags_array['title']= (tep_not_null($the_category['htc_title_tag']) ? $the_category['htc_title_tag'] : $the_category['name']);
        } 
      } else {
        $tags_array['title']= HEAD_TITLE_TAG_DEFAULT;
      }  
    }

    break;

// PRODUCT_INFO.PHP
  case (strstr($PHP_SELF,FILENAME_PRODUCT_INFO) ):
//    $the_product_info_query = tep_db_query("select p.products_id, pd.products_name, pd.products_description, pd.products_head_title_tag, pd.products_head_keywords_tag, pd.products_head_desc_tag, p.products_model, p.products_quantity, p.products_image, pd.products_url, p.products_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . $_GET['products_id'] . "' and pd.products_id = '" . $_GET['products_id'] . "'");
    $the_product_info_query = tep_db_query("select pd.language_id, p.products_id, pd.products_name, pd.products_description, pd.products_head_title_tag, pd.products_head_keywords_tag, pd.products_head_desc_tag, p.products_model, p.products_quantity, p.products_image, pd.products_url, p.products_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . (int)$_GET['products_id'] . "' and pd.products_id = '" . (int)$_GET['products_id'] . "'" . " and pd.language_id ='" .  (int)$languages_id . "'");
    $the_product_info = tep_db_fetch_array($the_product_info_query);
    
    if (HTPA_DEFAULT_ON=='1') 
    {
      $the_category_query = tep_db_query("select c.categories_name as cat_name from " . TABLE_CATEGORIES_DESCRIPTION . " c, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where c.categories_id = p2c.categories_id and p2c.products_id = '" . (int)$the_product_info['products_id'] . "' and language_id = '" . (int)$languages_id . "'");
      $cat = tep_db_fetch_array($the_category_query);
    }
    
    if (empty($the_product_info['products_head_desc_tag'])) {
      if (HTPA_DEFAULT_ON=='1') {
        $tags_array['desc'] = $cat['cat_name'] . ' - ';         //display cat name too
      }     
      if (HTTA_CAT_PRODUCT_DEFAULT_ON=='1') {                             
        $tags_array['desc'] .= HEAD_DESC_TAG_PRODUCT_INFO;              
      } 
      if (HTDA_PRODUCT_INFO_ON=='1' || empty($tags_array['desc'])) {
        $tags_array['desc'].= HEAD_DESC_TAG_ALL;
      }       
    } else {    
      $tags_array['desc']= $the_product_info['products_head_desc_tag'];
      if (HTPA_DEFAULT_ON=='1') {
        $tags_array['desc'] .= $cat['cat_name'] . ' - ';         //display cat name too
      }
      if (HTTA_CAT_PRODUCT_DEFAULT_ON=='1') {
        $tags_array['desc'] .= ' ' . HEAD_DESC_TAG_PRODUCT_INFO;
      }
      if ( HTDA_PRODUCT_INFO_ON=='1' ) {
        $tags_array['desc'] .= ' ' . HEAD_DESC_TAG_ALL;
      }
    }
     
    if (empty($the_product_info['products_head_keywords_tag'])) {
      if (HTPA_DEFAULT_ON=='1') {
        $tags_array['keywords'] = $cat['cat_name'] . ' , ';         //display cat name too
      }
      if (HTTA_CAT_PRODUCT_DEFAULT_ON=='1') {                             
        $tags_array['keywords'] .= HEAD_KEY_TAG_PRODUCT_INFO;              
      } 
      if ( HTKA_PRODUCT_INFO_ON=='1' || empty($tags_array['keywords'])) {
        $tags_array['keywords'].= HEAD_KEY_TAG_ALL;               
      }       
    } else {    
      $tags_array['keywords']= $the_product_info['products_head_keywords_tag'];
      if (HTPA_DEFAULT_ON=='1') {
        $tags_array['keywords'] .= $cat['cat_name'] . ' , ';         //display cat name too
      }
      if (HTTA_CAT_PRODUCT_DEFAULT_ON=='1') {
        $tags_array['keywords'] .= ' ' . HEAD_KEY_TAG_PRODUCT_INFO;
      }
      if ( HTKA_PRODUCT_INFO_ON=='1' ) {
        $tags_array['keywords'] .= ' ' . HEAD_KEY_TAG_ALL;
      }
    }

    if (empty($the_product_info['products_head_title_tag'])) {   //if not HTC title in product
      if (HTPA_DEFAULT_ON=='1') {
        $tags_array['title'] = $cat['cat_name'] . ' - ';         //display cat name too
      }
      if (HTTA_CAT_PRODUCT_DEFAULT_ON=='1') {                    //if HTCA checked
        $tags_array['title']= HEAD_TITLE_TAG_PRODUCT_INFO;       //show title for this section 
      }  
      if ( HTTA_PRODUCT_INFO_ON=='1' || empty($tags_array['title'])) { //if default switch on or no entry
        $tags_array['title'].= HEAD_TITLE_TAG_ALL;               //include the default text
      }       
    } else {    
      if (HTPA_DEFAULT_ON=='1') {
        $tags_array['title'] = $cat['cat_name'] . ' - ';
      }

      $tags_array['title'] .= clean_html_comments($the_product_info['products_head_title_tag']);

      if (HTTA_CAT_PRODUCT_DEFAULT_ON=='1') {
        $tags_array['title'] .= ' ' . HEAD_TITLE_TAG_PRODUCT_INFO;
      }
      if ( HTTA_PRODUCT_INFO_ON=='1' ) {
        $tags_array['title'] .= ' ' . HEAD_TITLE_TAG_ALL;
      }
    }

    break;


// PRODUCTS_NEW.PHP
  case (strstr($PHP_SELF,FILENAME_PRODUCTS_NEW) ):
    if ( HEAD_DESC_TAG_WHATS_NEW!='' ) {
      if ( HTDA_WHATS_NEW_ON=='1' ) {
        $tags_array['desc']= HEAD_DESC_TAG_WHATS_NEW . ' ' . HEAD_DESC_TAG_ALL;
      } else {
        $tags_array['desc']= HEAD_DESC_TAG_WHATS_NEW;
      }
    } else {
      $tags_array['desc']= HEAD_DESC_TAG_ALL;
    }

    if ( HEAD_KEY_TAG_WHATS_NEW!='' ) {
      if ( HTKA_WHATS_NEW_ON=='1' ) {
        $tags_array['keywords']= HEAD_KEY_TAG_WHATS_NEW . ' ' . HEAD_KEY_TAG_ALL;
      } else {
        $tags_array['keywords']= HEAD_KEY_TAG_WHATS_NEW;
      }
    } else {
      $tags_array['keywords']= HEAD_KEY_TAG_ALL;
    }

    if ( HEAD_TITLE_TAG_WHATS_NEW!='' ) {
      if ( HTTA_WHATS_NEW_ON=='1' ) {
        $tags_array['title']= HEAD_TITLE_TAG_WHATS_NEW . ' ' . HEAD_TITLE_TAG_ALL;
      } else {
        $tags_array['title']= HEAD_TITLE_TAG_WHATS_NEW;
      }
    } else {
      $tags_array['title']= HEAD_TITLE_TAG_ALL;
    }

    break;


// SPECIALS.PHP
  case (strstr($PHP_SELF,FILENAME_SPECIALS) ): 
    if ( HEAD_DESC_TAG_SPECIALS!='' ) {
      if ( HTDA_SPECIALS_ON=='1' ) {
        $tags_array['desc']= HEAD_DESC_TAG_SPECIALS . ' ' . HEAD_DESC_TAG_ALL;
      } else {
        $tags_array['desc']= HEAD_DESC_TAG_SPECIALS;
      }
    } else {
      $tags_array['desc']= HEAD_DESC_TAG_ALL;
    }

    if ( HEAD_KEY_TAG_SPECIALS=='' ) {
      // Build a list of ALL specials product names to put in keywords
      $new = tep_db_query("select p.products_id, pd.products_name, p.products_price, p.products_tax_class_id, p.products_image, s.specials_new_products_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_SPECIALS . " s where p.products_status = '1' and s.products_id = p.products_id and p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' and s.status = '1' order by s.specials_date_added DESC ");
      $row = 0;
      $the_specials='';
      while ($new_values = tep_db_fetch_array($new)) {
        $the_specials .= clean_html_comments($new_values['products_name']) . ', ';
      }
      if ( HTKA_SPECIALS_ON=='1' ) {
        $tags_array['keywords']= $the_specials . ' ' . HEAD_KEY_TAG_ALL;
      } else {
        $tags_array['keywords']= $the_specials;
      }
    } else {
       if ( HTKA_SPECIALS_ON=='1' ) {
        $tags_array['keywords']= HEAD_KEY_TAG_SPECIALS . ' ' . HEAD_KEY_TAG_ALL;
       } else {
        $tags_array['keywords']= HEAD_KEY_TAG_SPECIALS;  
       }
    }

    if ( HEAD_TITLE_TAG_SPECIALS!='' ) {
      if ( HTTA_SPECIALS_ON=='1' ) {
        $tags_array['title']= HEAD_TITLE_TAG_SPECIALS . ' ' . HEAD_TITLE_TAG_ALL;
      } else {
        $tags_array['title']= HEAD_TITLE_TAG_SPECIALS;
      }
    } else {
      $tags_array['title']= HEAD_TITLE_TAG_ALL;
    }

    break;


// PRODUCTS_REVIEWS_INFO.PHP and PRODUCTS_REVIEWS.PHP
    case(((basename($PHP_SELF)==FILENAME_PRODUCT_REVIEWS) or (basename($PHP_SELF)==FILENAME_PRODUCT_REVIEWS_INFO)) && isset($_GET['reviews_id'])):
    if ( HEAD_DESC_TAG_PRODUCT_REVIEWS_INFO=='' ) {
      if ( HTDA_PRODUCT_REVIEWS_INFO_ON=='1' ) {
        $tags_array['desc']= tep_get_header_tag_products_desc($_GET['reviews_id']) . ' ' . HEAD_DESC_TAG_ALL;
      } else {
        $tags_array['desc']= tep_get_header_tag_products_desc($_GET['reviews_id']);
      }
    } else {
      $tags_array['desc']= HEAD_DESC_TAG_PRODUCT_REVIEWS_INFO;
    }

    if ( HEAD_KEY_TAG_PRODUCT_REVIEWS_INFO=='' ) {
      if ( HTKA_PRODUCT_REVIEWS_INFO_ON=='1' ) {
        $tags_array['keywords']= tep_get_header_tag_products_keywords($_GET['reviews_id']) . ' ' . HEAD_KEY_TAG_ALL;
      } else {
        $tags_array['keywords']= tep_get_header_tag_products_keywords($_GET['reviews_id']);
      }
    } else {
      $tags_array['keywords']= HEAD_KEY_TAG_PRODUCT_REVIEWS_INFO;
    }

    if ( HEAD_TITLE_TAG_PRODUCT_REVIEWS_INFO=='' ) {
      if ( HTTA_PRODUCT_REVIEWS_INFO_ON=='1' ) {
        $tags_array['title']= ' Reviews: ' . tep_get_header_tag_products_title($_GET['reviews_id']) . HEAD_TITLE_TAG_ALL;
      } else {
        $tags_array['title']= tep_get_header_tag_products_title($_GET['reviews_id']);
      }
    } else {
      $tags_array['title']= HEAD_TITLE_TAG_PRODUCT_REVIEWS_INFO;
    }
    break;

// PRODUCTS_REVIEWS_WRITE.PHP
    case((basename($PHP_SELF)==FILENAME_PRODUCT_REVIEWS_WRITE) && isset($_GET['reviews_id'])):
    if ( HEAD_DESC_TAG_PRODUCT_REVIEWS_WRITE=='' ) {
      if ( HTDA_PRODUCT_REVIEWS_WRITE_ON=='1' ) {
        $tags_array['desc']= tep_get_header_tag_products_desc($_GET['reviews_id']) . ' ' . HEAD_DESC_TAG_ALL;
      } else {
        $tags_array['desc']= tep_get_header_tag_products_desc($_GET['reviews_id']);
      }
    } else {
      $tags_array['desc']= HEAD_DESC_TAG_PRODUCT_REVIEWS_WRITE;
    }

    if ( HEAD_KEY_TAG_PRODUCT_REVIEWS_WRITE=='' ) {
      if ( HTKA_PRODUCT_REVIEWS_WRITE_ON=='1' ) {
        $tags_array['keywords']= tep_get_header_tag_products_keywords($_GET['reviews_id']) . ' ' . HEAD_KEY_TAG_ALL;
      } else {
        $tags_array['keywords']= tep_get_header_tag_products_keywords($_GET['reviews_id']);
      }
    } else {
      $tags_array['keywords']= HEAD_KEY_TAG_PRODUCT_REVIEWS_WRITE;
    }

    if ( HEAD_TITLE_TAG_PRODUCT_REVIEWS_WRITE=='' ) {
      if ( HTTA_PRODUCT_REVIEWS_WRITE_ON=='1' ) {
        $tags_array['title']= ' Reviews: ' . tep_get_header_tag_products_title($_GET['reviews_id']) . HEAD_TITLE_TAG_ALL;
      } else {
        $tags_array['title']= tep_get_header_tag_products_title($_GET['reviews_id']);
      }
    } else {
      $tags_array['title']= HEAD_TITLE_TAG_PRODUCT_REVIEWS_WRITE;
    }
    break;
    
    // ARTICLES.PHP
    case (strstr($PHP_SELF,'articles.php') &! strstr($PHP_SELF,'new_articles.php')):
    $the_topic_query = tep_db_query("select td.topics_name from " . TABLE_TOPICS . " t, " . TABLE_TOPICS_DESCRIPTION . " td where t.topics_id = '" . (int)$current_topic_id . "' and td.topics_id = '" . (int)$current_topic_id . "' and td.language_id = '" . (int)$languages_id . "'");
    $the_topic = tep_db_fetch_array($the_topic_query);

    $the_authors_query= tep_db_query("select authors_name from " . TABLE_AUTHORS . " where authors_id = '" . (isset($_GET['authors_id']) ? (int)$_GET['authors_id'] : 0) . "'");
    $the_authors = tep_db_fetch_array($the_authors_query);

    if (HTDA_ARTICLES_ON=='1') {
      $tags_array['desc']= HEAD_DESC_TAG_ARTICLES . '. ' . HEAD_DESC_TAG_ALL;
    } else {
      $tags_array['desc']= HEAD_DESC_TAG_ARTICLES;
    }

    if (HTKA_ARTICLES_ON=='1') {

      if (tep_not_null($the_topic['topics_name'])) {
        $tags_array['keywords'] .= $the_topic['topics_name'];
      } else {
        if (tep_not_null($the_authors['authors_name'])) {
          $tags_array['keywords'] .= $the_authors['authors_name'];
        }
      }

      $tags_array['keywords'] = HEAD_KEY_TAG_ARTICLES . ', ' . $tags_array['keywords'] . ', ' . HEAD_KEY_TAG_ALL;

    } else {
      $tags_array['keywords']= HEAD_KEY_TAG_ARTICLES;
    }

    if (HTTA_ARTICLES_ON=='1') {
      $tags_array['title']= HEAD_TITLE_TAG_ALL . ' - ' . HEAD_TITLE_TAG_ARTICLES;

      if (tep_not_null($the_topic['topics_name'])) {
        $tags_array['title'] .= ' - ' . $the_topic['topics_name'];
      } else {
        if (tep_not_null($the_authors['authors_name'])) {
          $tags_array['title'] .= TEXT_BY . $the_authors['authors_name'];
        }
      }

    } else {
      $tags_array['title']= HEAD_TITLE_TAG_ARTICLES;
    }

    break;

// ARTICLE_INFO.PHP
  case (strstr($PHP_SELF,'article_info.php') ):
    $the_article_info_query = tep_db_query("select ad.language_id, a.articles_id, ad.articles_name, ad.articles_description, ad.articles_head_title_tag, ad.articles_head_keywords_tag, ad.articles_head_desc_tag, ad.articles_url, a.articles_date_added, a.articles_date_available, a.authors_id from " . TABLE_ARTICLES . " a, " . TABLE_ARTICLES_DESCRIPTION . " ad where a.articles_id = '" . (int)$_GET['articles_id'] . "' and ad.articles_id = '" . (int)$_GET['articles_id'] . "'" . " and ad.language_id ='" .  (int)$languages_id . "'");
    $the_article_info = tep_db_fetch_array($the_article_info_query);

    if (empty($the_article_info['articles_head_desc_tag'])) {
      $tags_array['desc']= NAVBAR_TITLE . '. ' . HEAD_DESC_TAG_ALL;
    } else {
      if ( HTDA_ARTICLE_INFO_ON=='1' ) {
        $tags_array['desc']= $the_article_info['articles_head_desc_tag'] . ' ' . HEAD_DESC_TAG_ALL;
      } else {
        $tags_array['desc']= $the_article_info['articles_head_desc_tag'];
      }
    }

    if (empty($the_article_info['articles_head_keywords_tag'])) {
      $tags_array['keywords']= NAVBAR_TITLE . ', ' . HEAD_KEY_TAG_ALL;
    } else {
      if ( HTKA_ARTICLE_INFO_ON=='1' ) {
        $tags_array['keywords']= $the_article_info['articles_head_keywords_tag'] . ', ' . HEAD_KEY_TAG_ALL;
      } else {
        $tags_array['keywords']= $the_article_info['articles_head_keywords_tag'];
      }
    }

    if (empty($the_article_info['articles_head_title_tag'])) {
      $tags_array['title']= HEAD_TITLE_TAG_ALL . ' - ' . NAVBAR_TITLE;
    } else {
      if ( HTTA_ARTICLE_INFO_ON=='1' ) {
        $tags_array['title']= HEAD_TITLE_TAG_ALL . ' - ' .  HEAD_TITLE_TAG_ARTICLE_INFO . ' - ' . $topics['topics_name'] . $authors['authors_name'] . ' - ' . clean_html_comments($the_article_info['articles_head_title_tag']);
      } else {
        $tags_array['title']= clean_html_comments($the_article_info['articles_head_title_tag']);
      }
    }

    break;

// ARTICLES_NEW.PHP
  case (strstr($PHP_SELF,'articles_new.php') ):
    if ( HEAD_DESC_TAG_ARTICLES_NEW!='' ) {
      if ( HTDA_ARTICLES_NEW_ON=='1' ) {
        $tags_array['desc']= HEAD_DESC_TAG_ARTICLES_NEW . '. ' . HEAD_DESC_TAG_ALL;
      } else {
        $tags_array['desc']= HEAD_DESC_TAG_ARTICLES_NEW;
      }
    } else {
      $tags_array['desc']= NAVBAR_TITLE . '. ' . HEAD_DESC_TAG_ALL;
    }

    if ( HEAD_KEY_TAG_ARTICLES_NEW=='' ) {
      // Build a list of ALL new article names to put in keywords
      $articles_new_array = array();
      $articles_new_query_raw = "select ad.articles_name
                                 from " . TABLE_ARTICLES . " a,
                                      " . TABLE_AUTHORS . " au,
                                      " . TABLE_ARTICLES_DESCRIPTION . " ad
                                 where a.articles_status = '1'
                                   and a.authors_id = au.authors_id
                                   and a.articles_id = ad.articles_id
                                   and ad.language_id = '" . (int)$languages_id . "'
                                 order by a.articles_date_added DESC, ad.articles_name";
      $articles_new_split = new splitPageResults($articles_new_query_raw, MAX_NEW_ARTICLES_PER_PAGE);
      $articles_new_query = tep_db_query($articles_new_split->sql_query);

      $row = 0;
      $the_new_articles='';
      while ($articles_new = tep_db_fetch_array($articles_new_query)) {
        $the_new_articles .= clean_html_comments($articles_new['articles_name']) . ', ';
      }
      if ( HTKA_ARTICLES_NEW_ON=='1' ) {
        $tags_array['keywords']= NAVBAR_TITLE . ', ' . $the_new_articles . ', ' . HEAD_KEY_TAG_ALL;
      } else {
        $tags_array['keywords']= NAVBAR_TITLE . ', ' . $the_new_articles;
      }
    } else {
      $tags_array['keywords']= HEAD_KEY_TAG_ARTICLES_NEW . ', ' . HEAD_KEY_TAG_ALL;
    }

    if ( HEAD_TITLE_TAG_ARTICLES_NEW!='' ) {
      if ( HTTA_ARTICLES_NEW_ON=='1' ) {
        $tags_array['title']= HEAD_TITLE_TAG_ALL . ' - ' . HEAD_TITLE_TAG_ARTICLES_NEW;
      } else {
        $tags_array['title']= HEAD_TITLE_TAG_ARTICLES_NEW;
      }
    } else {
      $tags_array['title']= HEAD_TITLE_TAG_ALL . ' - ' . NAVBAR_TITLE;
    }

    break;

// ARTICLES_REVIEWS_INFO.PHP and ARTICLES_REVIEWS.PHP
  case ( (strstr($PHP_SELF,'article_reviews_info.php') or strstr($PHP_SELF,'article_reviews.php')) && isset($_GET['reviews_id']) ):
    if ( HEAD_DESC_TAG_ARTICLE_REVIEWS_INFO=='' ) {
      if ( HTDA_ARTICLE_REVIEWS_INFO_ON=='1' ) {
        $tags_array['desc']= NAVBAR_TITLE . '. ' . tep_get_header_tag_articles_desc($_GET['reviews_id']) . ' ' . HEAD_DESC_TAG_ALL;
      } else {
        $tags_array['desc']= NAVBAR_TITLE . '. ' . tep_get_header_tag_articles_desc($_GET['reviews_id']);
      }
    } else {
      $tags_array['desc']= HEAD_DESC_TAG_ARTICLE_REVIEWS_INFO;
    }

    if ( HEAD_KEY_TAG_ARTICLE_REVIEWS_INFO=='' ) {
      if ( HTKA_ARTICLE_REVIEWS_INFO_ON=='1' ) {
        $tags_array['keywords']= NAVBAR_TITLE . ', ' . tep_get_header_tag_articles_keywords($_GET['reviews_id']) . ', ' . HEAD_KEY_TAG_ALL;
      } else {
        $tags_array['keywords']= NAVBAR_TITLE . ', ' . tep_get_header_tag_articles_keywords($_GET['reviews_id']);
      }
    } else {
      $tags_array['keywords']= HEAD_KEY_TAG_ARTICLE_REVIEWS_INFO;
    }

    if ( HEAD_TITLE_TAG_ARTICLE_REVIEWS_INFO=='' ) {
      if ( HTTA_ARTICLE_REVIEWS_INFO_ON=='1' ) {
        $tags_array['title']= HEAD_TITLE_TAG_ALL . ' - ' . HEADING_TITLE . tep_get_header_tag_articles_title($_GET['reviews_id']);
      } else {
        $tags_array['title']= tep_get_header_tag_articles_title($_GET['reviews_id']);
      }
    } else {
      $tags_array['title']= HEAD_TITLE_TAG_ARTICLE_REVIEWS_INFO;
    }
    break;

// pages.php
  case ( strstr($PHP_SELF,FILENAME_PAGES) && (isset($_GET['pID']) && tep_not_null($_GET['pID'])) ):
    $the_pages_info_query = tep_db_query("select pages_meta_title, pages_meta_keywords, pages_meta_description from " . TABLE_PAGES_DESCRIPTION . " where pages_id = '" . (int)$_GET['pID'] . "' and language_id ='" .  (int)$languages_id . "'");
    $the_pages_info = tep_db_fetch_array($the_pages_info_query);

    if (empty($the_pages_info['pages_meta_description'])) {
      $tags_array['desc']= NAVBAR_TITLE . '. ' . HEAD_DESC_TAG_ALL;
    } else {
      if ( HTDA_ARTICLE_INFO_ON=='1' ) {
        $tags_array['desc']= $the_pages_info['pages_meta_description'] . ' ' . HEAD_DESC_TAG_ALL;
      } else {
        $tags_array['desc']= $the_pages_info['pages_meta_description'];
      }
    }

    if (empty($the_pages_info['pages_meta_keywords'])) {
      $tags_array['keywords']= NAVBAR_TITLE . ', ' . HEAD_KEY_TAG_ALL;
    } else {
      if ( HTKA_ARTICLE_INFO_ON=='1' ) {
        $tags_array['keywords']= $the_pages_info['pages_meta_keywords'] . ', ' . HEAD_KEY_TAG_ALL;
      } else {
        $tags_array['keywords']= $the_pages_info['pages_meta_keywords'];
      }
    }

    if (empty($the_pages_info['pages_meta_title'])) {
      $tags_array['title']= HEAD_TITLE_TAG_ALL . ' - ' . NAVBAR_TITLE;
    } else {
      if ( HTTA_ARTICLE_INFO_ON=='1' ) {
        $tags_array['title']= HEAD_TITLE_TAG_ALL . ' - ' .  clean_html_comments($the_pages_info['pages_meta_title']);
      } else {
        $tags_array['title']= clean_html_comments($the_pages_info['pages_meta_title']);
      }
    }

    break;
    
    
// ALL OTHER PAGES NOT DEFINED ABOVE
  default:
    $tags_array['desc'] = HEAD_DESC_TAG_ALL;
    $tags_array['keywords'] = HEAD_KEY_TAG_ALL;
    $tags_array['title'] = HEAD_TITLE_TAG_ALL;
    break;
}

//RCI start
echo $cre_RCI->get('headertags', 'addswitch');
//RCI end

$favicon_path = cre_site_branding_rspv('favicon');
echo ' <meta http-equiv="Content-Type" content="text/html; charset=' . CHARSET  . '">'."\n";
echo ' <title>' . $tags_array['title'] . '</title>' . "\n";
echo ' <meta name="Description" content="' . $tags_array['desc'] . '">' . "\n";
echo ' <meta name="Keywords" content="' . $tags_array['keywords'] . '">' . "\n";
echo ' <meta name="robots" content="noodp">' . "\n";
echo ' <meta name="revisit-after" content="30 days">' . "\n";
echo ' <meta name="generator" content="' . PROJECT_VERSION . '">' . "\n";
echo ' <link rel="shortcut icon" href="'. $favicon_path .'" />' . "\n";
echo ' <link rel="icon" href="'. $favicon_path .'" type="image/vnd.microsoft.icon" />' . "\n";
echo ' <link rel="icon" type="image/png" href="'. $favicon_path .'" />' . "\n";
echo ' <!-- EOF: Generated Meta Tags -->' . "\n";
?>
