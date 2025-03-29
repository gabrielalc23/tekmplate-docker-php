<?php

use Fastroute\RouteCollector;
use Fastroute\Dispatcher;
use Fastroute\RouteParser;

require '/path/to/vendor/autoload.php';



$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\ConfigureRoutes $r) {
    $r->addRoute('GET', '/users', 'get_all_users_handler');

    $r->addRoute('GET', '/user/{id:\d+}', 'get_user_handler');
    
    $r->addRoute('GET', '/articles/{id:\d+}[/{title}]', 'get_article_handler');
});