<?php
declare(strict_types=1);

namespace Tests\Domain\Store;

use App\Domain\Customer\Customer;
use App\Domain\Store\Cashbox;
use App\Domain\Store\CashboxClosedException;
use Tests\TestCase;

class CashboxTest extends TestCase
{
    public function testEmptyCashbox()
    {
        $cashbox = new Cashbox(1, 600);

        $expectedPayload = json_encode([
            'name' => '1',
            'state' =>  'Closed',
            'length' => 0,
            'idle' => 0,
            'currentCustomer'=> null]);

        $this->assertEquals($expectedPayload, json_encode($cashbox));
    }


    public function testClosedCashbox()
    {
        $this->expectException(CashboxClosedException::class);

        $cashbox = new Cashbox(1, 600);


        $cashbox->enqueue(new Customer(10, 60, 1));
    }

    public function testOpen()
    {
        $cashbox = new Cashbox(1, 600);

        $cashbox->open();

        $expectedPayload = json_encode([
            'name' => '1',
            'state' =>  'Open',
            'length' => 0,
            'idle' => 0,
            'currentCustomer'=> null]);

        $this->assertEquals($expectedPayload, json_encode($cashbox));
    }


    public function testPush()
    {
        $cashbox = new Cashbox(1, 600);
        $cashbox->open();
        $cashbox->enqueue(new Customer(10, 60, 1));
        $cashbox->enqueue(new Customer(10, 60,2));



        $expectedPayload = json_encode([
            'name' => '1',
            'state' =>  'Open',
            'length' => 2,
            'idle' => 0,
            'currentCustomer'=> [
                'goods' => 1,
                'timeToReady' => 70
            ]]);

        $this->assertEquals($expectedPayload, json_encode($cashbox));
    }

    public function testTickEmptyClosed() {
        $cashbox = new Cashbox(1, 600);


        $cashbox->tik();


        $expectedPayload = json_encode([
            'name' => '1',
            'state' =>  'Closed',
            'length' => 0,
            'idle' => 0,
            'currentCustomer'=> null]);

        $this->assertEquals($expectedPayload, json_encode($cashbox));
    }

    public function testTickEmptyOpen() {
        $cashbox = new Cashbox(1, 600);

        $cashbox->open();

        $cashbox->tik();

        $expectedPayload = json_encode([
            'name' => '1',
            'state' =>  'Open',
            'length' => 0,
            'idle' => 1,
            'currentCustomer'=> null]);

        $this->assertEquals($expectedPayload, json_encode($cashbox));
    }

    public function testTickEmptyOpenDropIdle() {
        $cashbox = new Cashbox(1, 600);

        $cashbox->open();

        $cashbox->tik();
        $cashbox->tik();
        $cashbox->tik();
        $cashbox->tik();
        $cashbox->tik();
        $cashbox->tik();

        $cashbox->enqueue(new Customer(10, 60,1));

        $expectedPayload = json_encode([
            'name' => '1',
            'state' =>  'Open',
            'length' => 1,
            'idle' => 0,
            'currentCustomer'=> [
                'goods' => 1,
                'timeToReady' => 70
            ]]);

        $this->assertEquals($expectedPayload, json_encode($cashbox));
    }


    public function testTickIdleOverflow() {
        $cashbox = new Cashbox(1, 600);

        $cashbox->open();

        for ($i = 0; $i <= 600; $i++) {
        $cashbox->tik();
        }

        $expectedPayload = json_encode([
            'name' => '1',
            'state' =>  'Closed',
            'length' => 0,
            'idle' => 0,
            'currentCustomer'=> null]);

        $this->assertEquals($expectedPayload, json_encode($cashbox));
    }

    public function testTicksWithCustomer() {
        $cashbox = new Cashbox(1, 600);

        $cashbox->open();

        $cashbox->enqueue(new Customer(10, 60, 1));

        for ($i = 0; $i < 70; $i++) {
            $cashbox->tik();
        }

        $expectedPayload = json_encode([
            'name' => '1',
            'state' =>  'Open',
            'length' => 0,
            'idle' => 0,
            'currentCustomer'=> null]);

        $this->assertEquals($expectedPayload, json_encode($cashbox));
    }




}
