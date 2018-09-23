<?php

namespace Studiow\LeagueRoute\Strategy\Convertor;

use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Studiow\LeagueRoute\Strategy\Convertor;

class ConvertorCollection implements Convertor
{
    private $convertors = [];

    public function __construct(array $convertors = [])
    {
        array_map([$this, 'append'], $convertors);
    }

    public function append(Convertor $convertor): self
    {
        $this->convertors[] = $convertor;

        return $this;
    }

    public function prepend(Convertor $convertor): self
    {
        array_unshift($this->convertors, $convertor);
    }

    public function canConvert($input): bool
    {
        $convertor = $this->getConvertorFor($input);

        return ! is_null($convertor);
    }

    public function convert($input): ResponseInterface
    {
        $convertor = $this->getConvertorFor($input);
        if (is_null($convertor)) {
            throw new InvalidArgumentException('Could not convert input to response');
        }

        return $convertor->convert($input);
    }

    private function getConvertorFor($input): ?Convertor
    {
        foreach ($this->convertors as $convertor) {
            if ($convertor->canConvert($input)) {
                return $convertor;
            }
        }

        return null;
    }
}
