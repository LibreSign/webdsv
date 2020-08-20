<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;
use JsonSerializable;

class AppException extends \Exception implements JsonSerializable
{
    public function __construct(string $message = 'Ocorreu um erro ao validar o arquivo!', int $code = 500, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'message' => $this->getMessage(),
            'code' => $this->getCode(),
            'traceAsString' => $this->getTraceAsString(),
            'previous' => $this->formatPrevious(),
        ];
    }

    private function formatPrevious()
    {
        $previous = $this->getPrevious();

        if (!$previous instanceof Exception) {
            return [];
        }

        if ($previous instanceof self) {
            return $previous->jsonSerialize();
        }

        return [
            'message' => $previous->getMessage(),
            'code' => $previous->getCode(),
            'traceAsString' => $previous->getTraceAsString(),
        ];
    }
}
