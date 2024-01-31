<?php

namespace App\Repository;

use App\Entity\Car;
use App\Entity\Rent;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Rent>
 *
 * @method Rent|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rent|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rent[]    findAll()
 * @method Rent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RentRepository extends ServiceEntityRepository
{
    private $manager;

    public function __construct
    (
        ManagerRegistry $registry, 
        EntityManagerInterface $manager
    )
    {
        parent::__construct($registry, Rent::class);
        $this->manager = $manager;
    }

    public function add($rentedFrom, $rentedUntil, $approved, $userId, $carId)
    {
        $rent = new Rent();

        $rent
            ->setRentedFrom(new DateTime($rentedFrom))
            ->setRentedUntil(new DateTime($rentedUntil))
            ->setApproved($approved)
            ->setUser($this->manager->getRepository(User::class)->find($userId))
            ->setCar($this->manager->getRepository(Car::class)->find($carId));

        $this->manager->persist($rent);
        $this->manager->flush();
    }

    public function update(Rent $rent, $rentedFrom, $rentedUntil, $car): Rent
    {
        empty($data['rentedFrom'])? true : $rent->setRentedFrom(new DateTime($rentedFrom));
        empty($data['rentedUntil'])? true : $rent->setRentedUntil(new DateTime($rentedUntil));
        empty($data['car'])? true : $rent->setCar($this->manager->getRepository(Car::class)->find($car));

        $this->manager->persist($rent);
        $this->manager->flush();

        return $rent;
    }

    public function delete(Rent $rent)
    {
        $this->manager->remove($rent);
        $this->manager->flush();
    }
}
