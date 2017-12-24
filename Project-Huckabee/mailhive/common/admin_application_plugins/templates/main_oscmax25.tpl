<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html {$HTML_PARAMS}>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset={$SESSION_CHARSET}"/>
    <title>{$TITLE}</title>
    <link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
    <link rel="stylesheet" type="text/css" href="includes/javascript/jquery-ui-1.8.2.custom.css">
    <script type="text/javascript" src="includes/general.js"></script>
    <link rel="stylesheet" type="text/css" href="{$MH_CATALOG_URL}mailhive/common/js/ceebox/css/ceebox_mh.css"/>
    <link rel="stylesheet" type="text/css"
          href="{$MH_CATALOG_URL}/mailhive/common/admin_application_plugins/common.css">
</head>
<body>
<!-- header //-->
{$ADMIN_HEADER}

<script language="javascript">
    jQuery.noConflict();
</script>
<!-- the tab thing breaks the jquery stuff of oscmax 2.5, so leave it out until it becomes an issue... -->
<script language="javascript" src="{$MH_CATALOG_URL}mailhive/common/js/jquery.tools.min-1.2.5.js"></script>
<script language="javascript" src="{$MH_CATALOG_URL}mailhive/common/js/ceebox/js/jquery.ceebox-min.js"></script>
{include file="main_common_js.tpl"}

{include file="main_common_ceebox.tpl"}
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
    <tr>
        <td width="{$ADMIN_BOX_WIDTH}" valign="top">
            <table border="0" width="{$ADMIN_BOX_WIDTH}" cellspacing="1" cellpadding="1" class="columnLeft">
                <!-- left_navigation //-->
            {$ADMIN_COLUMN_LEFT}
                <!-- left_navigation_eof //-->
            </table>
        </td>
        <!-- body_text //-->
        <td width="100%" valign="top">
        {include file="main_common_content.tpl"}
        </td>
        <!-- body_text_eof //-->
    </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
{$ADMIN_FOOTER}
<!-- footer_eof //-->

{include file="main_common_update_reminder.tpl"}
</body>
</html>
{$ADMIN_APPLICATION_BOTTOM}
