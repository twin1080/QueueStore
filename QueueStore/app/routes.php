<?php
declare(strict_types=1);

use App\Application\Actions\Store\StoreAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $container = $app->getContainer();

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('use /store action');
        return $response;
    });

    $app->group('/store', function (Group $group) use ($container) {
        $group->get('', StoreAction::class);
    });


};
