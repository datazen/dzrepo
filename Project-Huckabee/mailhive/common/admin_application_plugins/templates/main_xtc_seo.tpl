{$CSEO_METATAG}
<script language="javascript" src="{$MH_CATALOG_URL}/mailhive/common/js/jquery.tools.min-1.1.2.js"></script>
<script language="javascript" src="{$MH_CATALOG_URL}/mailhive/common/js/ceebox/js/jquery.ceebox-min.js"></script>

{include file="main_common_js.tpl"}
{include file="main_common_ceebox.tpl"}

<link rel="stylesheet" type="text/css" href="{$MH_CATALOG_URL}/mailhive/common/js/ceebox/css/ceebox_mh.css"/>
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
<body bgcolor="#FFFFFF">
<!-- header //-->
{$ADMIN_HEADER}
<!-- header_eof //-->
<!-- body //-->
<div id="wrapper">
    <table class="outerTable" cellpadding="0" cellspacing="0">
        <tr>
            <td class="columnLeft2" width="{$ADMIN_BOX_WIDTH}" valign="top">
                <table border="0" width="{$ADMIN_BOX_WIDTH}" cellspacing="1" cellpadding="1" class="columnLeft">
                    <!-- left_navigation //-->
                {$ADMIN_COLUMN_LEFT}
                    <!-- left_navigation_eof //-->
                </table>
            </td>
            <td class="boxCenter" width="100%" valign="top">

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
