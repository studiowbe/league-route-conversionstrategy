<?php

namespace Studiow\LeagueRoute\Strategy\Test\Convertor;

use InvalidArgumentException;
use Psr\Http\Message\ResponseFactoryInterface;
use stdClass;
use Studiow\LeagueRoute\Strategy\Convertor\JsonConvertor;
use Studiow\LeagueRoute\Strategy\Test\ConversionTestCase;

class JsonConvertorTest extends ConversionTestCase
{
    public function testConvertorCanConvertOnlyArrayOrObject()
    {
        $convertor = new JsonConvertor($this->createMock(ResponseFactoryInterface::class));

        $this->assertTrue($convertor->canConvert([]));
        $this->assertTrue($convertor->canConvert(new stdClass()));
        $this->assertFalse($convertor->canConvert('test'));
        $this->assertFalse($convertor->canConvert(1));
        $this->assertFalse($convertor->canConvert(true));
    }

    public function testConvertorConvertsArrayToJson()
    {
        $data = ['foo' => 'bar'];
        $contentType = 'application/json; charset=UTF-8';
        $expectedResponse = $this->createMockResponse($contentType, json_encode($data));
        $responseFactory = $this->createMockResponseFactory($expectedResponse);

        $convertor = new JsonConvertor($responseFactory, $contentType);

        $response = $convertor->convert($data);

        $this->assertEquals($contentType, $response->getHeader('content-type'));
        $this->assertEquals(json_encode($data), $response->getBody()->getContents());
    }

    public function testConvertorConvertsObjectToJson()
    {
        $data = new stdClass();
        $data->foo = 'bar';

        $contentType = 'application/json; charset=UTF-8';
        $expectedResponse = $this->createMockResponse($contentType, json_encode($data));
        $responseFactory = $this->createMockResponseFactory($expectedResponse);

        $convertor = new JsonConvertor($responseFactory, $contentType);

        $response = $convertor->convert($data);

        $this->assertEquals($contentType, $response->getHeader('content-type'));
        $this->assertEquals(json_encode($data), $response->getBody()->getContents());
    }

    public function testConvertorThrowsExceptionForInvalidInput()
    {
        $convertor = new JsonConvertor($this->createMock(ResponseFactoryInterface::class));
        $this->expectException(InvalidArgumentException::class);
        $convertor->convert('test');
    }
}
