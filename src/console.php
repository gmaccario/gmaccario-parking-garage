#!/usr/bin/env php
<?php

require __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

use ParkingGarage\Domain\Entities\Car;
use ParkingGarage\Domain\Entities\Motorcycle;
use ParkingGarage\Domain\Entities\ParkingFloor;
use ParkingGarage\Domain\Entities\ParkingGarage;
use ParkingGarage\Domain\Entities\Van;
use ParkingGarage\Domain\Exceptions\NoSpaceException;

/**
 * =========================================================================================
 * This file is just an example considering this scenario with a multi-floor parking garage
 * =========================================================================================
 */

/**
 * Create 3 floors with different capacity each one
 */
$floors = array_map(function ($capacity) {
    return new ParkingFloor($capacity);
}, [1, 15, 20]);

/**
 * Create the garage, and pass the floors to the constructor
 */
$garage = new ParkingGarage($floors);

/**
 * In this example:
 * A car enters and takes the only available spot on the ground floor (floor n.0).
 * A van tries to park next, but since the ground floor is full, there's no space for it.
 * NOTE: Vans need at least 1.5 spaces, but there's only one spot available.
 * A motorcycle follows and parks on the floor n.1, where there's room.
 *
 * Now, let's change the vehicle order:
 * If the van arrives first and the ground floor capacity is only one spot, it can't park because it requires more space.
 *
 * However, if the ground floor has two spots and the vehicle order is [Van, Motorcycle, Car], then:
 * The van and motorcycle can park on the ground floor.
 * The car can park on the first floor (floor n.1).
 */
$vehicles = [new Car(), new Van(), new Motorcycle()];

foreach ($vehicles as $vehicle) {
    try {
        $floor = $garage->park($vehicle);
        if ($floor instanceof ParkingFloor) {
            echo 'Welcome, please go in.'.PHP_EOL;
        }
    } catch (NoSpaceException $e) {
        echo $e->getMessage().PHP_EOL;
    }
}
