<?php
/**
 * config/routes.php
 */
use Cake\Routing\RouteBuilder;
use Cake\Routing\Route\DashedRoute;

return function (RouteBuilder $routes): void {
    $routes->setRouteClass(DashedRoute::class);

    $routes->scope('/', function (RouteBuilder $builder): void {
        // Page routes
        $builder->connect('/', ['controller' => 'Users', 'action' => 'login']);
        $builder->connect('/register', ['controller' => 'Users', 'action' => 'register']);
        $builder->connect('/login', ['controller' => 'Users', 'action' => 'login']);
        $builder->connect('/dashboard', ['controller' => 'Users', 'action' => 'dashboard']);

        // API routes (DashedRoute maps these URLs to camelCase actions,
        // e.g. /users/api-login -> UsersController::apiLogin())
        $builder->connect('/users/api-register', ['controller' => 'Users', 'action' => 'apiRegister']);
        $builder->connect('/users/api-login', ['controller' => 'Users', 'action' => 'apiLogin']);
        $builder->connect('/users/api-logout', ['controller' => 'Users', 'action' => 'apiLogout']);
        $builder->connect('/users/api-dashboard', ['controller' => 'Users', 'action' => 'apiDashboard']);

        $builder->fallbacks(DashedRoute::class);
    });
};
