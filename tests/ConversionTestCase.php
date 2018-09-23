<?php

namespace Studiow\LeagueRoute\Strategy\Test;

use League\Route\Route;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;

abstract class ConversionTestCase extends TestCase
{
    /**
     * Create a Route object which will return $expectedResponse.
     *
     * @param mixed $expectedResponse
     *
     * @return Route
     */
    protected function createMockRouteWithCallback($expectedResponse): Route
    {
        $route = $this->createMock(Route::class);
        $route
            ->expects($this->any())
            ->method('getCallable')
            ->will(
                $this->returnValue(
                    function (ServerRequestInterface $request) use ($expectedResponse) {
                        return $expectedResponse;
                    })
            );

        return $route;
    }

    /**
     * Create a ResponseFactory which will return $expectedResponse.
     *
     * @param ResponseInterface $expectedResponse
     *
     * @return ResponseFactoryInterface
     */
    protected function createMockResponseFactory(ResponseInterface $expectedResponse): ResponseFactoryInterface
    {
        $factory = $this->createMock(ResponseFactoryInterface::class);
        $factory
            ->expects($this->any())
            ->method('createResponse')
            ->willReturn($expectedResponse);

        return $factory;
    }

    /**
     * Create a Response with a given content-type and body.
     *
     * @param string $contentType
     * @param string $bodyText
     *
     * @return ResponseInterface
     */
    protected function createMockResponse(string $contentType, string $bodyText): ResponseInterface
    {
        $response = $this->createMock(ResponseInterface::class);

        $response
            ->expects($this->any())
            ->method('withAddedHeader')
            ->with($this->equalTo('content-type'), $this->equalTo($contentType))
            ->will($this->returnSelf());

        $response
            ->expects($this->any())
            ->method('getHeader')
            ->with($this->equalTo('content-type'))
            ->will($this->returnValue($contentType));

        $response
            ->expects($this->any())
            ->method('withStatus')
            ->will($this->returnSelf());

        $body = $this->createMock(StreamInterface::class);

        $body
            ->expects($this->any())
            ->method('write')
            ->with($this->equalTo($bodyText));

        $body
            ->expects($this->any())
            ->method('getContents')
            ->willReturn($bodyText);

        $response
            ->expects($this->any())
            ->method('getBody')
            ->will($this->returnValue($body));

        return $response->withAddedHeader('content-type', $contentType);
    }
}
