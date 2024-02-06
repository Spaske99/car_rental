<?php

namespace App\DTO;

use App\Entity\Car;
use App\Entity\User;
use DateTime;

class RentDTO
{
    /**
     * @param int id
     * @param DateTime rentedFrom 
     * @param DateTime rentedUntil 
     * @param string approved
     * @param User user
     * @param Car car
     */

    public function __construct(
        public readonly int       $id,
        public readonly DateTime  $rentedFrom,
        public readonly DateTime  $rentedUntil,
        public readonly string    $approved,
        public readonly User      $user,
        public readonly Car       $car
    ) {
    }

    public function jsonSerialize(): array
    {
        return 
        [
            'id'          => $this->id,
            'rentedFrom'  => $this->rentedFrom->format('Y-m-d h:m:s'),
            'rentedUntil' => $this->rentedUntil->format('Y-m-d h:m:s'),
            'user' => [
                'id'       => $this->car->getId(),
                'firsName' => $this->user->getFirstName(),
                'lastName' => $this->user->getLastName(),
                'email'    => $this->user->getEmail()
            ],
            'car' => [
                'id'          => $this->car->getId(),
                'brand'       => $this->car->getBrand(),
                'model'       => $this->car->getModel(),
                'dailyPrice'  => $this->car->getDailyPrice(),
                'description' => $this->car->getDescription(),
                // 'image' => $this->car->getImage()
            ]
        ];
    }
}
