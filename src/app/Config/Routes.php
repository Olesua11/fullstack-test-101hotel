<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('api/comments', 'Comments::index');

$routes->post('api/comments', 'Comments::store');

$routes->delete('api/comments/(:num)', 'Comments::destroy/$1');
