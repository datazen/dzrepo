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

// Company
$app->get('/getAdminCompanyById/{id}', function (Request $request, Response $response) { echo AdminCompany::getCompanyById($request); });
$app->post('/updateAdminCompany/{id}', function (Request $request, Response $response) { echo AdminCompany::updateCompany($request); });

// Users
$app->get('/getAllAdminUsers', function () { echo AdminUsers::getAllUsers(); });
$app->get('/getAdminUserById/{id}', function (Request $request, Response $response) { echo AdminUsers::getUserById($request); });
$app->get('/getAdminUserByEmail/{email}', function (Request $request, Response $response) { echo AdminUsers::getUserByEmail($request); });
$app->post('/addAdminUser', function (Request $request, Response $response) { echo AdminUsers::addUser($request); });
$app->post('/updateAdminUser/{id}', function (Request $request, Response $response) { echo AdminUsers::updateUser($request); });
$app->post('/updateAdminAvatar/{id}', function (Request $request, Response $response) { echo AdminUsers::updateAvatar($request); });
$app->get('/deleteAdminUser/{id}', function (Request $request, Response $response) { echo AdminUsers::deleteUser($request); });

// Access
$app->get('/getAllAdminAccessLevels', function () { echo AdminAccessLevels::getAllAccessLevels(); });
$app->get('/getAdminAccessLevelById/{id}', function (Request $request, Response $response) { echo AdminAccessLevels::getAccessLevelById($request); });
$app->post('/updateAdminAccessLevel/{id}', function (Request $request, Response $response) { echo AdminAccessLevel::updateAccessLevel($request); });

// Page Access
$app->post('/getAllAdminPageAccess', function (Request $request, Response $response) { echo AdminPageAccess::getAllPageAccess($request); });
$app->get('/getAdminPageAccessById/{id}', function (Request $request, Response $response) { echo AdminPageAccess::getPageAccessById($request); });
$app->get('/getAdminPageAccessByRoute/Admin/{route}', function (Request $request, Response $response) { echo AdminPageAccess::getPageAccessByRoute($request); });
$app->get('/getAllAdminPageAccessByAccessLevel/{level}', function (Request $request, Response $response) { echo AdminPageAccess::getPageAccessByAccessLevel($request); });
$app->get('/getAdminPageAccessByRoute/Admin/configuration/{route}', function (Request $request, Response $response) { echo AdminPageAccess::getPageAccessByConfigRoute($request, 'configuration'); });
$app->get('/getAdminPageAccessByRoute/Admin/editUser/{route}', function (Request $request, Response $response) { echo AdminPageAccess::getPageAccessByConfigRoute($request, 'editUser'); });
$app->post('/updateAdminPageAccess/{id}', function (Request $request, Response $response) { echo AdminPageAccess::updatePageAccess($request); });

// Configuration
$app->get('/getAllAdminConfigurations', function (Request $request, Response $response) { echo AdminConfiguration::getAllAdminConfigurations($request); });
$app->get('/getAdminConfigurationsByGroupId/{id}', function (Request $request, Response $response) { echo AdminConfiguration::getAdminConfigurationsByGroupId($request); });
$app->get('/getAllAdminConfigurationGroups', function (Request $request, Response $response) { echo AdminConfiguration::getAdminConfigurationGroups($request); });
$app->get('/getAdminConfigurationById/{id}', function (Request $request, Response $response) { echo AdminConfiguration::getAdminConfigurationById($request); });
$app->post('/updateAdminConfiguration/{id}', function (Request $request, Response $response) { echo AdminConfiguration::updateAdminConfiguration($request); });

// Login
$app->post('/Admin/login', function (Request $request, Response $response) { echo AdminLogin::processLogin($request); });

/*
 * STORE 
 */
//$app->post('/Store/login', function (Request $request, Response $response) { echo Users::processLogin($request); });

$app->run();
?>