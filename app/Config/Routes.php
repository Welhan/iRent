<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
// $routes->get('/', 'Home::index');

$routes->get('/login', 'Auth::index');
$routes->post('/logining', 'Auth::login');
$routes->get('/logout', 'Auth::logout');

$routes->get('/', 'Dashboard::index');

// User Menu
// $routes->get('/user', 'User::index');
$routes->post('/access', 'User::userAccess');
$routes->post('/accessMenu', 'User::access');
$routes->group('user', static function ($routes) {
    $routes->get('', 'User::index');
    $routes->get('getData', 'User::userData');
    $routes->get('newUser', 'User::formNew');
    $routes->post('saveUser', 'User::saveUser');
    $routes->post('edit', 'User::editUser');
    $routes->post('updateUser', 'User::updateUser');
    $routes->post('deleteUser', 'User::formDelete');
    $routes->post('removeUser', 'User::deleteUser');
});

// Client Menu
$routes->get('/newClient', 'Client::newClient');
$routes->get('/editClient', 'Client::updateClient');
$routes->group('client', static function ($routes) {
    $routes->get('', 'Client::index');
    $routes->get('getData', 'Client::clientData');
    // $routes->get('newClient', 'Client::formNew');
    $routes->post('saveClient', 'Client::saveClient');
    // $routes->post('edit', 'Client::formEdit');
    $routes->post('editClient', 'Client::editClient');
    $routes->post('delete', 'Client::formDelete');
    $routes->post('deleteClient', 'Client::deleteClient');
});

// Provinsi Menu
$routes->group('kota', static function ($routes) {
    $routes->get('', 'Kota::index');
    $routes->get('getData', 'Kota::getData');
    $routes->get('refresh', 'Kota::refreshData');
});

// Profile Menu
$routes->group('profile', static function ($routes) {
    $routes->get('', 'Profile::index');
    $routes->post('edit', 'Profile::getEdit');
    $routes->post('updateProfile', 'Profile::editProfile');
    $routes->post('changePass', 'Profile::getPassword');
    $routes->post('updatePassword', 'Profile::editPassword');
    $routes->post('remove', 'Profile::removePP');
    $routes->post('removePic', 'Profile::removeProfPic');
});

// Vehicle Menu
$routes->get('addVehicle', 'Vehicle::formNew');
$routes->group('vehicle', static function ($routes) {
    $routes->get('', 'Vehicle::index');
    $routes->get('getData', 'Vehicle::getData');
    $routes->post('saveVehicle', 'Vehicle::newVehicle');
    $routes->get('listVehicle', 'Vehicle::getListVechicle');
});
/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
