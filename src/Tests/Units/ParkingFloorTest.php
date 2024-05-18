<?php

namespace ParkingGarage\Tests\Units;

use PHPUnit\Framework\TestCase;
use ParkingGarage\Domain\Entities\ParkingFloor;
use ParkingGarage\Domain\Entities\Car;
use ParkingGarage\Domain\Entities\Van;
use ParkingGarage\Domain\Entities\Motorcycle;

class ParkingFloorTest extends TestCase
{
    protected ParkingFloor $floor;

    protected function setUp(): void
    {
        $this->floor = new ParkingFloor(10.0);
    }

    public function testHasSpaceWithCar(): void
    {
        $car = new Car();
        $this->assertTrue($this->floor->hasSpace($car), "Expected space for one car");
    }

    public function testHasSpaceWithVan(): void
    {
        $van = new Van();
        $this->assertTrue($this->floor->hasSpace($van), "Expected space for one van");
    }

    public function testHasSpaceWithMotorcycle(): void
    {
        $motorcycle = new Motorcycle();
        $this->assertTrue($this->floor->hasSpace($motorcycle), "Expected space for one motorcycle");
    }

    public function testHasSpaceVanExceedsCapacity(): void
    {
        for($i = 0; $i <= 5; $i++) {
            $van = new Van();
            $this->floor->park($van); // Van occupies 1.5 car spots
        }

        $anotherVan = new Van();
        $this->assertFalse($this->floor->hasSpace($anotherVan), "Expected failure when parking would exceed capacity");
    }

    public function testParkCar(): void
    {
        $car = new Car();
        $this->assertTrue($this->floor->park($car), "Expected to successfully park a car");
    }

    public function testParkMotorcycle(): void
    {
        $motorcycle = new Motorcycle();
        $this->assertTrue($this->floor->park($motorcycle), "Expected to successfully park a motorcycle");
    }

    public function testParkVan(): void
    {
        $van = new Van();
        $this->assertTrue($this->floor->park($van), "Expected to successfully park a motorcycle");
    }

    public function testParkVanExceedsCapacity(): void
    {
        for($i = 0; $i <= 5; $i++) {
            $van = new Van();
            $this->floor->park($van); // Van occupies 1.5 car spots
        }

        $anotherVan = new Van();
        $this->assertFalse($this->floor->park($anotherVan), "Expected failure when parking would exceed capacity");
    }

    public function testParkVanAfterAnotherVanLeft(): void
    {
        for($i = 0; $i <= 5; $i++) {
            $van = new Van();
            $this->floor->park($van); // Van occupies 1.5 car spots
        }

        $anotherVan = new Van();
        $this->assertFalse($this->floor->park($anotherVan), "Expected failure when parking would exceed capacity");

        $this->floor->leave($van); // Remove the last parked Van
        $this->assertTrue($this->floor->park($anotherVan), "Expected space after removing the van");
    }

    public function testLeaveMotorcycle(): void
    {
        $motorcycle = new Motorcycle();
        $this->floor->park($motorcycle);

        $this->floor->leave($motorcycle); // Remove the motorcycle
        $this->assertTrue($this->floor->hasSpace($motorcycle), "Expected space after removing the motorcycle");
    }

    public function testLeaveVan(): void
    {
        $van = new Van();
        $this->floor->park($van);

        $this->floor->leave($van); // Remove the van
        $this->assertTrue($this->floor->hasSpace($van), "Expected space after removing the van");
    }
}
