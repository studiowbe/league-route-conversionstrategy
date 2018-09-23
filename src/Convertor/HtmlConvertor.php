<?php

namespace Studiow\LeagueRoute\Strategy\Convertor;

use InvalidArgumentException;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;

class HtmlConvertor extends ResponseFactoryAwareConvertor
{
    protected $contentType;

    public function __construct(ResponseFactoryInterface $responseFactory, string $contentType = 'text/html; charset=UTF-8')
    {
        parent::__construct($responseFactory);
        $this->contentType = $contentType;
    }

    public function canConvert($input): bool
    {
        return is_scalar($input);
    }

    public function convert($input): ResponseInterface
    {
        if (! $this->canConvert($input)) {
            throw new InvalidArgumentException('Could not convert input to HTML response');
        }

        return $this->createResponse(200, $this->contentType, (string) $input);
    }
}
