<?php
declare(strict_types=1);

return [
    // --- COMMON
    'common.dashboard' => ['GET', '/', \Pilulka\Lab\Elasticsearch\Web\Common\Action\ViewDashboardAction::class],
    'common.category' => ['GET', '/c/{id}', \Pilulka\Lab\Elasticsearch\Web\Common\Action\ViewCatalogAction::class],
];
