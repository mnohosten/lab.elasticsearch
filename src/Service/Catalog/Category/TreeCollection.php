<?php
declare(strict_types=1);

namespace Pilulka\Lab\Elasticsearch\Service\Catalog\Category;

class TreeCollection extends \ArrayIterator
{
    /**
     * @var array
     */
    private $bin;

    /**
     * CategoryNode constructor.
     */
    public function __construct(array &$bin, $key = null)
    {
        parent::__construct($bin[$key]);
        $this->bin = $bin;
    }

    public function current()
    {
        return new TreeNode(
            $this->bin,
            parent::current()
        );
    }


}