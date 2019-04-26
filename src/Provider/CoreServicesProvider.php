<?php
declare(strict_types=1);

namespace Pilulka\Lab\Elasticsearch\Provider;

use Atar\Web\Link\RawWebalizer;
use Atar\Web\LinkWebalizer;
use Atar\Web\RouteCollection;
use League\Container\Container;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Pilulka\Lab\Elasticsearch\Config;

/**
 * Class CoreServicesProvider
 * @package Sameday\Provider
 * @property Container $container
 */
class CoreServicesProvider extends AbstractServiceProvider
{
    protected $provides = [
        RouteCollection::class,
        Config::class,
        LinkWebalizer::class,
    ];

    public function register()
    {
        $this->registerConfig();
        $this->registerRouteCollection();
        $this->registerLinkWebalizer();
    }

    private function registerRouteCollection(): void
    {
        $this->container->share(RouteCollection::class, function () {
            return new RouteCollection(
                require __DIR__ . '/../../config/routes.php'
            );
        });
    }

    public function registerConfig(): void
    {
        $this->container->share(
            Config::class,
            function () {
                return new Config(
                    require __DIR__ . '/../../config/config.production.php'
                );
            }
        );
    }

    public function registerLinkWebalizer(): void
    {
        $this->container->share(
            LinkWebalizer::class,
            function () {
                return new RawWebalizer();
            }
        );
    }
}
