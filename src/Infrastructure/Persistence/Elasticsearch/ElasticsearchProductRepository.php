<?php
declare(strict_types=1);

namespace Pilulka\Lab\Elasticsearch\Infrastructure\Persistence\Elasticsearch;

use Elasticsearch\Client;
use function Lambdish\Phunctional\map;
use Pilulka\Lab\Elasticsearch\Model\Catalog\Product\ProductRepository;

class ElasticsearchProductRepository implements ProductRepository
{
    /**
     * @var Client
     */
    private $client;

    /**
     * ElasticsearchProductRepository constructor.
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function table(array $filter = [])
    {
        return [
            'products' => map(
                function ($hit) {
                    return $hit['_source'];
                },
                $this->hits($filter)
            )
        ];
    }

    /**
     * @return mixed
     */
    private function hits(array $filter)
    {
        return $this->client->search([
            'index' => 'product',
            'type' => 'product',
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            ['term' => ['categories' => ['value' => (int)$filter['category']]]]
                        ]
                    ]
                ]
            ],
            'size' => 20,
        ])['hits']['hits'];
    }

}
