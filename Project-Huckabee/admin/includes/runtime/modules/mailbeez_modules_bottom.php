<?php
$set = (isset($_GET['set'])) ? $_GET['set'] : '';
if ($set == 'payment') {
    require_once(DIR_FS_FUNCTIONS . 'inline_help.php');
    echo display_inline_help('mailbeez', 'payment');
}

?>