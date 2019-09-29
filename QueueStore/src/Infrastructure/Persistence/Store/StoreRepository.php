<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Store;

use App\Domain\Store\Store;
use App\Domain\Store\StoreRepositoryInterface;
use Slim\Factory\AppFactory;

class StoreRepository implements StoreRepositoryInterface
{
    /**
     * @var Store
     */
    private $store;


    /**
     * @return Store
     */
    public function create(): Store
    {
        $app = AppFactory::create();
        $settings = $app->getContainer()->get('settings');
        return new Store($settings['cashboxCount'], $settings['criticalLength'], $settings['idle']);
    }
}
