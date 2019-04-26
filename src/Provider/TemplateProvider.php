<?php
declare(strict_types=1);

namespace Pilulka\Lab\Elasticsearch\Provider;

use Atar\Web\LinkGenerator;
use League\Container\Container;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Pilulka\Lab\Elasticsearch\Config;
use Symfony\Component\Translation\Loader\PhpFileLoader;
use Symfony\Component\Translation\Translator;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFilter;

/**
 * Class TemplateProvider
 * @package Sameday\Provider
 * @property Container $container
 */
class TemplateProvider extends AbstractServiceProvider
{
    protected $provides = [
        Environment::class,
        TranslatorInterface::class
    ];
    /**
     * @var Config
     */
    private $config;

    /**
     * TemplateProvider constructor.
     */
    public function __construct(
        Config $config
    )
    {
        $this->config = $config;
    }


    public function register()
    {
        $this->container->share(
            TranslatorInterface::class,
            function () {
                $translator = new Translator(
                    $this->config->get('translator.default.locale'),
                    null,
                    $this->config->get('translator.cache_dir'),
                    $this->config->get('app.debug')
                );
                $translator->addLoader('php', new PhpFileLoader());
                foreach ($this->config->get('translator.locales') as $locale) {
                    $translator->addResource(
                        'php',
                        $this->config->get('translator.resource_dir') . '/' . $locale . '.php',
                        $locale
                    );
                }
                return $translator;
            }
        );
        $this->addTwig();
    }

    public function addTwig(): void
    {
        $this->container->share(
            Environment::class,
            function () {
                $loader = new FilesystemLoader([
                    __DIR__ . '/../../resources/templates/'
                ]);
                $twig = new Environment(
                    $loader,
                    [
                        'cache' => $this->config->get('templates.cache_dir'),
                        'debug' => $this->config->get('app.debug'),
                    ]
                );

                /** @var TranslatorInterface $translator */
                $translator = $this->container->get(TranslatorInterface::class);
                $twig->addFilter(
                    new TwigFilter('trans', function ($translationKey) use ($translator) {
                        return $translator->trans($translationKey);
                    })
                );

                /** @var LinkGenerator $linkGenerator */
                $linkGenerator = $this->container->get(LinkGenerator::class);
                $twig->addFilter(
                    new TwigFilter('link', function ($key, $params=[]) use ($linkGenerator) {
                        return $linkGenerator->generate($key, $params);
                    })
                );

                return $twig;
            }
        );
    }


}
