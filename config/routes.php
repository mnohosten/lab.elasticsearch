<?php
declare(strict_types=1);

return [
    // --- COMMON
    'common.dashboard' => ['GET', '/', \Pilulka\Lab\Elasticsearch\Web\Common\Action\ViewDashboardAction::class],
];
