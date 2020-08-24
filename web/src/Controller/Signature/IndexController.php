<?php

declare(strict_types=1);

namespace App\Controller\Signature;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
use Slim\Views\Twig;

class IndexController
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Twig
     */
    private $view;

    public function __construct(LoggerInterface $logger, Twig $view)
    {
        $this->logger = $logger;
        $this->view = $view;
    }

    public function route(Request $request, Response $response, array $args): Response
    {
        return $this->view->render($response, 'index.html.twig', []);
    }
}
