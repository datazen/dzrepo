<?php
/*
  $Id: $


*/

function display_inline_help($topic, $page)
{


    $headline = '';
    $help_content = '';


    list($headline, $help_content) = load_inline_help($topic, $page);

    if ($help_content != '') {


        $toggle_js = '
            <script language="JavaScript" type="text/javascript">
		    function help_toggle(x) {
                if (document.getElementById(x).style.display == \'none\') {
                    document.getElementById(x).style.display = \'\';
                } else {
                    document.getElementById(x).style.display = \'none\';
                }

            }
            </script>
            <style type="text/css">
                div.more_content {
                    display: block;
                    clear: none;
                    padding: 0;
                    margin: 0;
                    }

            </style>';

        $toggle_js_end = '<script language="JavaScript" type="text/javascript">help_toggle(\'more_content\');</script>';


        return $toggle_js .
               '<tr><td>
                <br/>
                <table width="100%">
                  <tr>
                    <td class="form-head">' . $headline . '</td>
                  </tr>
                  <tr>
                    <td class="form-body">' . $help_content . '</td>
                  </tr>
                </table>
             </td>
            </tr>' .
               $toggle_js_end;
    } else {
        return '';
    }

}

function load_inline_help($topic, $page)
{
    global $language;

    $headline_file = $topic . '/' . $page . '_headline.html';
    $headline_common_file = $topic . '/' . 'headline.html';

    $headline_file_path = DIR_WS_LANGUAGES . $language . '/help/' . $headline_file;
    $headline_file_default_path = DIR_WS_LANGUAGES . 'english/help/' . $headline_file;
    $headline_file_common_path = DIR_WS_LANGUAGES . $language . '/help/' . $headline_common_file;
    $headline_file_common_default_path = DIR_WS_LANGUAGES . 'english/help/' . $headline_common_file;

    ob_start();
    if (file_exists($headline_file_path)) {
        include($headline_file_path);
    } elseif (file_exists($headline_file_default_path)) {
        include($headline_file_default_path);
    } elseif (file_exists($headline_file_common_path)) {
        include($headline_file_common_path);
    } elseif (file_exists($headline_file_common_default_path)) {
        include($headline_file_common_default_path);
    }
    $headline = ob_get_contents();
    ob_end_clean();


    $help_file = $page . '.html';
    $help_file_root = DIR_WS_LANGUAGES . $language . '/help/' . $topic . '/' ;
    $help_file_path = $help_file_root . $help_file;
    $help_file_default_path = DIR_WS_LANGUAGES . 'english/help/' . $help_file;

    ob_start();
    if (file_exists($help_file_path)) {
        include($help_file_path);
    } elseif (file_exists($help_file_default_path)) {
        include($help_file_default_path);
    }
    $help_content = ob_get_contents();
    ob_end_clean();

    return array($headline, $help_content);

}

?>
