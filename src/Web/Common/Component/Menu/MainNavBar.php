<?php
declare(strict_types=1);

namespace Pilulka\Lab\Elasticsearch\Web\Common\Component\Menu;

use Pilulka\Lab\Elasticsearch\Service\Catalog\CategoryTreeService;
use Pilulka\Lab\Elasticsearch\Web\Component;
use Twig\Environment;

class MainNavBar implements Component
{
    /**
     * @var Environment
     */
    private $twig;
    /**
     * @var CategoryTreeService
     */
    private $treeService;


    /**
     * MainNavBar constructor.
     */
    public function __construct(
        Environment $twig,
        CategoryTreeService $treeService
    )
    {
        $this->twig = $twig;
        $this->treeService = $treeService;
    }

    public function render(array $args): string
    {
        return $this->twig->render(
            'common/component/main.navbar.twig',
            [
                'categories' => $this->treeService->fullTree(),
            ]
        );
    }

}