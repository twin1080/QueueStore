<?php
declare(strict_types=1);

use App\Domain\Customer\CustomerRepositoryInterface;
use App\Domain\Store\StoreRepositoryInterface;
use App\Infrastructure\Persistence\Customer\CustomerRepository;
use App\Infrastructure\Persistence\Store\StoreRepository;
use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    // Here we map our UserRepository interface to its in memory implementation
    $containerBuilder->addDefinitions([
        StoreRepositoryInterface::class => \DI\autowire(StoreRepository::class),
        CustomerRepositoryInterface::class => \DI\autowire(CustomerRepository::class)
    ]);
};
