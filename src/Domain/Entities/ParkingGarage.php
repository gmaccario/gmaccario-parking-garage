<?php

namespace ParkingGarage\Domain\Entities;

use ParkingGarage\Domain\Exceptions\NoSpaceException;
use ParkingGarage\Domain\Interfaces\Sizable;
use ParkingGarage\Domain\Helpers\Constants;

class ParkingGarage
{
    /**
     * @param ParkingFloor[] $floors
     */
    public function __construct(protected array $floors)
    {
    }

    /**
     * @param Sizable $sizable
     * @return ParkingFloor
     * @throws NoSpaceException
     */
    public function park(Sizable $sizable): ParkingFloor
    {
        /**
         * Special handling for Vans - can only park on the first floor
         */
        if ($sizable instanceof Van) {
            $groundFloor = $this->floors[Constants::PARKING_GROUND_FLOOR];
            if ($groundFloor->hasSpace($sizable)) {
                $groundFloor->park($sizable);

                return $groundFloor;
            }
            throw new NoSpaceException('Sorry, no spaces left on the ground floor.');
        }

        /**
         * For other vehicles, find a floor with available space
         */
        foreach ($this->floors as $floor) {
            if ($floor->hasSpace($sizable)) {
                $floor->park($sizable);

                return $floor;
            }
        }

        throw new NoSpaceException('Sorry, no spaces left.');
    }
}
