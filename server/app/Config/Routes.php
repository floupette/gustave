<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Route par défaut pour la page d'accueil
$routes->get('/', 'Home::index', ['filter' => 'cors']);

// Route pour récuperer les images uploader
$routes->get('uploads/(:segment)', 'UploadController::show/$1', ['filter' => 'cors']);

// Routes pour l'authentification (login & logout)
$routes->group('auth', function ($routes) {
    $routes->post('login', 'AuthController::login', ['filter' => 'cors']);
    $routes->get('info', 'AuthController::info', ['filter' => 'cors']);
    $routes->get('logout', 'AuthController::logout', ['filter' => 'cors']);
});

// Route pour le CRUD User
$routes->group('users', function ($routes) {
    $routes->post('create', 'UserController::create', ['filter' => 'cors']);
    $routes->get('(:num)', 'UserController::show/$1', ['filter' => 'cors']);
    $routes->get('/', 'UserController::index', ['filter' => 'cors']);
    $routes->put('(:num)', 'UserController::update/$1', ['filter' => 'cors']);
    $routes->delete('(:num)', 'UserController::delete/$1', ['filter' => 'cors']);
});

// Route pour le CRUD Logement
$routes->group('logements', function ($routes) {
    $routes->post('create', 'LogementController::create', ['filter' => 'cors']);
    $routes->get('(:num)', 'LogementController::show/$1', ['filter' => 'cors']);
    $routes->get('/', 'LogementController::index', ['filter' => 'cors']);
    $routes->put('(:num)', 'LogementController::update/$1', ['filter' => 'cors']);
    $routes->delete('(:num)', 'LogementController::delete/$1', ['filter' => 'cors']);
});

// Route pour le CRUD Equipement
$routes->group('equipements', function ($routes) {
    $routes->post('create', 'EquipementController::create', ['filter' => 'cors']);
    $routes->get('(:num)', 'EquipementController::show/$1', ['filter' => 'cors']);
    $routes->get('/', 'EquipementController::index', ['filter' => 'cors']);
    $routes->put('(:num)', 'EquipementController::update/$1', ['filter' => 'cors']);
    $routes->delete('(:num)', 'EquipementController::delete/$1', ['filter' => 'cors']);
});

// Route pour le CRUD Reservation
$routes->group('reservation', function ($routes) {
    $routes->post('create', 'ReservationController::create', ['filter' => 'cors']);
    $routes->get('(:num)', 'ReservationController::show/$1', ['filter' => 'cors']);
    $routes->get('/', 'ReservationController::index', ['filter' => 'cors']);
    $routes->put('(:num)', 'ReservationController::update/$1', ['filter' => 'cors']);
    $routes->delete('(:num)', 'ReservationController::delete/$1', ['filter' => 'cors']);
});

// Route pour le CRUD Rating
$routes->group('ratings', function ($routes) {
    $routes->post('create', 'RatingController::create', ['filter' => 'cors']);
    $routes->get('(:num)', 'RatingController::show/$1', ['filter' => 'cors']);
    $routes->get('/', 'RatingController::index', ['filter' => 'cors']);
    $routes->put('(:num)', 'RatingController::update/$1', ['filter' => 'cors']);
    $routes->delete('(:num)', 'RatingController::delete/$1', ['filter' => 'cors']);
});
