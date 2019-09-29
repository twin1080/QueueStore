<?php


namespace App\Domain\Store;


class CashboxClosedException extends \Exception
{
    public $message = "Cannot push into closed cashbox";
}