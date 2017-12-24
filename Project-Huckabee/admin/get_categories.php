<?php 
include('includes/application_top.php');

    // Removes invalid XML
    if(!function_exists('cre_stripInvalidXml')){
        function cre_stripInvalidXml($string) {
            $string = str_replace(array("\t" , "\n", "\r"), ' ', $string);
            $string = tep_db_decoder($string);
            $string = cre_translate_unsafe($string);
            $string = htmlentities(html_entity_decode($string));
            $ret = '';
            $length = strlen($string);
            for ($i=0; $i < $length; $i++){
                $current = ord($string{$i});
                if (($current == 0x9) ||
                    ($current == 0xA) ||
                    ($current == 0xD) ||
                    (($current >= 0x20) && ($current <= 0xD7FF)) ||
                    (($current >= 0xE000) && ($current <= 0xFFFD)) ||
                    (($current >= 0x10000) && ($current <= 0x10FFFF))){
                        $ret .= chr($current);
                    } else {
                        $ret .= " ";
                    }
            }
            $string = trim($ret);
            $find = array('&reg;', '&copy;', '&trade;','&lt;','&gt;','&eacute;','&quot;');
            $replace = array('(r)', '(c)', '(tm)','<', '>','e','"');
            $string = str_replace($find, $replace, $string);
            
            return $string;
        }
    }
    
    if(!function_exists('cre_translate_unsafe')){
        function cre_translate_unsafe($string) {
            // using from seo.php
            // Convert special characters from European countries into the English alphabetic equivalent
            // Improved by Daniel S. Friehe
              $transforms = array('À'=>'A','Á'=>'A','Â'=>'A','Ã'=>'A','Ä'=>'Ae','Å'=>'A','Ç'=>'C','È'=>'E','É'=>'E','Ê'=>'E','Ë'=>'E','Ì'=>'I',
                                  'Í'=>'I','Î'=>'I','Ï'=>'I','Ñ'=>'N','Ò'=>'O','Ó'=>'O','Ô'=>'O','Õ'=>'O','Ö'=>'Oe','Ø'=>'O','Ù'=>'U','Ú'=>'U',
                                  'Û'=>'U','Ü'=>'Ue','Ý'=>'Y','à'=>'a','á'=>'a','â'=>'a','ã'=>'a','ä'=>'ae','å'=>'a','ç'=>'c','è'=>'e','é'=>'e',
                                  'ê'=>'e','ë'=>'e','ì'=>'i','í'=>'i','î'=>'i','ï'=>'i','ñ'=>'n','ò'=>'o','ó'=>'o','ô'=>'o','õ'=>'o','ö'=>'oe',
                                  'ø'=>'o','ù'=>'u','ú'=>'u','û'=>'u','ü'=>'ue','ý'=>'y','ÿ'=>'y','ß'=>'ss', '&nbsp;' => ' '); 
          return strtr($string, $transforms);        
        }
    }
    

    if ( stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml") ) { 
        header("Content-type: application/xhtml+xml"); 
    } else { 
        header("Content-type: text/xml"); 
    } 
    
    echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";
    echo '<tree id="0">' . "\n";
    getLevelFromDB(0);
    $prod_query = tep_db_query("SELECT p.products_id, pd.products_name FROM " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c WHERE p.products_id = pd.products_id AND pd.language_id = '" . $languages_id . "' AND p2c.products_id = p.products_id AND p2c.categories_id = '0'");
    while( $prod = tep_db_fetch_array($prod_query) ) {
        echo '<item text="' . cre_stripInvalidXml($prod['products_name']) . '" id="p_' . $prod['products_id'] . '" im0="leaf.gif" im1="leaf.gif" im2="leaf.gif"></item>' . "\n";
    }
    
    echo '</tree>';
    
    //print one level of the tree, based on parent_id
    function getLevelFromDB($parent_id){
        global $languages_id;
        $cat_query_raw = "SELECT c.categories_id, cd.categories_name FROM " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd WHERE c.categories_id = cd.categories_id AND cd.language_id = '" . $languages_id . "' AND c.parent_id = '" . $parent_id . "' AND cd.categories_name <> 'Independent Stores' ORDER BY cd.categories_name";
        $cat_query = tep_db_query($cat_query_raw);
        while($cat = tep_db_fetch_array($cat_query)){  
            echo '<item text="' . cre_stripInvalidXml($cat['categories_name']) . '" id="c_' . tep_get_generated_category_path_ids($cat['categories_id']) . '" im0="folderClosed.gif" im1="folderOpen.gif" im2="folderClosed.gif">' . "\n";
            $prod_query = tep_db_query("SELECT p.products_id, pd.products_name FROM " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c WHERE p.products_id = pd.products_id AND pd.language_id = '" . $languages_id . "' AND p2c.products_id = p.products_id AND p2c.categories_id = '" . $cat['categories_id'] . "'");
            while( $prod = tep_db_fetch_array($prod_query) ) {
                echo '<item text="' . cre_stripInvalidXml($prod['products_name']) . '" id="p_' . $prod['products_id'] . '" im0="leaf.gif" im1="leaf.gif" im2="leaf.gif"></item>' . "\n";
            }
            getLevelFromDB($cat['categories_id']);
            echo '</item>' . "\n";
        }
    }
 
 require(DIR_WS_INCLUDES . 'application_bottom.php'); 
 ?> 