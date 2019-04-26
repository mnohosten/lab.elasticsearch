<?php

use Atar\Web\Application;
use Atar\Web\Container\Factory;

require __DIR__ . '/../vendor/autoload.php';

$container = (new Factory())
    ->create(
        require __DIR__ . '/../config/providers.php'
    );
/** @var $application Application */
$application = $container->get(Application::class);
$application->run();
