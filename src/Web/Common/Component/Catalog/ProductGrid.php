<?php
declare(strict_types=1);

namespace Pilulka\Lab\Elasticsearch\Web\Common\Component\Catalog;

use Pilulka\Lab\Elasticsearch\Service\Catalog\ProductGridService;
use Pilulka\Lab\Elasticsearch\Web\Component;
use Twig\Environment;

class ProductGrid implements Component
{
    /**
     * @var Environment
     */
    private $twig;
    /**
     * @var ProductGridService
     */
    private $gridService;

    public function __construct(
        Environment $twig,
        ProductGridService $gridService
    )
    {
        $this->twig = $twig;
        $this->gridService = $gridService;
    }

    public function render(array $args): string
    {
        return $this->twig->render(
            'common/component/catalog.grid.twig',
            [
                'table' => $this->gridService->table($args),
            ]
        );
    }

}