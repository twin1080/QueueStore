<?php
declare(strict_types=1);

namespace App\Domain\Customer;

use JsonSerializable;
use Slim\Factory\AppFactory;

class Customer implements JsonSerializable
{
    protected $goods = 0;

    protected $timeToReady = 0;

    public function __construct($timeForGoods, $timeForMoney, $goods = null)
    {
        if (!$goods) {
            $this->goods = rand(1, 30);
        } else {
            $this->goods = $goods;
        }



        $this->timeToReady = $goods * $timeForGoods +  $timeForMoney;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return ['goods' => $this->goods,
                'timeToReady' => $this->timeToReady];
    }

    /**
     * @return float|int
     */
    public function  getTimeToReady()
    {
        return --$this->timeToReady;
    }
}