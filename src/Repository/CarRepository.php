<?php

namespace App\Repository;

use App\Entity\Car;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

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

    public function __construct
    (
        ManagerRegistry $registry,
        EntityManagerInterface $manager
    )
    {
        parent::__construct($registry, Car::class);
        $this->manager = $manager;
    }

    public function add($brand, $model, $dailyPrice, $description, $image)
    {
        $car = new car();
       
        $car
            ->setBrand($brand)
            ->setModel($model)
            ->setDailyPrice($dailyPrice)
            ->setDescription($description)
            ->setImage($image);
        
        $this->manager->persist($car);
        $this->manager->flush();
    }
}
