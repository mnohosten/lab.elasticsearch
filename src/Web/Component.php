<?php
declare(strict_types=1);

namespace Pilulka\Lab\Elasticsearch\Web;

interface Component
{

    public function render(array $args): string;

}