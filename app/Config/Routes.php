<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Login::index');            
$routes->get('/login', 'Login::index');         
$routes->post('/login/check', 'Login::check');  

$routes->get('/dashboard', 'Dashboard::index');

$routes->post('/transaction/depot', 'Transaction::depot');  
$routes->post('/transaction/retrait', 'Transaction::retrait'); 

$routes->get('/logout', 'Logout::index');       
