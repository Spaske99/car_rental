<?php

namespace App\Repository;

use App\Entity\Car;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @extends ServiceEntityRepository<Car>
 *
 * @method Car|null find($id, $lockMode = null, $lockVersion = null)
 * @method Car|null findOneBy(array $criteria, array $orderBy = null)
 * @method Car[]    findAll()
 * @method Car[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarRepository extends ServiceEntityRepository
{
    private $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, Car::class);
        $this->manager = $manager;
    }

    public function add(Car $car)
    {      
        $this->manager->persist($car);
        $this->manager->flush();
    }

    public function update(Car $car): Car
    {
        $this->manager->persist($car);
        $this->manager->flush();

        return $car;
    }

    public function delete(Car $car)
    {
        $this->manager->remove($car);
        $this->manager->flush();
    }
}
