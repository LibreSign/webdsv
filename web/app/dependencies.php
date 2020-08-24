<?php

declare(strict_types=1);

use App\Controller\Signature\DetailController;
use App\Controller\Signature\IndexController;
use App\Service\DsvService;
use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get('settings');

            $loggerSettings = $settings['logger'];
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },
        IndexController::class => function (ContainerInterface $c) {
            $logger = $c->get(LoggerInterface::class);
            $view = $c->get('view');

            return new IndexController($logger, $view);
        },
        DetailController::class => function (ContainerInterface $c) {
            $logger = $c->get(LoggerInterface::class);
            $view = $c->get('view');
            $dsvService = new DsvService();
            $uploadDir = $c->get('settings')['uploadDir'];

            return new DetailController($logger, $view, $dsvService, $uploadDir);
        },
    ]);
};
