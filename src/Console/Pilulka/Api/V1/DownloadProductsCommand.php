<?php
declare(strict_types=1);

namespace Pilulka\Lab\Elasticsearch\Console\Pilulka\Api\V1;

use GuzzleHttp\Client;
use function GuzzleHttp\Promise\settle;
use GuzzleHttp\Psr7\Response;
use function Lambdish\Phunctional\filter;
use function Lambdish\Phunctional\first;
use function Lambdish\Phunctional\map;
use function Lambdish\Phunctional\reduce;
use Pilulka\Lab\Elasticsearch\Config;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DownloadProductsCommand extends Command
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
        $this->setName('pilulka:api:v1:download-products');
        $this->setDescription('Download whole product database from Pilulka API V1.');
    }


    public function execute(InputInterface $input, OutputInterface $output)
    {
        $client = $this->getClient();
        $i = 0;
        $limit = 500;
        $promises = [];
        $file = fopen($this->config->get('pilulka.api.v1.storage_path') . '/products.json', 'w');
        while (true) {
            $offset = $i * $limit;
            $promises[] = $client->getAsync("/api/v1/products?limit=500&offset={$offset}");
            $i++;
            $hasEmptyResponse = false;
            if (count($promises) >= 5) {
                $items = settle($promises)->wait();
                $promises = [];
                foreach ($items as $item) {
                    if ($item['state'] == 'fulfilled') {
                        /** @var Response $response */
                        $response = $item['value'];
                        $products = map(
                            $this->mapProduct(),
                            json_decode($response->getBody()->getContents(), true)
                        );
                        if (!count($products) && !$hasEmptyResponse) {
                            $hasEmptyResponse = true;
                        }
                        foreach ($products as $product) {
                            $output->writeln("Processed {$product['name']}");
                            fputs($file, json_encode([
                                    'index' => [
                                        '_index' => 'product',
                                        '_type' => 'product',
                                        '_id' => $product['id'],
                                    ]
                                ]) . "\n");
                            fputs($file, json_encode($product) . "\n");
                        }
                    }
                }
            }
            if ($hasEmptyResponse) break;
        }
        fclose($file);
    }

    /**
     * @return \Closure
     */
    private function mapProduct(): \Closure
    {
        return function ($input) {
            $input['price'] = $this->filterPrice($input);

            $input['amountInStock'] = $input['inStock'];
            unset($input['inStock']);

            $input['inBonus'] = $input['inBonus'] == 'yes';
            $input['flags'] = [];
            $this->flag('isInBonus', 'inBonus', $input);
            $this->flag('isInTv', 'inTv', $input);
            $this->flag('isForbiddenByGoogle', 'nogoogle', $input);
            $this->flag('isForbiddenBySodexo', 'noSodexo', $input);
            $this->flag('isForbiddenByEdenred', 'noEdenred', $input);
            $this->flag('isRecommended', 'isRecommended', $input);
            $this->flag('isInAction', 'inAction', $input);
            $this->flag('isFreeSale', 'isFreeSale', $input);
            $this->flag('isGift', 'isGift', $input);
            $this->flag('isNotTestedOnAnimals', 'isNotTestedOnAnimals', $input);
            $this->flag('hasFreeDelivery', 'hasFreeDelivery', $input);
            $this->flag('hasIllustrationPicture', 'hasIllustrationPicture', $input);
            $this->flag('hasExtendedWarranty', 'hasExtendedWarranty', $input);
            $this->flag('hasPilulacek', 'hasPilulacek', $input);

            $this->code('apa', 'apa', $input);
            $this->code('pdk', 'pdk', $input);
            $this->code('ean', 'ean', $input);
            $this->code('sukl', 'sukl', $input);

            $input['pharmacies'] = map(function ($pharmacy) {
                return $pharmacy['id'];
            }, $input['pharmacy']);
            unset($input['pharmacy']);

            $input['categories'] = $input['category'];
            unset($input['category']);

            $input['paramGroups'] = $input['paramGroup'];
            unset($input['paramGroup']);

            if (isset($input['brand'])) {
                $input['brand'] = [
                    'id' => $input['brand']['id'],
                    'name' => $input['brand']['name'],
                    'manufacturer' => $input['brand']['manufacturer'],
                ];
            }
            if (isset($input['manufacturer'])) {
                $input['manufacturer'] = [
                    'id' => $input['manufacturer']['id'],
                    'name' => $input['manufacturer']['name'],
                ];
            }
            if (isset($input['productLine'])) {
                $input['productLine'] = [
                    'id' => $input['productLine']['id'],
                    'name' => $input['productLine']['name'],
                    'manufacturer' => $input['productLine']['manufacturer'],
                    'brand' => $input['productLine']['brand'],
                ];
            }

            return $input;
        };
    }

    private function flag($newKey, $oldKey, &$input)
    {
        $input['flags'][$newKey] = $input[$oldKey];
        unset($input[$oldKey]);
    }

    private function code($newKey, $oldKey, &$input)
    {
        $input['codes'][$newKey] = $input[$oldKey];
        unset($input[$oldKey]);
    }


    private function filterPrice($input)
    {
        $priceItem = first(
            filter(
                function ($price) {
                    return $price['id'] == $this->config->get('pilulka.main_pharmacy_id');
                },
                $input['pharmacy']
            )
        );
        return $priceItem['discountPrice'] > 0 ? $priceItem['discountPrice'] : $priceItem['finalPrice'];
    }

}
