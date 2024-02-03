<?php

namespace App\Service;

use App\Entity\Car;
use App\Entity\Rent;
use App\Entity\User;
use App\Repository\RentRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class RentService 
{
    private $manager;
    private $rentRepository;

    public function __construct(EntityManagerInterface $manager,RentRepository $rentRepository)
    {
        $this->manager = $manager;
        $this->rentRepository = $rentRepository;
    }

    public function add($data)
    {
        $rentedFrom = $data['rentedFrom'];
        $rentedUntil = $data['rentedUntil'];
        $approved = $data['approved'];
        $user = $data['user'];
        $car = $data['car'];

        $rent = new Rent();

        $rent
            ->setRentedFrom(new DateTime($rentedFrom))
            ->setRentedUntil(new DateTime($rentedUntil))
            ->setApproved($approved)
            ->setUser($this->manager->getRepository(User::class)->find($user))
            ->setCar($this->manager->getRepository(Car::class)->find($car));

        $this->rentRepository->add($rent);
    }

    public function getAll($rents)
    {
        $data = [];

        foreach($rents as $rent) {
            $data[] = [
                'id' => $rent->getId(),
                'rentedFrom' => $rent->getRentedFrom()->format('Y-m-d H:i:s'),
                'rentedUntil' => $rent->getRentedUntil()->format('Y-m-d H:i:s'),
                'approved' => $rent->isApproved(),
                'user' => [
                    'firstName' => $rent->getUser()->getFirstName(),
                    'lastName' => $rent->getUser()->getLastName(),
                    'email' => $rent->getUser()->getEmail()
                ],
                'car' => [
                    'brand' => $rent->getCar()->getBrand(), 
                    'model' => $rent->getCar()->getModel()
                ]
            ];
        }

        return $data;
    }

    public function get($rent)
    {
        $data = [
            'id' => $rent->getId(),
            'rentedFrom' => $rent->getRentedFrom()->format('Y-m-d H:i:s'),
            'rentedUntil' => $rent->getRentedUntil()->format('Y-m-d H:i:s'),
            'approved' => $rent->isApproved(),
            'user' => [
                'firstName' => $rent->getUser()->getFirstName(),
                'lastName' => $rent->getUser()->getLastName(),
                'email' => $rent->getUser()->getEmail()
            ],
            'car' => [
                'brand' => $rent->getCar()->getBrand(), 
                'model' => $rent->getCar()->getModel()
            ]
        ];

        return $data;
    }

    public function update($rent, $data)
    {
        empty($data['rentedFrom']) ? true : $rent->setRentedFrom(new DateTime($data['rentedFrom']));
        empty($data['rentedUntil']) ? true : $rent->setRentedUntil(new DateTime($data['rentedUntil']));
        empty($data['car']) ? true : $rent->setCar($this->manager->getRepository(Car::class)->find($data['car']));

        $updatedRent = $this->rentRepository->update($rent);
        
        return $updatedRent->jsonSerialize();
    }
}