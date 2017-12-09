<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\UploadedFileInterface as UploadedFile;

require '../vendor/autoload.php';
require 'Conf/Slim.conf.php';
require 'Lib/Database.lib.php';

require 'Model/AdminLogin.class.php';
require 'Model/AdminCompany.class.php';
require 'Model/AdminUsers.class.php';
require 'Model/AdminAccessLevels.class.php';
require 'Model/AdminPageAccess.class.php';
require 'Model/AdminConfiguration.class.php';

date_default_timezone_set('America/New_York');

/*
echo file_get_contents('php://input');

echo "<pre>";
print_r($_POST);
echo "</pre>";
die('11');
*/
$app = new \Slim\App(["settings" => $config]);

// Endpoint naming format: camelCase([action][site][class][method])  i.e. getStoreAccessById

// Login
$app->post('/Admin/login', function (Request $request, Response $response) { echo AdminLogin::processAdminLogin($request); });

// Access
$app->post('/getAllAdminAccessLevels', function (Request $request, Response $response) { echo AdminAccessLevels::getAllAdminAccessLevels($request); });
$app->post('/getAdminAccessLevelById', function (Request $request, Response $response) { echo AdminAccessLevels::getAdminAccessLevelById($request); });
$app->post('/updateAdminAccessLevel', function (Request $request, Response $response) { echo AdminAccessLevels::updateAdminAccessLevel($request); });

// Company
$app->post('/getAdminCompanyById', function (Request $request, Response $response) { echo AdminCompany::getAdminCompanyById($request); });
$app->post('/updateAdminCompany', function (Request $request, Response $response) { echo AdminCompany::updateAdminCompany($request); });

// Users
$app->post('/getAllAdminUsers', function (Request $request, Response $response) { echo AdminUsers::getAllAdminUsers($request); });
$app->post('/getAdminUserById', function (Request $request, Response $response) { echo AdminUsers::getAdminUserById($request); });
$app->post('/getAdminUserByEmail', function (Request $request, Response $response) { echo AdminUsers::getAdminUserByEmail($request); });
$app->post('/addAdminUser', function (Request $request, Response $response) { echo AdminUsers::addAdminUser($request); });
$app->post('/updateAdminUser', function (Request $request, Response $response) { echo AdminUsers::updateAdminUser($request); });
$app->post('/updateAdminUserAvatar', function (Request $request, Response $response) { echo AdminUsers::updateAdminUserAvatar($request); });
$app->post('/deleteAdminUser', function (Request $request, Response $response) { echo AdminUsers::deleteAdminUser($request); });

// Page Access
$app->post('/getAllAdminPageAccess', function (Request $request, Response $response) { echo AdminPageAccess::getAllAdminPageAccess($request); });
$app->post('/getAdminPageAccessByAccessLevel', function (Request $request, Response $response) { echo AdminPageAccess::getAdminPageAccessByAccessLevel($request); });
$app->post('/getAdminPageAccessByRoute', function (Request $request, Response $response) { echo AdminPageAccess::getAdminPageAccessByRoute($request); });
$app->post('/getAdminPageAccessById', function (Request $request, Response $response) { echo AdminPageAccess::getAdminPageAccessById($request); });
$app->post('/updateAdminPageAccess', function (Request $request, Response $response) { echo AdminPageAccess::updateAdminPageAccess($request); });

//$app->get('/getAdminPageAccessByRoute/Admin/configuration/{route}', function (Request $request, Response $response) { echo AdminPageAccess::getPageAccessByConfigRoute($request, 'configuration'); });
//$app->get('/getAdminPageAccessByRoute/Admin/editUser/{route}', function (Request $request, Response $response) { echo AdminPageAccess::getPageAccessByConfigRoute($request, 'editUser'); });

// Configuration
$app->post('/getAllAdminConfigurations', function (Request $request, Response $response) { echo AdminConfiguration::getAllAdminConfigurations($request); });
$app->post('/getAllAdminConfigurationGroups', function (Request $request, Response $response) { echo AdminConfiguration::getAllAdminConfigurationGroups($request); });
$app->post('/getAdminConfigurationsByGroupId', function (Request $request, Response $response) { echo AdminConfiguration::getAdminConfigurationsByGroupId($request); });
$app->post('/getAdminConfigurationById', function (Request $request, Response $response) { echo AdminConfiguration::getAdminConfigurationById($request); });
$app->post('/updateAdminConfiguration', function (Request $request, Response $response) { echo AdminConfiguration::updateAdminConfiguration($request); });



/*
 * STORE 
 */
//$app->post('/Store/login', function (Request $request, Response $response) { echo Users::processLogin($request); });

$app->run();
?>