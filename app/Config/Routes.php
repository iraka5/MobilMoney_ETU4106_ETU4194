<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/operateurs', 'Home::listerTout'); 
$routes->get('frais/modifier/(:num)', 'Home::modifierFrais/$1');
$routes->post('frais/mettreAJour/(:num)', 'Home::mettreAJourFrais/$1');

