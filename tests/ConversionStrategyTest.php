<?php

namespace Studiow\LeagueRoute\Strategy\Test;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Studiow\LeagueRoute\Strategy\ConversionStrategy;
use Studiow\LeagueRoute\Strategy\Convertor\JsonConvertor;
use TypeError;

class ConversionStrategyTest extends ConversionTestCase
{
    /**
     * If the result is a Response already, we just want to forward it.
     */
    public function testStrategyForwardsResponse()
    {
        $expectedResponse = $this->createMock(ResponseInterface::class);

        $route = $this->createMockRouteWithCallback($expectedResponse);

        $strategy = new ConversionStrategy();
        $response = $strategy->invokeRouteCallable($route, $this->createMock(ServerRequestInterface::class));
        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testStrategyThrowsExceptionForInvalidReturn()
    {
        $route = $this->createMockRouteWithCallback('string return');
        $strategy = new ConversionStrategy();
        $this->expectException(TypeError::class);
        $strategy->invokeRouteCallable($route, $this->createMock(ServerRequestInterface::class));
    }

    public function testStrategyUsesConvertor()
    {
        $data = ['foo' => 'bar'];

        $route = $this->createMockRouteWithCallback($data);
        $contentType = 'application/json; charset=UTF-8';

        $expectedResponse = $this->createMockResponse($contentType, json_encode($data));
        $responseFactory = $this->createMockResponseFactory($expectedResponse);

        $strategy = new ConversionStrategy(new JsonConvertor($responseFactory, $contentType));
        $response = $strategy->invokeRouteCallable($route, $this->createMock(ServerRequestInterface::class));

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals($contentType, $response->getHeader('content-type'));
        $this->assertEquals(json_encode($data), $response->getBody()->getContents());
    }
}
