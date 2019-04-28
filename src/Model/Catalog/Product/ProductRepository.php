<?php
declare(strict_types=1);

namespace Pilulka\Lab\Elasticsearch\Model\Catalog\Product;

interface ProductRepository
{
    public function table(array $filter = []);
}
