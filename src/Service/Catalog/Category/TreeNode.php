<?php
declare(strict_types=1);

namespace Pilulka\Lab\Elasticsearch\Service\Catalog\Category;

class TreeNode extends \ArrayIterator
{
    /**
     * @var array
     */
    private $bin;


    /**
     * TreeNode constructor.
     * @param array $bin
     * @param array $item
     */
    public function __construct(array &$bin, array &$item)
    {
        parent::__construct($item);
        $this->bin = $bin;
    }

    public function getChildren(): iterable
    {
        if (is_array($this->bin[$this['id']]))
            return new TreeCollection(
                $this->bin,
                $this['id']
            );
        return [];
    }

}