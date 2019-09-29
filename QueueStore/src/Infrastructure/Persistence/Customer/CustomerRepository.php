<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Customer;


use App\Domain\Customer\CustomerRepositoryInterface;
use App\Domain\Customer\Customer;
use Slim\Factory\AppFactory;


class CustomerRepository implements CustomerRepositoryInterface
{

    private function checkWithProbability($probability=0.1, $length=10000)
    {
        $test = mt_rand(1, $length);
        return $test<=$probability*$length;
    }

    public function get()
    {
        $app = AppFactory::create();
        $settings = $app->getContainer()->get('settings');

       if ($this->checkWithProbability($this->checkWithProbability($settings['flowRate']))){
        return new Customer($settings['timeForGoods'],$settings['timeForMoney']);
       } else {
           return null;
       }
    }
}
