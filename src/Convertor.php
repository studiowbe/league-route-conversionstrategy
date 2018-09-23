<?php

namespace Studiow\LeagueRoute\Strategy;

use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;

interface Convertor
{
    /**
     * Check if this convertor can handle whatever $input is.
     *
     * @param mixed $input
     *
     * @return bool
     */
    public function canConvert($input): bool;

    /**
     * Convert $input to a Response if we can. Otherwise an InvalidArgumentException will be raised.
     *
     * @param mixed $input
     *
     * @throws InvalidArgumentException
     *
     * @return ResponseInterface
     */
    public function convert($input): ResponseInterface;
}
