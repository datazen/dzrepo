<?php
/*
  $Id: ultimate_seo.php,v 1.2 2004/03/05 00:36:41 maestro Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Ultimate SEO URL\'s User Manual');
define('PAGE_CONTENT', '<p><b>Notes and Configuration:</b></p>
                        
                        <p>NOTE: You may need to clear any cache files to ensure that all of the URLs generated are fresh.</p>

                        <p>Click a few links and verify everything is functional. Test the add to cart and buy now buttons if you use them. Once you verify everything is working it\'s time to configure the contribution.</p>

                        <p><b>Configuration</b></p>

                        <p>You will find a new setting group in your admin control panel under Configuration => SEO URLs</p>

                        <p>Add cPath to product URLs? *NEW*<br> 
                          &nbsp;&nbsp;&nbsp;Default Setting: false<br> 
                          &nbsp;&nbsp;&nbsp;This setting enables/disables the option of adding the cPath parameter to the end of product pages. Example: some-product-p-1.html?cPath=xx</p> 

                        <p>Choose URL Rewrite Type<br> 
                          &nbsp;&nbsp;&nbsp;Default Setting: Rewrite<br> 
                          &nbsp;&nbsp;&nbsp;This setting selection of URL rewrite types. Currently, there is only the 1 type (Rewrite) but in the future there will be more.</p> 

                        <p>Filter Short Words<br> 
                          &nbsp;&nbsp;&nbsp;Default Setting: true<br> 
                          &nbsp;&nbsp;&nbsp;This setting allows a store owner to filter short words which typically add no value. Don\'t set this too high!</p> 

                        <p>Output W3C valid URLs (parameter string)? *NEW*<br> 
                          &nbsp;&nbsp;&nbsp;Default Setting: true<br> 
                          &nbsp;&nbsp;&nbsp;This setting allows a store owner to choose W3C valid URLs.</p> 

                        <p>Enable SEO cache to save queries?<br> 
                          &nbsp;&nbsp;&nbsp;Default Setting: true<br> 
                          &nbsp;&nbsp;&nbsp;This setting enables/disables the query cache engine globally. If disabled each URL generated will take 1 query.</p> 

                        <p>Enable product cache?<br> 
                          &nbsp;&nbsp;&nbsp;Default Setting: true<br> 
                          &nbsp;&nbsp;&nbsp;This setting enables/disables the option of using cache for the products.</p> 

                        <p>Enable categories cache?<br> 
                          &nbsp;&nbsp;&nbsp;Default Setting: true<br> 
                          &nbsp;&nbsp;&nbsp;This setting enables/disables the option of using cache for the categories.</p> 

                        <p>Enable manufacturers cache?<br> 
                          &nbsp;&nbsp;&nbsp;Default Setting: true<br> 
                          &nbsp;&nbsp;&nbsp;This setting enables/disables the option of using cache for the manufacturers.</p> 

                        <p>Enable articles cache?<br> 
                          &nbsp;&nbsp;&nbsp;Default Setting: true<br> 
                          &nbsp;&nbsp;&nbsp;This setting enables/disables the option of using cache for the articles (if installed).</p> 

                        <p>Enable information cache?<br> 
                          &nbsp;&nbsp;&nbsp;Default Setting: true<br> 
                          &nbsp;&nbsp;&nbsp;This setting enables/disables the option of using cache for the information pages (if installed).</p> 

                        <p>Enable topics cache?<br> 
                          &nbsp;&nbsp;&nbsp;Default Setting: true<br> 
                          &nbsp;&nbsp;&nbsp;This setting enables/disables the option of using cache for the article topics (if installed).</p> 

                        <p>Enable automatic redirects?<br> 
                          &nbsp;&nbsp;&nbsp;Default Setting: true<br> 
                          &nbsp;&nbsp;&nbsp;This setting enables/disables the automatic 301 header redirect logic. This sends a moved permanent header for all old URLs to the new ones. This setting is highly recommended for stores that have already been indexed by spiders.</p>

                        <p>Enter special character conversions<br> 
                          &nbsp;&nbsp;&nbsp;Default Setting: NULL<br> 
                          &nbsp;&nbsp;&nbsp;This setting allows a store owner to enter a list of special character conversions. Please note the format below.<br>
                          &nbsp;&nbsp;&nbsp;FORMAT: char=>conv, char2=>conv2, char3=>conv3<br> 
                          &nbsp;&nbsp;&nbsp;NOTE: use a comma as the separator</p> 

                        <p>Remove all non-alphanumeric characters?<br> 
                          &nbsp;&nbsp;&nbsp;Default Setting: false<br> 
                          &nbsp;&nbsp;&nbsp;This setting allows the store owner to remove all non-alphanumeric characters from the URL. This is highly effective and will result in some interesting URLs. For example, some-product-p-1.html will become someproduct-p-1.html</p>

                        <p>Reset SEO URLs Cache<br> 
                          &nbsp;&nbsp;&nbsp;Default Setting: false<br> 
                          &nbsp;&nbsp;&nbsp;This setting allows the store owner to clear the SEO cache manually.</p>
                       ');

?>
