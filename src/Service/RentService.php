<?php

namespace App\Service;

use App\DTO\RentDTO;
use App\Entity\Car;
use App\Entity\Rent;
use App\Entity\User;
use App\Repository\RentRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        $rentedFrom = new DateTime($data['rentedFrom']);
        $rentedUntil = new DateTime($data['rentedUntil']);
        $approved = $data['approved'];
        $user = $this->manager->getRepository(User::class)->find($data['user']);
        $car = $this->manager->getRepository(Car::class)->find($data['car']);
        
        if (empty($rentedFrom) || empty($rentedUntil) || empty($approved) || empty($user) || empty($car)) {
            throw new BadRequestHttpException('Expecting mandatory parameters!');
        }

        $rent = new Rent();

        $rent
            ->setRentedFrom($rentedFrom)
            ->setRentedUntil($rentedUntil)
            ->setApproved($approved)
            ->setUser($user)
            ->setCar($car);

        $this->rentRepository->add($rent);
    }

    // GET_ALL RENTS
    public function getAll()
    {
        $rents = $this->rentRepository->findAll();

        if (empty($rents)) {
            throw new NotFoundHttpException('No rents found!');
        }

        $data = [];

        foreach($rents as $rent) {
            $data[] = $this->getRentDTO($rent)->jsonSerialize();
        }

        return $data;
    }

    // GET RENT
    public function get($id)
    {
        $rent = $this->rentRepository->find($id);

        if ($rent === null) {
            throw new NotFoundHttpException("Rent with ID $id not found!");
        }

        return $this->getRentDTO($rent)->jsonSerialize();
    }

    // UPDATE RENT
    public function update($id, $data)
    {
        $rent = $this->rentRepository->find($id);

        if ($rent === null) {
            throw new NotFoundHttpException("Rent with ID $id not found!");
        }

        empty($data['rentedFrom']) ? true : $rent->setRentedFrom(new DateTime($data['rentedFrom']));
        empty($data['rentedUntil']) ? true : $rent->setRentedUntil(new DateTime($data['rentedUntil']));
        empty($data['car']) ? true : $rent->setCar($this->manager->getRepository(Car::class)->find($data['car']));

        $this->rentRepository->update($rent);
        
        return $this->getRentDTO($rent)->jsonSerialize();
    }

    // DELETE RENT
    public function delete($id)
    {
        $rent = $this->rentRepository->find($id);

        if ($rent === null) {
            throw new NotFoundHttpException("Rent with ID $id not found!");
        }

        $this->rentRepository->delete($rent);
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