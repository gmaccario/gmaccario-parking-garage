<?php

namespace ParkingGarage\Domain\Entities;

use ParkingGarage\Domain\Interfaces\Sizable;

class Motorcycle implements Sizable
{
    public function getSize(): float
    {
        return 0.5;
    }
}
