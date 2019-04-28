<?php
declare(strict_types=1);

namespace Pilulka\Lab\Elasticsearch\Provider;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use League\Container\Container;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Pilulka\Lab\Elasticsearch\Infrastructure\Persistence\Elasticsearch\ElasticsearchCategoryRepository;
use Pilulka\Lab\Elasticsearch\Infrastructure\Persistence\Elasticsearch\ElasticsearchProductRepository;
use Pilulka\Lab\Elasticsearch\Model\Catalog\Category\CategoryRepository;
use Pilulka\Lab\Elasticsearch\Model\Catalog\Product\ProductRepository;

/**
 * Class PersistenceProvider
 * @package Pilulka\Lab\Elasticsearch\Provider
 * @property Container $container
 */
class PersistenceProvider extends AbstractServiceProvider
{

    protected $provides = [
        CategoryRepository::class,
        ProductRepository::class,
        Client::class,
    ];

    public function register()
    {
        $this->container->share(
            Client::class,
            function() {
                $builder = ClientBuilder::create();
                $builder->setHosts(['elasticsearch']);
                return $builder->build();
            }
        );
        $this->container->share(
            CategoryRepository::class,
            function() {
                return $this->container->get(
                    ElasticsearchCategoryRepository::class
                );
            }
        );
        $this->container->share(
            ProductRepository::class,
            function() {
                return $this->container->get(
                    ElasticsearchProductRepository::class
                );
            }
        );
    }


}