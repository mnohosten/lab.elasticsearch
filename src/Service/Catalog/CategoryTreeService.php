<?php
declare(strict_types=1);

namespace Pilulka\Lab\Elasticsearch\Service\Catalog;

use Pilulka\Lab\Elasticsearch\Model\Catalog\Category\CategoryRepository;
use Pilulka\Lab\Elasticsearch\Service\Catalog\Category\TreeCollection;

class CategoryTreeService
{
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * CategoryTreeService constructor.
     */
    public function __construct(
        CategoryRepository $categoryRepository
    )
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function fullTree()
    {
        $children = [];
        foreach ($this->categoryRepository->all() as $id=>$item) {
            $children[$item['parentId']][] = $item;
        }
        return new TreeCollection($children);
    }

}
