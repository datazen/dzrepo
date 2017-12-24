<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html {$HTML_PARAMS}>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset={$SESSION_CHARSET}">
    <title>{$TITLE}</title>
    <link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
    <link rel="stylesheet" type="text/css" href="{$MH_CATALOG_URL}mailhive/common/js/ceebox/css/ceebox_mh.css"/>
    <link rel="stylesheet" type="text/css"
          href="{$MH_CATALOG_URL}/mailhive/common/admin_application_plugins/common.css">
    <script type="text/javascript">
        {literal}
        function rowOverEffect(object) {
            if (object.className == 'dataTableRow') object.className = 'dataTableRowOver';
        }

        function rowOutEffect(object) {
            if (object.className == 'dataTableRowOver') object.className = 'dataTableRow';
        }
        {/literal}
    </script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
{$ADMIN_HEADER}
<script type="text/javascript" src="gm/javascript/gm_modules.js"></script>

<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
    <tr>
        <td class="columnLeft2" width="{$ADMIN_BOX_WIDTH}" valign="top">
            <table border="0" width="{$ADMIN_BOX_WIDTH}" cellspacing="1" cellpadding="1" class="columnLeft">
                <!-- left_navigation //-->
            {$ADMIN_COLUMN_LEFT}
                <!-- left_navigation_eof //-->
            </table>
        </td>
        <!-- body_text //-->
        <td width="100%" valign="top">
            <script language="javascript" src="{$MH_CATALOG_URL}/mailhive/common/js/jquery.tools.min-1.2.5.js"></script>
            <script language="javascript"
                    src="{$MH_CATALOG_URL}/mailhive/common/js/ceebox/js/jquery.ceebox-min.js"></script>

        {include file="main_common_js.tpl"}
        {include file="main_common_content.tpl"}
        </td>
        <!-- body_eof //-->

        <!-- footer //-->
    {$ADMIN_FOOTER}
        <!-- footer_eof //-->
        <br/>
    {include file="main_common_ceebox.tpl"}
    {include file="main_common_update_reminder.tpl"}
</body>
</html>
{$ADMIN_APPLICATION_BOTTOM}

