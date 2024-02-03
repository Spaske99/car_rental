<?php

namespace App\Service;

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

    public function add($data)
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

        $this->userRepository->add($user);
    }

    public function getAll($users)
    {
        $data = [];

        foreach ($users as $user) {
            $data[] = [
                'id' => $user->getId(),
                'firstName' => $user->getFirstName(),
                'lastName' => $user->getLastName(),
                'email' => $user->getEmail(),
                'password' => $user->getPassword(),
                'created' => $user->getCreated(),
                'updated' => $user->getUpdated(),
                'role' => $user->getRole()->getRoleType()
            ];
        }

        return $data;
    }

    public function get($user)
    {
        $data = [
            'id' => $user->getId(),
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'created' => $user->getCreated(),
            'updated' => $user->getUpdated(),
            'role' => $user->getRole()->getRoleType()
        ];

        return $data;
    }

    public function update($user, $data)
    {
        empty($data['firstName']) ? true : $user->setFirstName($data['firstName']);
        empty($data['lastName']) ? true : $user->setLastName($data['lastName']);
        empty($data['email']) ? true : $user->setEmail($data['email']);
        empty($data['password']) ? true : $user->setPassword($data['password']);

        $updatedUser = $this->userRepository->update($user);
        
        return $updatedUser->jsonSerialize();
    }
}