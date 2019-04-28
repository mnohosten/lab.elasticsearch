<?php
declare(strict_types=1);

namespace Pilulka\Lab\Elasticsearch\Web\Common\Action;

use Atar\Web\Action;
use Atar\Web\VariableCollection;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment;
use Zend\Diactoros\Response\HtmlResponse;

class ViewCatalogAction implements Action
{
    /**
     * @var Environment
     */
    private $twig;


    /**
     * ViewCatalogAction constructor.
     * @param Environment $twig
     */
    public function __construct(
        Environment $twig
    )
    {
        $this->twig = $twig;
    }

    public function __invoke(RequestInterface $request, VariableCollection $variables): ResponseInterface
    {
        return new HtmlResponse(
            $this->twig->render(
                'common/catalog.twig',
                [
                    'categoryId' => $variables->get('id')
                ]
            )
        );
    }


}