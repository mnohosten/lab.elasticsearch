<?php
declare(strict_types=1);

namespace Pilulka\Lab\Elasticsearch\Web\Common\Action;

use Atar\Web\Action;
use Atar\Web\VariableCollection;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment;
use Zend\Diactoros\CallbackStream;
use Zend\Diactoros\Response;

class ViewDashboardAction implements Action
{
    /**
     * @var Environment
     */
    private $twig;


    /**
     * ViewDashboardAction constructor.
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function __invoke(RequestInterface $request, VariableCollection $variables): ResponseInterface
    {
        return new Response\HtmlResponse(new CallbackStream(function() {
            echo $this->twig->render('common/dashboard.twig', []);
        }));
    }
}
