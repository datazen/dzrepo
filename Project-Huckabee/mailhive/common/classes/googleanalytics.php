<?php

/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010, 2011 MailBeez

  inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

  rewrite emails to add tracking code

  $ga = new googleAnalytics('medium', 'campaign', 'source');
  $nInfo->content = $ga->rewriteContent($nInfo, 'content');

  // or
  $ga = new googleAnalytics($medium, $campaign, $source);
  $content = $ga->rewriteContent($content);
 */

///////////////////////////////////////////////////////////////////////////////
///																																					 //
///                 MailBeez Core file - do not edit                         //
///                                                                          //
///////////////////////////////////////////////////////////////////////////////


class googleAnalytics {

// class constructor
  function googleAnalytics($medium = MAILBEEZ_MAILHIVE_GA_MEDIUM, $campaign = 'newslettername', $source = MAILBEEZ_MAILHIVE_GA_SOURCE) {
    $this->code = '';
    $this->medium = $medium;
    $this->campaign = $campaign;
    $this->source = $source;
    $this->buildCode();
  }

  function buildCode() {
    $this->code = 'utm_source=' . urlencode($this->source) . '&utm_medium=' . urlencode($this->medium) . '&utm_campaign=' . urlencode($this->campaign);
  }

  function rewriteContent($input, $rewrite_mode = 'all') {
    $rewritten = $input;
    // $rewritten = preg_replace('#[&?]*osCsid=[a-zA-Z0-9]+#', '',  $input );

    switch ($rewrite_mode) {
      case 'all':
        // rewrite all links
        $rewritten = preg_replace("#href=\"(([a-zA-Z]+://)([a-zA-Z0-9%.;:/=+_-]*[?]+[a-zA-Z0-9&%.;:/=+_-]*))\"#", "href=\"$1" . "&" . $this->code . "\"", $rewritten);
        $rewritten = preg_replace("#href=\"(([a-zA-Z]+://)([a-zA-Z0-9%.;:/=+_-]*))\"#", "href=\"$1" . "?" . $this->code . "\"", $rewritten);
        break;
      case 'shop':
        // rewrite only internal links
        $rewritten = preg_replace("#href=\"" . HTTP_SERVER . "(([a-zA-Z0-9%.;:/=+_-]*[?]+[a-zA-Z0-9&%.;:/=+_-]*))\"#", "href=\"" . HTTP_SERVER . "$1" . "&" . $this->code . "\"", $rewritten);
        $rewritten = preg_replace("#href=\"" . HTTP_SERVER . "(([a-zA-Z0-9%.;:/=+_-]*))\"#", "href=\"" . HTTP_SERVER . "$1" . "?" . $this->code . "\"", $rewritten);
        break;
    }

    return $rewritten;
  }

}

?>
