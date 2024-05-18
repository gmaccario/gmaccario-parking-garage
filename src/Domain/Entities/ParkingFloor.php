<?php

namespace ParkingGarage\Domain\Entities;

use ParkingGarage\Domain\Interfaces\Sizable;

class ParkingFloor
{
    /**
     * @param  float  $capacity  // total capacity in car spots
     * @param  float  $currentLoad  // current load in car spots
     */
    public function __construct(protected float $capacity, protected float $currentLoad = 0.0)
    {
    }

    /**
     * @param Sizable $sizable
     * @return bool
     */
    public function hasSpace(Sizable $sizable): bool
    {
        return ($this->currentLoad + $sizable->getSize())
            <= $this->capacity;
    }

    /**
     * @param Sizable $sizable
     * @return bool
     */
    public function park(Sizable $sizable): bool
    {
        if ($this->hasSpace($sizable)) {
            $this->currentLoad += $sizable->getSize();

            return true;
        }

        return false;
    }

    /**
     * @param Sizable $sizable
     */
    public function leave(Sizable $sizable): void
    {
        $this->currentLoad -= $sizable->getSize();
    }
}
