<?php

declare(strict_types=1);

use Slim\App;
use Slim\Views\TwigMiddleware;

return function (App $app) {
    $app->add(TwigMiddleware::createFromContainer($app));
};
