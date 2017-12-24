<?php

/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
 */

require_once(DIR_FS_CATALOG . 'mailhive/common/classes/dashboardbeez.php');

class dashboard_latest_news extends dashboardbeez
{

    var $code;
    var $module;
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function dashboard_latest_news()
    {
        dashboardbeez::dashboardbeez();
        $this->code = 'dashboard_latest_news';
        $this->module = 'dashboard_latest_news';
        $this->version = '1.1'; // float value
        $this->required_mb_version = 2.0;
        $this->title = MAILBEEZ_DASHBOARD_LATEST_NEWS_TITLE;
        $this->description = MAILBEEZ_DASHBOARD_LATEST_NEWS_DESCRIPTION;
        $this->status_key = 'MAILBEEZ_DASHBOARD_LATEST_NEWS_STATUS';
        $this->stripHTML = true;

        if (defined('MAILBEEZ_DASHBOARD_LATEST_NEWS_STATUS')) {
            $this->sort_order = MAILBEEZ_DASHBOARD_LATEST_NEWS_SORT_ORDER;
            $this->enabled = (MAILBEEZ_DASHBOARD_LATEST_NEWS_STATUS == 'True');
        }
    }

    function getOutput()
    {
        if (!class_exists('lastRSS')) {
            include(DIR_FS_CATALOG . 'mailhive/common/classes/' . 'rss.php');
        }

        $rss = new lastRSS;
        $rss->items_limit = 20;
        $rss->cache_dir = DIR_FS_CACHE;
        $rss->cache_time = 3600; //86400;


        $feed_url_en = 'http://feeds.feedburner.com/MailbeezNews';
        $feed_url_de = 'http://feeds.feedburner.com/MailbeezNewsDE';

        $feed_url = '';

        switch ($_SESSION['language']) {
            case "german":
                $feed_url = $feed_url_de;
                break;
            default:
                $feed_url = $feed_url_en;
        }

        $feed = $rss->get($feed_url);

        $output = '<div id="WidgetTitle">' . MAILBEEZ_DASHBOARD_LATEST_NEWS_TITLE . '</div>
                   <div id="WidgetSubTitle">' . MAILBEEZ_DASHBOARD_LATEST_NEWS_TEXT . '</div>';
        $output .= '<table style="margin-top: 5px; border="0" width="100%" cellspacing="0" cellpadding="4">' .
                   '  <tr class="dataTableHeadingRow">' .
                   '    <td class="dataTableHeadingContent">' . MAILBEEZ_DASHBOARD_LATEST_NEWS_TITLE . '</td>' .
                   '    <td class="dataTableHeadingContent" align="right">' . MAILBEEZ_DASHBOARD_LATEST_NEWS_DATE . '</td>' .
                   '  </tr>' .
                   '</table>';

        $output .= '<div style="overflow: auto; height: 142px; border:">';
        $output .= '<table style="margin-top: 0px;" border="0" width="100%" cellspacing="0" cellpadding="4">';

        if (is_array($feed) && !empty($feed)) {
            foreach ($feed['items'] as $item) {
                $output .= '  <tr class="dataTableRow" onmouseover="rowOverEffect(this);" onmouseout="rowOutEffect(this);">' .
                           '    <td class="dataTableContent"><a href="' . $item['link'] . '" target="_blank">' . utf8_decode($item['title']) . '</a></td>' .
                           '    <td class="dataTableContent" align="right" style="white-space: nowrap;">' . date("F j, Y", strtotime($item['pubDate'])) . '</td>' .
                           '  </tr>';
            }
        } else {
            $output .= '  <tr class="dataTableRow">' .
                       '    <td class="dataTableContent" colspan="2">' . MAILBEEZ_DASHBOARD_LATEST_NEWS_FEED_ERROR . '</td>' .
                       '  </tr>';
        }

        /*
$output .= '  <tr class="dataTableRow">' .
'    <td class="dataTableContent" align="right" colspan="2"><a href="http://www.oscommerce.com/newsletter/subscribe" target="_blank">' . tep_image(DIR_WS_IMAGES . 'icon_newsletter.png', MAILBEEZ_DASHBOARD_LATEST_NEWS_ICON_NEWSLETTER) . '</a>&nbsp;<a href="http://www.facebook.com/pages/osCommerce/33387373079" target="_blank">' . tep_image(DIR_WS_IMAGES . 'icon_facebook.png', MAILBEEZ_DASHBOARD_LATEST_NEWS_ICON_FACEBOOK) . '</a>&nbsp;<a href="http://twitter.com/osCommerce" target="_blank">' . tep_image(DIR_WS_IMAGES . 'icon_twitter.png', MAILBEEZ_DASHBOARD_LATEST_NEWS_ICON_TWITTER) . '</a>&nbsp;<a href="http://feeds.feedburner.com/osCommerceNewsAndBlogs" target="_blank">' . tep_image(DIR_WS_IMAGES . 'icon_rss.png', MAILBEEZ_DASHBOARD_LATEST_NEWS_ICON_RSS) . '</a></td>'; */

        $output .= '  </tr>' .
                   '</table>';

        $output .= '</div>';
        return $output;
    }

    function check()
    {
        return defined('MAILBEEZ_DASHBOARD_LATEST_NEWS_STATUS');
    }

    function install()
    {
        mh_insert_config_value(array('configuration_title' => 'Enable Latest MailBeez News Module',
                                    'configuration_key' => 'MAILBEEZ_DASHBOARD_LATEST_NEWS_STATUS',
                                    'configuration_value' => 'True',
                                    'configuration_description' => 'Do you want to show the latest MailBeez News on the dashboard?',
                                    'set_function' => 'mh_cfg_select_option(array(\'True\', \'False\'), '
                               ));

        mh_insert_config_value(array('configuration_title' => 'Sort order of display.',
                                    'configuration_key' => 'MAILBEEZ_DASHBOARD_LATEST_NEWS_SORT_ORDER',
                                    'configuration_value' => '15',
                                    'configuration_description' => 'Sort order of display. Lowest is displayed first.',
                                    'set_function' => ''
                               ));
    }

    function keys()
    {
        return array('MAILBEEZ_DASHBOARD_LATEST_NEWS_STATUS', 'MAILBEEZ_DASHBOARD_LATEST_NEWS_SORT_ORDER');
    }

}

?>
