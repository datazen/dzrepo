<!DOCTYPE html>
<html xmlns:ng="http://angularjs.org" ng-app="app">
<head>
    <base href="/" />
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Angular Cart</title>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>" />
    <link rel="stylesheet" href="assets/css/app.Admin.css?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>" rel="stylesheet" />
    <link rel="stylesheet" href="assets/css/utility.css?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>" rel="stylesheet" />
    <link rel="stylesheet" href="assets/css/ie10-viewport-bug-workaround.css?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>" rel="stylesheet" />
    <link rel="shortcut icon" href="assets/img/Admin/favicon.ico" type="image/x-icon" />    
</head>
<body>  

    <ng-include ng-if="currentState.site == 'Admin'" src="'./app/shared/Admin/header/headerView.php'"></ng-include>
    <ng-include ng-if="currentState.site == 'Admin'" src="'./app/shared/Admin/sidebar/sidebarView.php'"></ng-include>
    <div class="container-fluid" id="body-container">
        <div class="row">
            <div ng-view></div>
        </div>
    </div>

    <script src="//code.jquery.com/jquery-3.1.1.min.js?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>"></script>
    <script src="//code.angularjs.org/1.6.6/angular.min.js?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>"></script>
    <script src="//code.angularjs.org/1.6.6/angular-route.min.js?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>"></script>
    <script src="//code.angularjs.org/1.6.6/angular-cookies.min.js?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>"></script>
    <script src="//code.angularjs.org/1.6.6/angular-animate.min.js?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>"></script>
    <script src="//code.angularjs.org/1.6.6/angular-touch.min.js?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>"></script>
    <script src="//code.angularjs.org/1.6.6/angular-sanitize.min.js?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>"></script>
    <script src="//code.angularjs.org/1.6.6/angular-messages.min.js?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>"></script>
    <script src="//unpkg.com/angular-ui-router@0.4.2/release/angular-ui-router.js"></script>
    <script src="node_modules/angular-translate/dist/angular-translate.min.js"></script>
    
    <script src="assets/lib/ui-bootstrap-2.5.0.min.js?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>"></script>
    <script src="//angular-ui.github.io/bootstrap/ui-bootstrap-tpls-2.5.0.js"></script>

    <!-- Translations -->
    <script src="i18n/en/Admin/translations.en.js?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>"></script>
    <script src="i18n/es/Admin/translations.es.js?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>"></script>
    <script src="i18n/fr/Admin/translations.fr.js?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>"></script>
    <script src="i18n/de/Admin/translations.de.js?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>"></script>

    <!-- Routes -->
    <script src="app/routes/app.routes.Admin.js?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>"></script>
    <script src="app/routes/app.routes.Site.js?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>"></script>
    <script src="app/app.js?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>"></script>

    <!-- Admin -->
    <script src="app/components/Admin/dashboard/dashboardController.js?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>"></script>
    <script src="app/components/Admin/company/companyService.js?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>"></script>
    <script src="app/components/Admin/company/companyController.js?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>"></script>
    <script src="app/components/Admin/users/usersService.js?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>"></script>
    <script src="app/components/Admin/users/usersController.js?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>"></script>
    <script src="app/components/Admin/login/loginService.js?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>"></script>
    <script src="app/components/Admin/login/loginController.js?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>"></script>
    <script src="app/components/Admin/accessLevels/accessLevelsService.js?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>"></script>
    <script src="app/components/Admin/accessLevels/accessLevelsController.js?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>"></script>
    <script src="app/components/Admin/pageAccess/pageAccessService.js?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>"></script>
    <script src="app/components/Admin/pageAccess/pageAccessController.js?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>"></script>
    <script src="app/components/Admin/configuration/configurationService.js?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>"></script>
    <script src="app/components/Admin/configuration/configurationController.js?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>"></script>
    <script src="app/components/Admin/profile/profileController.js?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>"></script>
    
    <script src="app/shared/Admin/header/headerController.js?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>"></script>
    <script src="app/shared/Admin/sidebar/sidebarController.js?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>"></script>
    <script src="app/shared/Admin/common/flashService.js?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>"></script>
    <script src="app/shared/Admin/common/uploadService.js?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>"></script>
    <script src="app/shared/Admin/common/ngConfirmClickDirective.js?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>"></script>
    <script src="app/shared/Admin/common/stringToNumberDirective.js?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>"></script>
    <script src="app/shared/Admin/common/passwordVerifyDirective.js?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>"></script>
    <script src="app/shared/Admin/common/startFromFilter.js?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>"></script>

    <!-- Public Site -->
    <script src="app/components/Site/home/homeController.js?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>"></script>
    <script src="app/shared/Site/common/flashService.js?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>"></script>

    <?php
    if (file_exists('assets/js/general.js.php')) include 'assets/js/general.js.php';
    ?>    
</body>
</html>