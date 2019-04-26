<?php
declare(strict_types=1);

namespace Pilulka\Lab\Elasticsearch\Console;

use Pilulka\Lab\Elasticsearch\Config;
use Psr\Container\ContainerInterface;

class Application
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Application constructor.
     */
    public function __construct(
        ContainerInterface $container
    )
    {
        $this->container = $container;
    }

    public function run(): void
    {
        $application = new \Symfony\Component\Console\Application();
        /** @var Config $config */
        $config = $this->container->get(Config::class);
        foreach ($config->get('console.commands') as $commandClass) {
            $application->add($this->container->get($commandClass));
        }
        $application->run();
    }

}
