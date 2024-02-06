<?php

namespace App\Service;

use App\DTO\RentDTO;
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

    // CREATE RENT
    public function create($data)
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

    // GET_ALL RENTS
    public function getAll($rents)
    {
        $data = [];

        foreach($rents as $rent) {
            $data[] = $this->getRentDTO($rent)->jsonSerialize();
        }

        return $data;
    }

    // GET RENT
    public function get($rent)
    {
        return $this->getRentDTO($rent)->jsonSerialize();
    }

    // UPDATE RENT
    public function update($rent, $data)
    {
        empty($data['rentedFrom']) ? true : $rent->setRentedFrom(new DateTime($data['rentedFrom']));
        empty($data['rentedUntil']) ? true : $rent->setRentedUntil(new DateTime($data['rentedUntil']));
        empty($data['car']) ? true : $rent->setCar($this->manager->getRepository(Car::class)->find($data['car']));

        $this->rentRepository->update($rent);
        
        return $this->getRentDTO($rent)->jsonSerialize();
    }

    // RENT DTO
    public function getRentDTO($rent): RentDTO
    {
        return new RentDTO(
            $rent->getId(),
            $rent->getRentedFrom(),
            $rent->getRentedUntil(),
            $rent->isApproved(),
            $rent->getUser(),
            $rent->getCar()
        );
    }
}