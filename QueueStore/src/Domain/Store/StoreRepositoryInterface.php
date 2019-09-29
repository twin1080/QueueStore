<?php
declare(strict_types=1);

namespace App\Domain\Store;

interface StoreRepositoryInterface
{
    /**
     * @return Store
     */
    public function create(): Store;

}
