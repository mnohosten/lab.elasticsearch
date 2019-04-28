<?php
declare(strict_types=1);

namespace Pilulka\Lab\Elasticsearch\Web;

use Psr\Container\ContainerInterface;
use Webmozart\Assert\Assert;

class ComponentLoader
{
    /**
     * @var array
     */
    private $map;
    /**
     * @var ContainerInterface
     */
    private $container;


    /**
     * ComponentLoader constructor.
     */
    public function __construct(
        ContainerInterface $container,
        array $map = []
    )
    {
        $this->map = $map;
        $this->container = $container;
    }

    public function get(string $key)
    {
        Assert::keyExists($this->map, $key);
        return $this->container->get($this->map[$key]);
    }

}