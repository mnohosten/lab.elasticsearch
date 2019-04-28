<?php
declare(strict_types=1);

namespace Pilulka\Lab\Elasticsearch\Infrastructure\Persistence\Elasticsearch;

use Elasticsearch\Client;
use function Lambdish\Phunctional\map;
use Pilulka\Lab\Elasticsearch\Model\Catalog\Category\CategoryRepository;

class ElasticsearchCategoryRepository implements CategoryRepository
{
    /**
     * @var Client
     */
    private $client;


    /**
     * ElasticsearchCategoryRepository constructor.
     */
    public function __construct(
        Client $client
    )
    {
        $this->client = $client;
    }

    public function all(): array
    {
        return map(
            function ($hit) {
                return $hit['_source'];
            },
            $this->allHitsArray()
        );
    }

    private function allHitsArray()
    {
        return $this->client->search([
            'index' => 'category',
            'type' => 'category',
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            "term" => ["isActive" => ["value" => true]]
                        ]
                    ]
                ]
            ],
            'size' => 10000,
        ])['hits']['hits'];
    }

}
