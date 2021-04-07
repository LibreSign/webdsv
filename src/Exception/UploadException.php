<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;

class UploadException extends AppException
{
    /** @var int */
    private $errorCode;

    public function __construct(int $code, Exception $previous = null)
    {
        $message = 'Erro no upload de arquivos';
        switch ($code) {
            case UPLOAD_ERR_NO_FILE:
            case UPLOAD_ERR_PARTIAL:
                $message = 'Nenhum arquivo foi enviado ou envio foi parcial!';
                break;
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $message = 'Tamanho do arquivo excede o limite máximo!';
                break;
            default:
                $message = 'Erro no upload de arquivos';
                break;
        }
        parent::__construct($message ?? 'Erro no upload de arquivos', 400, $previous);

        $this->errorCode = $code;
    }

    public function getErrorCode(): int
    {
        return $this->errorCode;
    }

    public function jsonSerialize()
    {
        return array_merge(parent::jsonSerialize(), [
            'uploadErrorCode' => $this->getErrorCode(),
        ]);
    }
}
