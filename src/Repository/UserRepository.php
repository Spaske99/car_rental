<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Role;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    private $manager;

    public function __construct
    (
        ManagerRegistry $registry,
        EntityManagerInterface $manager
    )
    {
        parent::__construct($registry, User::class);
        $this->manager = $manager;
    }

    public function saveUser($firstName, $lastName, $email, $password, $created, $updated, $roleId)
    {
        $role = $this->manager->getRepository(Role::class)->find($roleId);

        $newUser = new User();

        $newUser
            ->setFirstName($firstName)
            ->setLastName($lastName)
            ->setEmail($email)
            ->setPassword($password)
            ->setCreated(new \DateTimeImmutable($created))
            ->setUpdated(new \DateTime($updated))
            ->setRole($role);

        $this->manager->persist($newUser);
        $this->manager->flush();
    }
}
