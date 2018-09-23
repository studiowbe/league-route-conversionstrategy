<?php

namespace Studiow\LeagueRoute\Strategy;

use League\Route\Route;
use League\Route\Strategy\ApplicationStrategy;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TypeError;

class ConversionStrategy extends ApplicationStrategy
{
    protected $convertor;

    public function __construct(Convertor $convertor = null)
    {
        $this->setConvertor($convertor);
    }

    public function setConvertor(Convertor $convertor = null)
    {
        $this->convertor = $convertor;
    }

    public function getConvertor(): ?Convertor
    {
        return $this->convertor;
    }

    /**
     * @param Route                  $route
     * @param ServerRequestInterface $request
     *
     * @throws TypeError
     *
     * @return ResponseInterface
     */
    public function invokeRouteCallable(Route $route, ServerRequestInterface $request): ResponseInterface
    {
        $result = call_user_func_array($route->getCallable($this->getContainer()), [$request, $route->getVars()]);

        //result is already a Response object, we can return this immediately
        if ($result instanceof ResponseInterface) {
            return $result;
        }

        //If we have a convertor that can convert the result, let it convert the result
        if (! is_null($this->getConvertor()) && $this->getConvertor()->canConvert($result)) {
            return $this->getConvertor()->convert($result);
        }

        //return the result unmodified, this will throw a TypeError
        return $result;
    }
}
