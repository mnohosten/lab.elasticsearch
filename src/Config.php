<?php
declare(strict_types=1);

namespace Pilulka\Lab\Elasticsearch;

use Webmozart\Assert\Assert;

class Config
{
    /**
     * @var array
     */
    private $config;

    /**
     * Config constructor.
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function get(string $key)
    {
        Assert::keyExists($this->config, $key);
        return $this->config[$key];
    }
}
