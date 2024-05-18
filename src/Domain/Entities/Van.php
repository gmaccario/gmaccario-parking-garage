<?php

namespace ParkingGarage\Domain\Entities;

use ParkingGarage\Domain\Interfaces\Sizable;

class Van implements Sizable
{
    public function getSize(): float
    {
        return 1.5;
    }
}
