<?php
declare(strict_types=1);

namespace Pilulka\Lab\Elasticsearch\Service\Catalog;

use Pilulka\Lab\Elasticsearch\Model\Catalog\Product\ProductRepository;

class ProductGridService
{
    /**
     * @var ProductRepository
     */
    private $productRepository;

    public function __construct(
        ProductRepository $productRepository
    )
    {
        $this->productRepository = $productRepository;
    }

    public function table(array $filter = [])
    {
        return $this->productRepository->table($filter);
    }
    
}