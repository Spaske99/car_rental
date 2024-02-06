<?php

namespace App\Service;

use App\DTO\UserDTO;
use App\Entity\Rent;
use App\Entity\Role;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserService 
{
    private $manager;
    private $userRepository;

    public function __construct(EntityManagerInterface $manager, UserRepository $userRepository)
    {
        $this->manager = $manager;
        $this->userRepository = $userRepository;
    }

    // CREATE USER
    public function create($data)
    {
        $firstName = $data['firstName'];
        $lastName = $data['lastName'];
        $email = $data['email'];
        $password = $data['password'];
        $role = $data['role'];

        $user = new User();

        $user
            ->setFirstName($firstName)
            ->setLastName($lastName)
            ->setEmail($email)
            ->setPassword($password)
            ->setRole($this->manager->getRepository(Role::class)->find($role));

        $this->userRepository->create($user);
    }

    // GET_ALL USERS
    public function getAll($users)
    {
        $data = [];

        foreach ($users as $user) {
            $data[] = $this->getUserDTO($user)->jsonSerialize();
        }

        return $data;
    }

    // GET USER
    public function get($user)
    {
        return $this->getUserDTO($user)->jsonSerialize();
    }

    // UPDATE USER
    public function update($user, $data)
    {
        empty($data['firstName']) ? true : $user->setFirstName($data['firstName']);
        empty($data['lastName']) ? true : $user->setLastName($data['lastName']);
        empty($data['email']) ? true : $user->setEmail($data['email']);
        empty($data['password']) ? true : $user->setPassword($data['password']);

        $this->userRepository->update($user);
        
        return $this->getUserDTO($user)->jsonSerialize();
    }

    // DELETE USER AND ASSOCIATED RENTS
    public function delete($user)
    {
        $rents = $this->manager->getRepository(Rent::class)->findByUser($user);

        foreach ($rents as $rent) {
            $this->manager->remove($rent);
        }

        $this->userRepository->delete($user);
    }

    // USER DTO
    public function getUserDTO($user): UserDTO
    {
        return new UserDTO(
            $user->getId(),
            $user->getFirstName(),
            $user->getLastName(),
            $user->getEmail(),
            $user->getPassword(),
            $user->getRole()
        );
    }
}