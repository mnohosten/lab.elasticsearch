<?php
declare(strict_types=1);

namespace Pilulka\Lab\Elasticsearch\Model\Catalog\Category;

interface CategoryRepository
{

    public function all(): array;

}
