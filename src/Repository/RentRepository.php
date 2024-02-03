<?php

namespace App\Repository;

use App\Entity\Rent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

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

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, Rent::class);
        $this->manager = $manager;
    }

    public function add(Rent $rent)
    {
        $this->manager->persist($rent);
        $this->manager->flush();
    }

    public function update(Rent $rent): Rent
    {
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
