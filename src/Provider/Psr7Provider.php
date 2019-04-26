<?php
declare(strict_types=1);

namespace Pilulka\Lab\Elasticsearch\Provider;

use League\Container\Container;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Psr\Http\Message\RequestInterface;
use Zend\Diactoros\ServerRequestFactory;

/**
 * Class Psr7Provider
 * @package Sameday\Provider
 * @property Container $container
 */
class Psr7Provider extends AbstractServiceProvider
{
    protected $provides = [
        RequestInterface::class,
    ];

    public function register()
    {
        $this->container->share(
            RequestInterface::class,
            function () {
                return ServerRequestFactory::fromGlobals();
            }
        );
    }
}
