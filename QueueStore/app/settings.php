<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Monolog\Logger;

return function (ContainerBuilder $containerBuilder) {
    // Global Settings Object
    $containerBuilder->addDefinitions([
        'settings' => [
            'displayErrorDetails' => true, // Should be set to false in production
            'workFrom' => 8, // начало рабочего дня
            'workTill' => 20, // конец рабочего дня
            'idle' => 300, // время простоя кассы после которого она закрывается
            'flowRate' => 0.1, // вероятность появления посетителя в каждую секунду
            'timeForGoods'=> 10, // время на проведение одной покупки
            'timeForMoney'=>60, // время на оплату
            'criticalLength' => 5, // длина очереди при которой открывается новая касса
            'cashboxCount' => 4, // количество касс в магазине
            'logger' => [
                'name' => 'slim-app',
                'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
                'level' => Logger::DEBUG,
            ],
        ],
    ]);
};
