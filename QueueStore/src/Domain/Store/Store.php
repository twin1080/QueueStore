<?php
declare(strict_types=1);

namespace App\Domain\Store;

use JsonSerializable;
use Slim\Factory\AppFactory;

class Store implements JsonSerializable
{
    protected $criticalLength = 5;

    protected $dailyVisitors = 0;

    /**
     * @var Cashbox[]
     */
    protected $cashboxes;

    public function __construct($cashboxCount, $criticalLength, $idle)
    {
        $this->criticalLength = $criticalLength;
        for ($i = 1; $i <= $cashboxCount; $i++) {
            $this->cashboxes[] = new Cashbox($i, $idle);
        }
    }


    public function addCustomer($customer)
    {
        $this->dailyVisitors++;
        // Отсортируем по заполненности
        usort($this->cashboxes, function (Cashbox $a, Cashbox $b) {return $b->getLength() <=> $a->getLength();});

        // Поставим покупателя в ближайшую свободную кассу
        foreach ($this->cashboxes as $cashbox) {
            if ($cashbox->getState() !== Cashbox::CLOSED) {
                if ($cashbox->getLength() < $this->criticalLength) {
                    $cashbox->enqueue($customer);
                    return;
                }
            }
        }

        // Не нашлось подходящей, все закрыты или все заполнены до пяти
        foreach ($this->cashboxes as $cashbox) {
            if ($cashbox->getState() === Cashbox::CLOSED) {
                $cashbox->open();
                $cashbox->enqueue($customer);
                return;
            }
        }

        // Не нашлось закрытых, значит поставим в последнюю, она самая незагруженная
        if (!end($this->cashboxes)->isClosed()) {
            end($this->cashboxes)->enqueue($customer);
        }
    }

    public function doWork()
    {
        foreach ($this->cashboxes as $cashbox) {
            $cashbox->tik();
        }
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
            'dailyVisitors' => $this->dailyVisitors,
            'cashboxes' => $this->cashboxes
        ];
    }
}