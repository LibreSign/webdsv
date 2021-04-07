<?php

declare(strict_types=1);

namespace App\Handlers;

use App\Exception\AppException;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;
use Slim\Handlers\ErrorHandler as SlimErrorHandler;
use Slim\Interfaces\CallableResolverInterface;
use Slim\Views\Twig;

class HttpErrorHandler extends SlimErrorHandler
{
    /** @var Twig */
    private $view;

    public function __construct(
        CallableResolverInterface $callableResolver,
        ResponseFactoryInterface $responseFactory,
        LoggerInterface $logger,
        Twig $view
    ) {
        parent::__construct($callableResolver, $responseFactory, $logger);
        $this->view = $view;
    }

    /**
     * {@inheritdoc}
     */
    protected function respond(): Response
    {
        $data = [];
        $exception = $this->exception;

        if ($exception instanceof AppException) {
            $this->logger->error('Erro do app: '.$exception->getMessage(), $exception->jsonSerialize());
            $data['error'] = ['message' => $exception->getMessage()];

            return $this->view->render($this->createResponse($exception->getCode()), 'index.html.twig', [
                'data' => $data,
            ]);
        }

        $this->logger->error('Erro do server. Erro: '.$exception->getMessage(), [
            'code' => $exception->getCode(),
            'message' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);

        $data['error'] = ['message' => 'Erro na validação das assinaturas.'];

        return $this->view->render($this->createResponse(500), 'index.html.twig', [
            'data' => $data,
        ]);
    }

    private function createResponse(int $errorCode)
    {
        // This variable should be set to the allowed host from which your API can be accessed with
        $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';

        return $this->responseFactory->createResponse($errorCode);

        // return $response
        //     ->withHeader('Access-Control-Allow-Credentials', 'true')
        //     ->withHeader('Access-Control-Allow-Origin', $origin)
        //     ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        //     ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS')
        //     ->withHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
        //     ->withAddedHeader('Cache-Control', 'post-check=0, pre-check=0')
        //     ->withHeader('Pragma', 'no-cache')
        // ;
    }
}
