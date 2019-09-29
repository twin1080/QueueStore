<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Monolog\Logger;

return function (ContainerBuilder $containerBuilder) {
    // Global Settings Object
    $containerBuilder->addDefinitions([
        'settings' => [
            'displayErrorDetails' => true, // Should be set to false in production
            'workFrom' => 8, //
            'workTill' => 20,
            'idle' => 300,
            'flowRate' => 0.1,
            'timeForGoods'=> 60,
            'timeForMoney'=>10,
            'criticalLength' => 5,
            'cashboxCount' => 4,
            'logger' => [
                'name' => 'slim-app',
                'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
                'level' => Logger::DEBUG,
            ],
        ],
    ]);
};
