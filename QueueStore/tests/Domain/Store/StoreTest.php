<?php

namespace Tests\Domain\Store;

use App\Domain\Customer\Customer;
use App\Domain\Store\Store;
use PHPUnit\Framework\TestCase;

class StoreTest extends TestCase
{
    public function testDoWorkEmptyStore()
    {
        $store = new Store(1, 5, 600);
        $store->doWork();

        $expectedPayload = json_encode([
            'dailyVisitors' => 0,
            'cashboxes' => [
                ["name" => "1", "state" => "Closed", "length" => 0, "idle" => 0, "currentCustomer" => null]
            ]]);

        $this->assertEquals($expectedPayload, json_encode($store));
    }

    public function testDoWork()
    {
        $store = new Store(1, 5, 600);
        $store->addCustomer(new Customer(10, 60, 1));
        $store->doWork();

        $expectedPayload = json_encode([
            'dailyVisitors' => 1,
            'cashboxes' => [
                ["name" => "1", "state" => "Open", "length" => 1, "idle" => 0, "currentCustomer" => ["goods" => 1, "timeToReady" => 69]]
            ]]);

        $this->assertEquals($expectedPayload, json_encode($store));
    }

    public function testDoWorkDequeue()
    {
        $store = new Store(1, 5, 600);
        $store->addCustomer(new Customer(10, 60, 1));
        $store->addCustomer(new Customer(10, 60, 1));
        for ($i = 0; $i < 140; $i++) {
            $store->doWork();
        }

        $expectedPayload = json_encode([
            'dailyVisitors' => 2,
            'cashboxes' => [
                ["name" => "1", "state" => "Open", "length" => 0, "idle" => 0, "currentCustomer" => null]
            ]]);

        $this->assertEquals($expectedPayload, json_encode($store));
    }

    public function testSerialize()
    {
        $store = new Store(2, 5, 600);

        $expectedPayload = json_encode([
            'dailyVisitors' => 0,
            'cashboxes' => [
                ["name" => "1", "state" => "Closed", "length" => 0, "idle" => 0, "currentCustomer" => null],
                ["name" => "2", "state" => "Closed", "length" => 0, "idle" => 0, "currentCustomer" => null]
            ]]);

        $this->assertEquals($expectedPayload, json_encode($store));
    }

    public function testAddCustomer()
    {
        $store = new Store(2, 5, 600);
        $store->addCustomer(new Customer(10, 60, 1));

        $expectedPayload = json_encode([
            'dailyVisitors' => 1,
            'cashboxes' => [
                ["name" => "1", "state" => "Open", "length" => 1, "idle" => 0, "currentCustomer" => ["goods" => 1, "timeToReady" => 70]],
                ["name" => "2", "state" => "Closed", "length" => 0, "idle" => 0, "currentCustomer" => null]
            ]]);

        $this->assertEquals($expectedPayload, json_encode($store));
    }

    public function testAddCustomerTwice()
    {
        $store = new Store(2, 5, 600);
        $store->addCustomer(new Customer(10, 60, 1));
        $store->addCustomer(new Customer(10, 60, 2));

        $expectedPayload = json_encode([
            'dailyVisitors' => 2,
            'cashboxes' => [
                ["name" => "1", "state" => "Open", "length" => 2, "idle" => 0, "currentCustomer" => ["goods" => 1, "timeToReady" => 70]],
                ["name" => "2", "state" => "Closed", "length" => 0, "idle" => 0, "currentCustomer" => null]
            ]]);

        $this->assertEquals($expectedPayload, json_encode($store));
    }

    public function testAddCustomerFive()
    {
        $store = new Store(2, 5, 600);
        $store->addCustomer(new Customer(10, 60, 1));
        $store->addCustomer(new Customer(10, 60, 2));
        $store->addCustomer(new Customer(10, 60, 2));
        $store->addCustomer(new Customer(10, 60, 2));
        $store->addCustomer(new Customer(10, 60, 2));

        $expectedPayload = json_encode([
            'dailyVisitors' => 5,
            'cashboxes' => [
                ["name" => "1", "state" => "Open", "length" => 5, "idle" => 0, "currentCustomer" => ["goods" => 1, "timeToReady" => 70]],
                ["name" => "2", "state" => "Closed", "length" => 0, "idle" => 0, "currentCustomer" => null]
            ]]);

        $this->assertEquals($expectedPayload, json_encode($store));
    }

    public function testAddCustomerSix()
    {
        $store = new Store(2, 5, 600);
        $store->addCustomer(new Customer(10, 60, 1));
        $store->addCustomer(new Customer(10, 60, 2));
        $store->addCustomer(new Customer(10, 60, 2));
        $store->addCustomer(new Customer(10, 60, 2));
        $store->addCustomer(new Customer(10, 60, 2));
        $store->addCustomer(new Customer(10, 60, 1));

        $expectedPayload = json_encode([
            'dailyVisitors' => 6,
            'cashboxes' => [
                ["name" => "1", "state" => "Open", "length" => 5, "idle" => 0, "currentCustomer" => ["goods" => 1, "timeToReady" => 70]],
                ["name" => "2", "state" => "Open", "length" => 1, "idle" => 0, "currentCustomer" => ["goods" => 1, "timeToReady" => 70]]
            ]]);

        $this->assertEquals($expectedPayload, json_encode($store));
    }

}
