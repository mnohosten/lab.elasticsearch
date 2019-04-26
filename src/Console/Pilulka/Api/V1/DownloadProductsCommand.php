<?php
declare(strict_types=1);

namespace Pilulka\Lab\Elasticsearch\Console\Pilulka\Api\V1;

use GuzzleHttp\Client;
use function GuzzleHttp\Promise\settle;
use GuzzleHttp\Psr7\Response;
use Pilulka\Lab\Elasticsearch\Config;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DownloadProductsCommand extends Command
{
    /**
     * @var Config
     */
    private $config;


    /**
     * DownloadProductsCommand constructor.
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('pilulka:api:v1:donwload-products');
        $this->setDescription('Download whole product database from Pilulka API V1.');
    }


    public function execute(InputInterface $input, OutputInterface $output)
    {
        $client = $this->getClient();
        $i = 0;
        $limit = 500;
        $promises = [];
        $file  = fopen($this->config->get('pilulka.api.v1.storage_path') . '/products.json', 'w');
        while (true) {
            $offset = $i * $limit;
            $promises[] = $client->getAsync("/api/v1/products?limit=500&offset={$offset}");
            $i++;
            $hasEmptyResponse = false;
            if (count($promises) >= 5) {
                $items = settle($promises)->wait();
                $promises = [];
                foreach ($items as $item) {
                    if($item['state'] == 'fulfilled') {
                        /** @var Response $response */
                        $response = $item['value'];
                        $products = json_decode($response->getBody()->getContents(), true);
                        if(!count($products) && !$hasEmptyResponse) {
                            $hasEmptyResponse = true;
                        }
                        foreach ($products as $product) {
                            $output->writeln("Processed {$product['name']}, pdk: {$product['pdk']}");
                            fputs( $file, json_encode([
                                'index' => [
                                    '_index' => 'pilulka',
                                    '_type' => 'products',
                                    '_id' => $product['id'],
                                ]
                                ]) . "\n");
                            fputs( $file, json_encode($product) . "\n");
                        }
                    }
                }
            }
            if($hasEmptyResponse) break;
        }
        fclose($file);
    }

    /**
     * @return Client
     */
    private function getClient(): Client
    {
        $client = new Client([
            'base_uri' => 'http://ipilulka.cz',
            'auth' => ['pdp', 'a8b4Cjk$~'],
        ]);
        return $client;
    }

}
