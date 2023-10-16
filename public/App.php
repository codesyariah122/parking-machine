<?php
require_once 'App/Config/Autoload.php';

use App\Config\Router;

$app = new Router;

$app->get('/', 'WebBase\WebPageController@index');
$app->get('/parkir', 'WebBase\WebPageController@parkir');
$app->get('/login', 'Auth\LoginController@index');

$app->group('users', function ($app) {
    $app->post('/users/add', 'Auth\AuthManagementController@add');
    $app->post('/auth/login', 'Auth\AuthManagementController@login');
    $app->post('/auth/logout', 'Auth\AuthManagementController@logout');
    $app->get('/check/session-login', 'Auth\AuthManagementController@checkSessionLogin');
});

$app->group('/dashboard', function($app) {
    //Dashboard Admin
    $app->get('/admin', 'Dashboard\AdminController@index');
    $app->get('/tickets', 'Dashboard\TicketsController@index');
    $app->get('/payments', 'Dashboard\PaymentsController@index');

    // Api dashboard
    $app->get('/lists/tickets', 'Dashboard\TicketsController@all');
    $app->get('/lists/payments', 'Dashboard\PaymentsController@all');
});

$app->group('/parkir', function ($app) {
    $app->get('/check-available', 'Api\ParkingProcessController@checkAvailable');
    $app->get('/take-ticket', 'Api\ParkingProcessController@store');
    $app->post('/start-parking' ,'Api\StartParkingController@store');
    $app->post('/end-parking', 'Api\ParkingPaymentController@store');
    $app->get('/barcode/{param}', 'Api\GenerateBarcodeController@run');
});

$app->run();