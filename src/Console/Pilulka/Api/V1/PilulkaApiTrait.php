<?php
declare(strict_types=1);

namespace Pilulka\Lab\Elasticsearch\Console\Pilulka\Api\V1;

use GuzzleHttp\Client;

trait PilulkaApiTrait
{

    /**
     * @return Client
     */
    private function getClient(): Client
    {
        $client = new Client([
            'base_uri' => $this->config->get('pilulka.api.v1.base_uri'),
            'auth' => [
                $this->config->get('pilulka.api.v1.username'),
                $this->config->get('pilulka.api.v1.password'),
            ],
        ]);
        return $client;
    }

}