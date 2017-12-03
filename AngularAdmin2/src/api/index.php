<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\UploadedFileInterface as UploadedFile;

require '../vendor/autoload.php';
require 'Conf/Slim.conf.php';
require 'Lib/Database.lib.php';
require 'Model/Admin.class.php';
require 'Model/Users.class.php';
require 'Model/Access.class.php';
require 'Model/Configuration.class.php';

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

// Users
$app->get('/getAllAdminUsers', function () { echo Users::getAll(); });
$app->get('/getAdminUserById/{id}', function (Request $request, Response $response) { echo Users::getUserById($request); });
$app->get('/getAdminUserByUsername/{username}', function (Request $request, Response $response) { echo Users::getUserByUsername($request); });
$app->post('/addAdminUser', function (Request $request, Response $response) { echo Users::addUser($request); });
$app->post('/updateAdminUser/{id}', function (Request $request, Response $response) { echo Users::updateUser($request); });
$app->post('/updateAdminAvatar/{id}', function (Request $request, Response $response) { echo Users::updateAvatar($request); });
$app->get('/deleteAdminUser/{id}', function (Request $request, Response $response) { echo Users::deleteUser($request); });

// Access
$app->get('/getAllAdminAccessLevels', function () { echo Access::getAllAccessLevels(); });
$app->get('/getAdminAccessLevelById/{id}', function (Request $request, Response $response) { echo Access::getAccessLevelById($request); });
$app->post('/updateAdminAccessLevel/{id}', function (Request $request, Response $response) { echo Access::updateAccessLevel($request); });

// Page Access
$app->post('/getAllAdminPageAccess', function (Request $request, Response $response) { echo Access::getAllPages($request); });
$app->get('/getAdminPageAccessById/{id}', function (Request $request, Response $response) { echo Access::getPageById($request); });
$app->get('/getAdminPageAccessByRoute/Admin/{route}', function (Request $request, Response $response) { echo Access::getPageByRoute($request); });
$app->get('/getAllAdminPageAccessByAccessLevel/{level}', function (Request $request, Response $response) { echo Access::getPagesByAccessLevel($request); });
$app->get('/getAdminPageAccessByRoute/Admin/configuration/{route}', function (Request $request, Response $response) { echo Access::getPageByConfigRoute($request, 'configuration'); });
$app->get('/getAdminPageAccessByRoute/Admin/editUser/{route}', function (Request $request, Response $response) { echo Access::getPageByConfigRoute($request, 'editUser'); });
$app->post('/updateAdminPageAccess/{id}', function (Request $request, Response $response) { echo Access::updatePage($request); });

// Configuration
$app->get('/getAdminConfigurationsByGroupId/{id}', function (Request $request, Response $response) { echo Configuration::getAllConfigurations($request); });
$app->get('/getAllAdminConfigurations', function (Request $request, Response $response) { echo Configuration::getAllConfigurationData($request); });
$app->get('/getAllAdminConfigurationGroups', function (Request $request, Response $response) { echo Configuration::getConfigurationGroups($request); });
$app->get('/getAdminConfigurationById/{id}', function (Request $request, Response $response) { echo Configuration::getConfigurationById($request); });
$app->post('/updateAdminConfiguration/{id}', function (Request $request, Response $response) { echo Configuration::updateConfiguration($request); });

// Login
$app->post('/Admin/login', function (Request $request, Response $response) { echo Admin::processLogin($request); });

/*
 * STORE 
 */
$app->post('/Store/login', function (Request $request, Response $response) { echo Users::processLogin($request); });

$app->run();
?>