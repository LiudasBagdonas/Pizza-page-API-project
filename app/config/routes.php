<?php

use Core\Router;

// Auth Controllers
Router::add('login', '/login', \App\Controllers\Common\Auth\LoginController::class, 'login');
Router::add('register', '/register', \App\Controllers\Common\Auth\RegisterController::class, 'register');
Router::add('logout', '/logout', \App\Controllers\Common\Auth\LogoutController::class, 'logout');
Router::add('install', '/install', \App\Controllers\Common\InstallController::class, 'install');

// Common Routes
Router::add('index', '/', \App\Controllers\Common\HomeController::class);
Router::add('index2', '/index.php', \App\Controllers\Common\HomeController::class);

// Admin Routes
Router::add('admin_orders', "/admin/orders", \App\Controllers\Admin\OrdersController::class);
Router::add('admin_users', "/admin/users", \App\Controllers\Admin\UsersController::class);

// User Routes
Router::add('user_orders', '/my/orders', \App\Controllers\User\OrdersController::class);

// API Routes
Router::add('api_pizza_get', '/api/pizza/get', \App\Controllers\Common\API\PizzaApiController::class);
Router::add('api_pizza_create', '/api/pizza/create', \App\Controllers\Admin\API\PizzaApiController::class, 'create');
Router::add('api_pizza_edit', '/api/pizza/edit', \App\Controllers\Admin\API\PizzaApiController::class, 'edit');
Router::add('api_pizza_update', '/api/pizza/update', \App\Controllers\Admin\API\PizzaApiController::class, 'update');
Router::add('api_pizza_delete', '/api/pizza/delete', \App\Controllers\Admin\API\PizzaApiController::class, 'delete');