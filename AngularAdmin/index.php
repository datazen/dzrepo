<!DOCTYPE html>
<html xmlns:ng="http://angularjs.org" ng-app="app">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Angular Admin</title>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>" />
    <link rel="stylesheet" href="css/app.css?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>" rel="stylesheet" />
    <link rel="stylesheet" href="css/utility.css?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>" rel="stylesheet" />
    <link rel="stylesheet" href="css/ie10-viewport-bug-workaround.css?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>" rel="stylesheet" />
    <link rel="shortcut icon" href="/img/favicon.ico" type="image/x-icon" />    
</head>
<body>  

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

    <script src="js/ui-bootstrap-2.5.0.min.js?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>"></script>
    <script src="//angular-ui.github.io/bootstrap/ui-bootstrap-tpls-2.5.0.js"></script>

    <script src="js/app.js?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>"></script>
    <script src="js/services/authentication.js?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>"></script>
    <script src="js/services/flash.js?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>"></script>
    <script src="js/services/user.js?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>"></script>
    <script src="js/services/upload.js?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>"></script>
    <script src="js/services/access.js?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>"></script>
    <script src="js/services/configuration.js?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>"></script>

    <script src="js/controllers/sidebar.js?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>"></script>
    <script src="js/controllers/dashboard.js?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>"></script>
    <script src="js/controllers/login.js?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>"></script>
    <script src="js/controllers/profile.js?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>"></script>
    <script src="js/controllers/users.js?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>"></script>
    <script src="js/controllers/accessLevels.js?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>"></script>
    <script src="js/controllers/pageAccess.js?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>"></script>
    <script src="js/controllers/configuration.js?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>"></script>

    <?php
    if (file_exists('js/general.js.php')) include 'js/general.js.php';
    ?>    
</body>
</html>