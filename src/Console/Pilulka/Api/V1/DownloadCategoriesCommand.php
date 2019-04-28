<?php
declare(strict_types=1);

namespace Pilulka\Lab\Elasticsearch\Console\Pilulka\Api\V1;

use function GuzzleHttp\Promise\settle;
use GuzzleHttp\Psr7\Response;
use function Lambdish\Phunctional\map;
use Pilulka\Lab\Elasticsearch\Config;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DownloadCategoriesCommand extends Command
{

    use PilulkaApiTrait;

    /**
     * @var Config
     */
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
        parent::__construct();
    }


    protected function configure()
    {
        $this->setName('pilulka:api:v1:download-categories');
        $this->setDescription('Download categories from Pilulka API.');
        parent::configure();
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $client = $this->getClient();
        $response = $client->get("/api/v1/categories");
        $items = json_decode($response->getBody()->getContents(), true);
        $file = fopen($this->config->get('pilulka.api.v1.storage_path') . '/categories.json', 'w');
        foreach ($items as $item) {
            $output->writeln("Processed {$item['name']}");
            fputs($file, json_encode([
                    'index' => [
                        '_index' => 'category',
                        '_type' => 'category',
                        '_id' => $item['id'],
                    ]
                ]) . "\n");
            fputs($file, json_encode($item) . "\n");
        }
        fclose($file);
    }


}
