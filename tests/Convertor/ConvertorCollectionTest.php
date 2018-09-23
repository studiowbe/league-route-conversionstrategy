<?php

namespace Studiow\LeagueRoute\Strategy\Test\Convertor;

use stdClass;
use Studiow\LeagueRoute\Strategy\Convertor\ConvertorCollection;
use Studiow\LeagueRoute\Strategy\Convertor\HtmlConvertor;
use Studiow\LeagueRoute\Strategy\Convertor\JsonConvertor;
use Studiow\LeagueRoute\Strategy\Test\ConversionTestCase;

class ConvertorCollectionTest extends ConversionTestCase
{
    public function testEmptyConvertorCannotConvertAnything()
    {
        $convertor = new ConvertorCollection();
        $this->assertFalse($convertor->canConvert([]));
        $this->assertFalse($convertor->canConvert(new stdClass()));
        $this->assertFalse($convertor->canConvert('test'));
        $this->assertFalse($convertor->canConvert(1));
        $this->assertFalse($convertor->canConvert(true));
    }

    public function testConvertorContainsConvertors()
    {
        $text_data = 'test';
        $array_data = ['foo' => 'bar'];
        $html_convertor = new HtmlConvertor(
            $this->createMockResponseFactory(
                $this->createMockResponse('text/html; charset=UTF-8', $text_data)
            ),
            'text/html; charset=UTF-8'
        );

        $json_convertor = new JsonConvertor(
            $this->createMockResponseFactory(
                $this->createMockResponse('text/html; charset=UTF-8', json_encode($array_data))
            ),
            'text/html; charset=UTF-8'
        );

        $convertor = new ConvertorCollection([$html_convertor, $json_convertor]);
        // matched by $json_convertor
        $this->assertTrue($convertor->canConvert([]));
        $this->assertTrue($convertor->canConvert(new stdClass()));

        // matched by $html_convertor
        $this->assertTrue($convertor->canConvert('test'));
        $this->assertTrue($convertor->canConvert(1));
        $this->assertTrue($convertor->canConvert(true));
    }
}
