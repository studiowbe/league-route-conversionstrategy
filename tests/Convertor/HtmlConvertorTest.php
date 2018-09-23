<?php

namespace Studiow\LeagueRoute\Strategy\Test\Convertor;

use InvalidArgumentException;
use Psr\Http\Message\ResponseFactoryInterface;
use stdClass;
use Studiow\LeagueRoute\Strategy\Convertor\HtmlConvertor;
use Studiow\LeagueRoute\Strategy\Test\ConversionTestCase;

class HtmlConvertorTest extends ConversionTestCase
{
    public function testConvertorCanConvertOnlyScalars()
    {
        $convertor = new HtmlConvertor($this->createMock(ResponseFactoryInterface::class));
        $this->assertFalse($convertor->canConvert([]));
        $this->assertFalse($convertor->canConvert(new stdClass()));
        $this->assertTrue($convertor->canConvert('test'));
        $this->assertTrue($convertor->canConvert(1));
        $this->assertTrue($convertor->canConvert(true));
    }

    public function testConvertorConvertsToHtml()
    {
        $content = 'test';
        $contentType = 'text/html; charset=UTF-8';

        $expectedResponse = $this->createMockResponse($contentType, $content);
        $responseFactory = $this->createMockResponseFactory($expectedResponse);

        $convertor = new HtmlConvertor($responseFactory, $contentType);

        $response = $convertor->convert($content);
        $this->assertEquals($contentType, $response->getHeader('content-type'));
        $this->assertEquals($content, $response->getBody()->getContents());
    }

    public function testConvertorThrowsExceptionForInvalidInput()
    {
        $convertor = new HtmlConvertor($this->createMock(ResponseFactoryInterface::class));
        $this->expectException(InvalidArgumentException::class);
        $convertor->convert([]);
    }
}
