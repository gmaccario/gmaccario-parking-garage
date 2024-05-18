<?php

use PHPUnit\Framework\TestCase;
use ParkingGarage\Domain\Entities\Car;
use ParkingGarage\Domain\Interfaces\Sizable;

class CarTest extends TestCase
{
    public function testCarSize(): void
    {
        $car = new Car();
        $expectedSize = 1.0;

        $actualSize = $car->getSize();

        $this->assertEquals($expectedSize, $actualSize, "Car size should be $expectedSize.");
    }

    public function testCarImplementsSizable(): void
    {
        $car = new Car();

        $this->assertInstanceOf(Sizable::class, $car, "Car should implement Sizable interface.");
    }
}
