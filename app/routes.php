<?php

declare(strict_types=1);

use App\Controller\Signature\DetailController;
use App\Controller\Signature\IndexController;
use App\Service\DsvService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->group('/', function (Group $group) {
        $group->get('', function (Request $request, Response $response, array $args) {
            $logger = $this->get(LoggerInterface::class);
            $view = $this->get('view');

            return (new IndexController($logger, $view))
                ->route($request, $response, $args)
            ;
        });
        $group->post('', function (Request $request, Response $response, array $args) {
            $logger = $this->get(LoggerInterface::class);
            $view = $this->get('view');
            $dsvService = new DsvService();
            $uploadDir = $this->get('settings')['uploadDir'];

            return (new DetailController($logger, $view, $dsvService, $uploadDir))
                ->route($request, $response, $args)
            ;
        });
    });
};
