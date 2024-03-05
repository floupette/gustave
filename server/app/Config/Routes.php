<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Route par défaut pour la page d'accueil
$routes->get('/', 'Home::index');

// Route pour récuperer les images uploader
$routes->get('uploads/(:segment)', 'UploadController::show/$1');

// Routes pour l'authentification (login & logout)
$routes->group('auth', function ($routes) {
    $routes->post('login', 'AuthController::login');
    $routes->get('info', 'AuthController::info');
    $routes->get('logout', 'AuthController::logout');
});

// Route pour le CRUD User
$routes->group('users', function ($routes) {
    $routes->post('create', 'UserController::create');
    $routes->get('(:num)', 'UserController::show/$1');
    $routes->get('/', 'UserController::index');
    $routes->put('(:num)', 'UserController::update/$1');
    $routes->delete('(:num)', 'UserController::delete/$1');
});

// Route pour le CRUD Logement
$routes->group('logements', function ($routes) {
    $routes->post('create', 'LogementController::create');
    $routes->get('(:num)', 'LogementController::show/$1');
    $routes->get('/', 'LogementController::index');
    $routes->put('(:num)', 'LogementController::update/$1');
    $routes->delete('(:num)', 'LogementController::delete/$1');
});

// Route pour le CRUD Equipement
$routes->group('equipements', function ($routes) {
    $routes->post('create', 'EquipementController::create');
    $routes->get('(:num)', 'EquipementController::show/$1');
    $routes->get('/', 'EquipementController::index');
    $routes->put('(:num)', 'EquipementController::update/$1');
    $routes->delete('(:num)', 'EquipementController::delete/$1');
});

// Route pour le CRUD Reservation
$routes->group('reservation', function ($routes) {
    $routes->post('create', 'ReservationController::create');
    $routes->get('(:num)', 'ReservationController::show/$1');
    $routes->get('/', 'ReservationController::index');
    $routes->put('(:num)', 'ReservationController::update/$1');
    $routes->delete('(:num)', 'ReservationController::delete/$1');
});

// Route pour le CRUD Rating
$routes->group('ratings', function ($routes) {
    $routes->post('create', 'RatingController::create');
    $routes->get('(:num)', 'RatingController::show/$1');
    $routes->get('/', 'RatingController::index');
    $routes->put('(:num)', 'RatingController::update/$1');
    $routes->delete('(:num)', 'RatingController::delete/$1');
});
