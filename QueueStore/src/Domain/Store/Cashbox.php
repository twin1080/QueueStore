<?php


namespace App\Domain\Store;


use App\Domain\Customer\Customer;
use phpDocumentor\Reflection\Types\This;
use Slim\Factory\AppFactory;
use function DI\string;

class Cashbox implements \JsonSerializable
{
    const CLOSED = 0;
    const OPEN = 1;
    protected $name;

    protected $state = self::CLOSED;

    protected $queue;

    protected $idle = 0;

    protected $max_idle = 600;

    /**
     * @var Customer
     */
    protected $currentCustomer;

    /**
     * Cashbox constructor.
     * @param $name
     */
    public function __construct($name, $maxIdle)
    {
        $this->name = (string)$name;
        $this->queue = new \SplQueue();

        $this->max_idle = $maxIdle;
    }

    public function open() {
        $this->state = self::OPEN;
    }

    public function isClosed() {
        return $this->state === self::CLOSED;
    }

    /**
     * @return int
     */
    public function getState(): int
    {
        return $this->state;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param Customer $customer
     * @throws CashboxClosedException
     */
    public function enqueue(Customer $customer)
    {
        if ($this->isClosed()) {
            throw new CashboxClosedException();
        }

        if (!$this->currentCustomer) {
            $this->currentCustomer = $customer;
        } else {
            $this->queue->enqueue($customer);
        }
        $this->idle = 0;
    }

    public function getLength()
    {
        return $this->queue->count() + ($this->currentCustomer ? 1 : 0);
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
        return [
            'name' => $this->name,
            'state' => $this->isClosed() ? 'Closed' : 'Open',
            'length' => $this->getLength(),
            'idle' => $this->idle,
            'currentCustomer' => $this->currentCustomer,
        ];
    }


    public function tik()
    {
        if (!$this->isClosed()) {
        if ($this->currentCustomer) {
            if ($this->currentCustomer->getTimeToReady() <= 0) {
                if (!$this->queue->isEmpty()) {
                    $this->currentCustomer = $this->queue->dequeue();
                } else{
                    $this->currentCustomer = null;
                }
            }
        } else {
            if ($this->queue->isEmpty()){
                if ($this->idle >= $this->max_idle) {
                    $this->idle = 0;
                    $this->state = self::CLOSED;
                } else {
                    $this->idle++;
                }
            } else {
                $this->currentCustomer = $this->queue->dequeue();
            }
        }



        }
    }
}