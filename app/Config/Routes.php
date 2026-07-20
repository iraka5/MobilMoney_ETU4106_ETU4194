<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


$routes->get('/operateurs', 'Home::listerTout'); 
$routes->get('frais/modifier/(:num)', 'Home::modifierFrais/$1');
$routes->post('frais/mettreAJour/(:num)', 'Home::mettreAJourFrais/$1');
$routes->get('commission/modifier/(:num)', 'Home::modifierCommission/$1');
$routes->post('commission/mettreAJour/(:num)', 'Home::mettreAJourCommission/$1');

$routes->get('/', 'Login::index');            
$routes->get('/login', 'Login::index');         
$routes->post('/login/check', 'Login::check');  

$routes->get('/dashboard', 'Dashboard::index');


$routes->post('/transaction/depot', 'Transaction::depot');  
$routes->post('/transaction/retrait', 'Transaction::retrait'); $routes->post('/transaction/transfert', 'Transaction::transfert');
$routes->post('transaction/transfertMultiple', 'Transaction::transfertMultiple');
$routes->get('/logout', 'Logout::index');       

