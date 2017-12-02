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

$container = $app->getContainer();
$container['upload_directory'] = __DIR__ . '/img';

// Users
$app->get('/users', function () { echo Users::getAll(); });
$app->get('/getUserById/{id}', function (Request $request, Response $response) { echo Users::getUserById($request); });
$app->get('/getUserByUsername/{username}', function (Request $request, Response $response) { echo Users::getUserByUsername($request); });
$app->post('/addUser', function (Request $request, Response $response) { echo Users::addUser($request); });
$app->post('/updateUser/{id}', function (Request $request, Response $response) { echo Users::updateUser($request); });
$app->post('/updateAvatar/{id}', function (Request $request, Response $response) { echo Users::updateAvatar($request); });
$app->get('/deleteUser/{id}', function (Request $request, Response $response) { echo Users::deleteUser($request); });

// Access
$app->get('/accessLevels', function () { echo Access::getAllAccessLevels(); });
$app->get('/getAccessLevelById/{id}', function (Request $request, Response $response) { echo Access::getAccessLevelById($request); });
$app->post('/updateAccessLevel/{id}', function (Request $request, Response $response) { echo Access::updateAccessLevel($request); });

// Page Access
$app->post('/getAllPages', function (Request $request, Response $response) { echo Access::getAllPages($request); });
$app->get('/getPageById/{id}', function (Request $request, Response $response) { echo Access::getPageById($request); });
$app->get('/getPageByRoute/Admin/{route}', function (Request $request, Response $response) { echo Access::getPageByRoute($request); });
$app->get('/getPagesByAccessLevel/{level}', function (Request $request, Response $response) { echo Access::getPagesByAccessLevel($request); });
$app->get('/getPageByRoute/Admin/configuration/{route}', function (Request $request, Response $response) { echo Access::getPageByConfigRoute($request, 'configuration'); });
$app->get('/getPageByRoute/Admin/editUser/{route}', function (Request $request, Response $response) { echo Access::getPageByConfigRoute($request, 'editUser'); });
$app->post('/updatePage/{id}', function (Request $request, Response $response) { echo Access::updatePage($request); });

// Configuration
$app->get('/configurations/{id}', function (Request $request, Response $response) { echo Configuration::getAllConfigurations($request); });
$app->get('/getAllConfigurationData', function (Request $request, Response $response) { echo Configuration::getAllConfigurationData($request); });
$app->get('/configurationGroups', function (Request $request, Response $response) { echo Configuration::getConfigurationGroups($request); });
$app->get('/getConfigurationById/{id}', function (Request $request, Response $response) { echo Configuration::getConfigurationById($request); });
$app->post('/updateConfiguration/{id}', function (Request $request, Response $response) { echo Configuration::updateConfiguration($request); });

// Login
$app->post('/Admin/login', function (Request $request, Response $response) { echo Admin::processLogin($request); });

/*
 * STORE 
 */

$app->post('/login', function (Request $request, Response $response) { echo Users::processLogin($request); });

$app->run();
?>