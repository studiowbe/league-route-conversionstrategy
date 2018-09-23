<?php

namespace Studiow\LeagueRoute\Strategy\Convertor;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Studiow\LeagueRoute\Strategy\Convertor;

abstract class ResponseFactoryAwareConvertor implements Convertor
{
    protected $responseFactory;

    public function __construct(ResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    protected function createResponse(int $status = 200, string $contentType = 'text/html', string $body = ''): ResponseInterface
    {
        $response = $this->responseFactory
            ->createResponse()
            ->withStatus($status)
            ->withAddedHeader('content-type', $contentType);

        $response->getBody()->write($body);

        return $response;
    }
}
