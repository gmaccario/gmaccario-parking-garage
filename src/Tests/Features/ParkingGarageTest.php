<?php

namespace ParkingGarage\Tests\Features;

use PHPUnit\Framework\TestCase;
use ParkingGarage\Domain\Entities\ParkingGarage;
use ParkingGarage\Domain\Entities\ParkingFloor;
use ParkingGarage\Domain\Entities\Car;
use ParkingGarage\Domain\Entities\Van;
use ParkingGarage\Domain\Entities\Motorcycle;
use ParkingGarage\Domain\Exceptions\NoSpaceException;

class ParkingGarageTest extends TestCase
{
    /**
     * @var ParkingFloor[]
     */
    protected array $floors;
    protected ParkingGarage $garage;

    protected function setUp(): void
    {
        parent::setUp();

        $this->floors = array_map(function ($capacity) {
            return new ParkingFloor($capacity);
        }, [1, 15, 20]);

        $this->garage = new ParkingGarage($this->floors);
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @throws NoSpaceException
     */
    public function testParkingCarSuccessfully(): void
    {
        $car = new Car();

        $result = $this->garage->park($car);

        $this->assertInstanceOf(ParkingFloor::class, $result);
    }

    /**
     * @throws NoSpaceException
     */
    public function testNoSpaceAvailableOnGroundFloorForVan(): void
    {
        $van = new Van();

        $this->expectException(NoSpaceException::class);
        $this->expectExceptionMessage("Sorry, no spaces left on the ground floor.");

        $this->garage->park($van);
    }

    /**
     * @throws NoSpaceException
     */
    public function testParkingMotorcycleSuccessfully(): void
    {
        $motorcycle = new Motorcycle();

        $result = $this->garage->park($motorcycle);

        $this->assertInstanceOf(ParkingFloor::class, $result);
    }

    /**
     * @throws NoSpaceException
     */
    public function testNoSpaceInGarage(): void
    {
        foreach([1, 15, 20] as $spot) {
            for($i = 1; $i <= $spot; $i++) {
                $car = new Car();

                $this->garage->park($car);
            }
        }

        $car = new Car();

        $this->expectException(NoSpaceException::class);
        $this->expectExceptionMessage("Sorry, no spaces left.");

        $this->garage->park($car);
    }
}
