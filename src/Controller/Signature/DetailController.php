<?php

declare(strict_types=1);

namespace App\Controller\Signature;

use App\Exception\AppException;
use App\Exception\UploadException;
use App\Service\DsvService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Log\LoggerInterface;
use Slim\Views\Twig;

class DetailController
{
    /** @var LoggerInterface */
    protected $logger;

    /** @var Twig */
    protected $view;

    /** @var DsvService */
    private $dsvService;

    /** @var string */
    private $uploadDir;

    public function __construct(LoggerInterface $logger, Twig $view, DsvService $dsvService, string $uploadDir)
    {
        $this->logger = $logger;
        $this->view = $view;
        $this->dsvService = $dsvService;
        $this->uploadDir = $uploadDir;
    }

    public function route(Request $request, Response $response, array $args): Response
    {
        $data = [];
        /** @var UploadedFileInterface[] */
        $uploadedFiles = $request->getUploadedFiles();
        $uploadedFile = $uploadedFiles['pdf'];
        $errorCode = $uploadedFile->getError();
        if (UPLOAD_ERR_OK !== $errorCode) {
            throw new UploadException($errorCode);
        }
        $fullPath = $this->moveUploadedFile($uploadedFile);
        $data['signatures'] = $this->dsvService->getSignatures($fullPath);
        unlink($fullPath);
        
        if (empty($data['signatures'])) {
            throw new AppException('Nenhuma assinatura encontrada!', 400);
        }

        return $this->view->render($response, 'index.html.twig', [
            'data' => $data,
        ]);
    }

    private function moveUploadedFile(UploadedFileInterface $uploadedFile)
    {
        try {
            $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
            // see http://php.net/manual/en/function.random-bytes.php
            $basename = bin2hex(random_bytes(8));
            $filename = sprintf('%s.%0.8s', $basename, $extension);
            $fullPath = $this->uploadDir.$filename;

            $uploadedFile->moveTo($fullPath);

            return $fullPath;
        } catch (\Exception $exception) {
            throw new AppException('Erro ao ler arquivo enviado!', 400, $exception);
        }
    }
}
