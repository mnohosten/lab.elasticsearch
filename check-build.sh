#!/usr/bin/env bash
echo "Running unit tests!"
vendor/bin/phpunit

echo "Running Easy Code Standard!"
vendor/bin/ecs check src/

echo "Running PHP Mess Detector!"
vendor/bin/phpmd src/ text cleancode, codesize, controversial, design, naming, unusedcode

echo "Running PHPStan!"
vendor/bin/phpstan analyze --level max src/
