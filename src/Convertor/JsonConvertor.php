<?php

namespace Studiow\LeagueRoute\Strategy\Convertor;

use InvalidArgumentException;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;

class JsonConvertor extends ResponseFactoryAwareConvertor
{
    protected $contentType;

    public function __construct(ResponseFactoryInterface $responseFactory, string $contentType = 'application/json; charset=UTF-8')
    {
        parent::__construct($responseFactory);
        $this->contentType = $contentType;
    }

    public function canConvert($input): bool
    {
        return is_array($input) || is_object($input);
    }

    public function convert($input): ResponseInterface
    {
        if (! $this->canConvert($input)) {
            throw new InvalidArgumentException('Could not convert input to JSON response');
        }

        return $this->createResponse(200, $this->contentType, json_encode($input));
    }
}
