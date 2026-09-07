<?php

namespace Famoser\Mamapp\Api;

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Psr7\UploadedFile;

class RequestValidatorExtensions
{
    public static function checkApiKey(Request $request): void
    {
        $queryParams = $request->getQueryParams();
        if (!key_exists('apiKey', $queryParams) || $queryParams['apiKey'] !== $_SERVER['API_KEY']) {
            throw new HttpBadRequestException($request, 'invalid api key.');
        }
    }
}
