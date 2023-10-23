<?php
require_once __DIR__ . '/controllers/ManagersController.php';
require_once __DIR__ . '/controllers/FundsController.php';

// Define the routes for the API
$routes = [
    ['GET', '/managers', 'ManagersController@index'],
    ['GET', '/managers/(\d+)', 'ManagersController@read'],
    ['POST', '/managers', 'ManagersController@store'],
    ['PUT', '/managers/(\d+)', 'ManagersController@update'],
    ['DELETE', '/managers/(\d+)', 'ManagersController@destroy'],
    ['GET', '/funds', 'FundsController@index'],
    ['GET', '/funds/(\d+)', 'FundsController@read'],
    ['POST', '/funds', 'FundsController@store'],
    ['PUT', '/funds/(\d+)', 'FundsController@update'],
    ['DELETE', '/funds/(\d+)', 'FundsController@destroy'],
];

// Parse the request URL
$request_url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Find the matching route
$route = null;
foreach ($routes as $r) {
    if ($r[0] == $_SERVER['REQUEST_METHOD'] && preg_match('#^' . $r[1] . '$#', $request_url, $matches)) {
        $route = $r;
        break;
    }
}

// If no route was found, return a 404 error
if (!$route) {
    http_response_code(404);
    echo '404 Not Found';
    exit;
}

// Extract the controller and method names from the route
list($controller_name, $method_name) = explode('@', $route[2]);

// Create a new instance of the controller
$controller = new $controller_name();

// Call the method
$params = array_slice($matches, 1);
call_user_func_array([$controller, $method_name], $params);
